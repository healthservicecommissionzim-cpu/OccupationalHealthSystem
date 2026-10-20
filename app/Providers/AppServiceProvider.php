<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Events\IncidentEscalatedToHR;
use App\Listeners\SendHRIncidentEscalationNotification;
use App\Events\TicketCreated;
use App\Listeners\RouteTicketToSuperAdmin;
use App\Events\TicketReplyAdded;
use App\Listeners\NotifyOriginatorOfReply;
use App\Events\TicketStatusChanged;
use App\Listeners\NotifyOriginatorOfStatusChange;
use App\Events\Case90DayMilestoneReached;
use App\Events\Case180DayMilestoneReached;
use App\Listeners\SendMilestoneNotifications;
use App\Events\IncidentWithWitnessesSubmitted;
use App\Listeners\SendWitnessNotifications;
use App\Events\WitnessStatementSubmitted;
use App\Listeners\HandleWitnessStatementSubmitted;
use App\Events\IncidentCreated;
use App\Listeners\SendIncidentConfirmationEmail;
use App\Listeners\SendSupervisorIncidentAlert;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS scheme for all URLs in production
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
        // Register event listeners
        \Illuminate\Support\Facades\Event::listen(
            IncidentEscalatedToHR::class,
            SendHRIncidentEscalationNotification::class
        );

        // Register document upload notification listener
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\IncidentDocumentUploaded::class,
            \App\Listeners\SendDocumentUploadNotification::class
        );

        // Register ticket creation and routing listener
        \Illuminate\Support\Facades\Event::listen(
            TicketCreated::class,
            RouteTicketToSuperAdmin::class
        );

        // Register ticket notification listeners
        \Illuminate\Support\Facades\Event::listen(
            TicketReplyAdded::class,
            NotifyOriginatorOfReply::class
        );

        \Illuminate\Support\Facades\Event::listen(
            TicketStatusChanged::class,
            NotifyOriginatorOfStatusChange::class
        );

        // Register leave milestone notification listeners
        \Illuminate\Support\Facades\Event::listen(
            Case90DayMilestoneReached::class,
            SendMilestoneNotifications::class
        );

        \Illuminate\Support\Facades\Event::listen(
            Case180DayMilestoneReached::class,
            SendMilestoneNotifications::class
        );

        // Register witness notification listener (queued for async SMS dispatch)
        \Illuminate\Support\Facades\Event::listen(
            IncidentWithWitnessesSubmitted::class,
            SendWitnessNotifications::class
        );

        // Register witness statement submission listener
        \Illuminate\Support\Facades\Event::listen(
            WitnessStatementSubmitted::class,
            HandleWitnessStatementSubmitted::class
        );

        // Register incident creation listeners
        \Illuminate\Support\Facades\Event::listen(
            IncidentCreated::class,
            SendIncidentConfirmationEmail::class
        );

        \Illuminate\Support\Facades\Event::listen(
            IncidentCreated::class,
            SendSupervisorIncidentAlert::class
        );

        // Register Gate callbacks for permission checks
        // This allows @can('permission_slug') to work in Blade templates
        Gate::define('view_incidents', function ($user) {
            return $user->hasPermission('view_incidents');
        });
        
        Gate::define('create_incidents', function ($user) {
            return $user->hasPermission('create_incidents');
        });
        
        Gate::define('edit_incidents', function ($user) {
            return $user->hasPermission('edit_incidents');
        });
        
        Gate::define('approve_incidents', function ($user) {
            return $user->hasPermission('approve_incidents');
        });
        
        Gate::define('delete_incidents', function ($user) {
            return $user->hasPermission('delete_incidents');
        });
        
        Gate::define('view_first_aid', function ($user) {
            return $user->hasPermission('view_first_aid');
        });
        
        Gate::define('create_first_aid', function ($user) {
            return $user->hasPermission('create_first_aid');
        });
        
        Gate::define('manage_first_aid', function ($user) {
            return $user->hasPermission('manage_first_aid');
        });
        
        Gate::define('view_users', function ($user) {
            return $user->hasPermission('view_users');
        });
        
        Gate::define('manage_users', function ($user) {
            return $user->hasPermission('manage_users');
        });
        
        Gate::define('approve_users', function ($user) {
            return $user->hasPermission('approve_users');
        });
        
        Gate::define('assign_roles', function ($user) {
            return $user->hasPermission('assign_roles');
        });
        
        Gate::define('view_reports', function ($user) {
            return $user->hasPermission('view_reports');
        });
        
        Gate::define('export_reports', function ($user) {
            return $user->hasPermission('export_reports');
        });
        
        Gate::define('view_dashboard', function ($user) {
            return $user->hasPermission('view_dashboard');
        });

        // RTW Management Gate
        Gate::define('manage-rtw', function ($user, $incident) {
            // HR users can manage RTW for their assigned cases
            if ($user->hasRole('hr')) {
                return $incident->routed_to_hr_user_id === $user->id || $user->hasPermission('manage_incidents');
            }
            return false;
        });
    }
}
