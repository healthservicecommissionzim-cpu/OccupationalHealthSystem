<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\IncidentNotification;
use App\Models\User;
use App\Notifications\IncidentHRReviewNotification;
use App\Notifications\DocumentUploadedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Dispatch alert when new case is created and escalated to HR.
     *
     * @param Incident $case
     * @return void
     */
    public function dispatchCaseCreatedAlert(Incident $case): void
    {
        try {
            // Get all CoS department users
            $cosUsers = User::role('hsc_conditions')->get();

            foreach ($cosUsers as $user) {
                // Send database notification
                $notification = IncidentNotification::create([
                    'case_id' => $case->id,
                    'recipient_user_id' => $user->id,
                    'notification_type' => 'CASE_CREATED',
                    'channel' => 'database',
                    'status' => 'SENT',
                    'sent_at' => now(),
                ]);

                // Send in-app notification via mail if email exists
                if ($user->email) {
                    $user->notify(new IncidentHRReviewNotification($case));
                }
            }

            Log::info('Case created alerts dispatched', [
                'case_id' => $case->id,
                'reference' => $case->incident_reference,
                'recipients' => $cosUsers->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to dispatch case created alerts', [
                'case_id' => $case->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Dispatch alert when case reaches HSC Resolution.
     *
     * @param Incident $case
     * @return void
     */
    public function dispatchCaseCompletedAlert(Incident $case): void
    {
        try {
            $cosUsers = User::role('hsc_conditions')->get();

            foreach ($cosUsers as $user) {
                IncidentNotification::create([
                    'case_id' => $case->id,
                    'recipient_user_id' => $user->id,
                    'notification_type' => 'CASE_COMPLETED',
                    'channel' => 'database',
                    'status' => 'SENT',
                    'sent_at' => now(),
                ]);

                if ($user->email) {
                    $user->notify(new IncidentHRReviewNotification($case));
                }
            }

            Log::info('Case completion alerts dispatched', [
                'case_id' => $case->id,
                'reference' => $case->incident_reference,
                'recipients' => $cosUsers->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to dispatch case completion alerts', [
                'case_id' => $case->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Dispatch nudge/reminder notification to current case holder.
     *
     * @param Incident $case
     * @param User $holdingUser
     * @return bool Success status
     */
    public function dispatchNudgeNotification(Incident $case, User $holdingUser): bool
    {
        try {
            // Check if notification already exists (idempotency)
            $existingNotification = IncidentNotification::where([
                'case_id' => $case->id,
                'recipient_user_id' => $holdingUser->id,
                'notification_type' => 'NUDGE_SENT',
            ])->where('created_at', '>=', now()->subHour())->first();

            if ($existingNotification) {
                Log::warning('Nudge notification already sent recently', [
                    'case_id' => $case->id,
                    'user_id' => $holdingUser->id,
                ]);
                return false;
            }

            // Log notification
            IncidentNotification::create([
                'case_id' => $case->id,
                'recipient_user_id' => $holdingUser->id,
                'notification_type' => 'NUDGE_SENT',
                'channel' => 'email',
                'status' => 'SENT',
                'sent_at' => now(),
            ]);

            // Send email notification
            if ($holdingUser->email) {
                $holdingUser->notify(new IncidentHRReviewNotification($case));
            }

            // Send SMS if available
            if ($holdingUser->phone_number && config('services.sms.enabled')) {
                $this->sendNudgeSMS($holdingUser, $case);
            }

            Log::info('Nudge notification sent', [
                'case_id' => $case->id,
                'reference' => $case->incident_reference,
                'recipient' => $holdingUser->name,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to dispatch nudge notification', [
                'case_id' => $case->id,
                'user_id' => $holdingUser->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send SMS nudge notification.
     *
     * @param User $user
     * @param Incident $case
     * @return void
     */
    private function sendNudgeSMS(User $user, Incident $case): void
    {
        try {
            $smsService = app(SMSService::class);
            $message = "HSC-OHS: Case {$case->incident_reference} requires your attention. "
                . "Please log in to review. Visit injury.hsc.org.zw";

            $smsService->send($user->phone_number, $message);

            Log::info('Nudge SMS sent', [
                'case_id' => $case->id,
                'user_id' => $user->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send nudge SMS', [
                'case_id' => $case->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get all notifications for a user.
     *
     * @param User $user
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserNotifications(User $user, int $limit = 50)
    {
        return IncidentNotification::where('recipient_user_id', $user->id)
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread notification count for user.
     *
     * @param User $user
     * @return int
     */
    public function getUnreadCount(User $user): int
    {
        return IncidentNotification::where('recipient_user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Mark notification as read.
     *
     * @param IncidentNotification $notification
     * @return void
     */
    public function markAsRead(IncidentNotification $notification): void
    {
        $notification->update(['read_at' => now()]);

        Log::info('Notification marked as read', [
            'notification_id' => $notification->id,
        ]);
    }

    /**
     * Mark all notifications for user as read.
     *
     * @param User $user
     * @return int Number of notifications updated
     */
    public function markAllAsRead(User $user): int
    {
        $count = IncidentNotification::where('recipient_user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        Log::info('All notifications marked as read', [
            'user_id' => $user->id,
            'count' => $count,
        ]);

        return $count;
    }

    /**
     * Dispatch document upload notification to assigned HR user.
     *
     * @param Incident $case
     * @param string $documentType
     * @return bool Success status
     */
    public function dispatchDocumentUploadNotification(Incident $case, string $documentType): bool
    {
        try {
            // Get assigned HR user
            if (!$case->routed_to_hr_user_id) {
                Log::warning('No HR user assigned for document upload notification', [
                    'case_id' => $case->id,
                ]);
                return false;
            }

            $hrUser = User::find($case->routed_to_hr_user_id);
            if (!$hrUser) {
                Log::warning('Assigned HR user not found', [
                    'case_id' => $case->id,
                    'hr_user_id' => $case->routed_to_hr_user_id,
                ]);
                return false;
            }

            // Log notification
            IncidentNotification::create([
                'case_id' => $case->id,
                'recipient_user_id' => $hrUser->id,
                'notification_type' => 'DOCUMENT_UPLOAD',
                'channel' => 'email',
                'status' => 'SENT',
                'sent_at' => now(),
            ]);

            // Send email
            if ($hrUser->email) {
                $hrUser->notify(new DocumentUploadedNotification($case, $documentType));
            }

            // Send SMS if phone available
            if ($hrUser->phone_number && config('services.sms.enabled')) {
                $this->sendDocumentUploadSMS($hrUser, $case);
            }

            Log::info('Document upload notification sent', [
                'case_id' => $case->id,
                'reference' => $case->incident_reference,
                'document_type' => $documentType,
                'recipient' => $hrUser->name,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to dispatch document upload notification', [
                'case_id' => $case->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send document upload SMS notification.
     *
     * @param User $user
     * @param Incident $case
     * @return void
     */
    private function sendDocumentUploadSMS(User $user, Incident $case): void
    {
        try {
            $smsService = app(SMSService::class);
            $message = "HSC-OHS: New documents uploaded for incident {$case->incident_reference}. "
                . "Please log in to review. Visit injury.hsc.org.zw";

            $smsService->send($user->phone_number, $message);

            Log::info('Document upload SMS sent', [
                'case_id' => $case->id,
                'user_id' => $user->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send document upload SMS', [
                'case_id' => $case->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send SLA breach alert to CoS department.
     *
     * @param Incident $case
     * @param int $daysOverdue
     * @return void
     */
    public function dispatchSLABreachAlert(Incident $case, int $daysOverdue): void
    {
        try {
            $cosUsers = User::role('hsc_conditions')->get();

            foreach ($cosUsers as $user) {
                IncidentNotification::create([
                    'case_id' => $case->id,
                    'recipient_user_id' => $user->id,
                    'notification_type' => 'SLA_BREACH',
                    'channel' => 'database',
                    'status' => 'SENT',
                    'sent_at' => now(),
                ]);
            }

            Log::info('SLA breach alert dispatched', [
                'case_id' => $case->id,
                'reference' => $case->incident_reference,
                'days_overdue' => $daysOverdue,
                'recipients' => $cosUsers->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to dispatch SLA breach alert', [
                'case_id' => $case->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
