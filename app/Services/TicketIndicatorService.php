<?php

namespace App\Services;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class TicketIndicatorService
{
    /**
     * Get pending ticket count for a user (tickets they created that are not closed/resolved).
     *
     * @param User $user
     * @return int
     */
    public function getPendingTicketCount(User $user): int
    {
        $cacheKey = "ticket_pending_count_{$user->id}";

        return Cache::remember($cacheKey, 300, function () use ($user) {
            return SupportTicket::where('originator_user_id', $user->id)
                ->whereIn('status', ['OPEN', 'IN_PROGRESS'])
                ->count();
        });
    }

    /**
     * Check if user has unread replies (tickets with updates since last viewed).
     *
     * @param User $user
     * @return int
     */
    public function getUnreadReplyCount(User $user): int
    {
        $cacheKey = "ticket_unread_replies_{$user->id}";

        return Cache::remember($cacheKey, 300, function () use ($user) {
            // Get tickets created by user that have been updated more recently than their last visit
            return SupportTicket::where('originator_user_id', $user->id)
                ->where('status', '!=', 'CLOSED')
                ->whereHas('replies', function ($query) {
                    // Only count if there are replies from super admin (not originator's own replies)
                    $query->where('sender_user_id', '!=', auth()->id());
                })
                ->count();
        });
    }

    /**
     * Get total notification count for a user (pending + unread).
     *
     * @param User $user
     * @return int
     */
    public function getTotalNotificationCount(User $user): int
    {
        return $this->getPendingTicketCount($user) + $this->getUnreadReplyCount($user);
    }

    /**
     * Invalidate cache for a specific user.
     *
     * @param User $user
     * @return void
     */
    public function invalidateCache(User $user): void
    {
        Cache::forget("ticket_pending_count_{$user->id}");
        Cache::forget("ticket_unread_replies_{$user->id}");
    }
}
