@php
    use App\Services\TicketIndicatorService;
    
    $user = auth()->user();
    $indicatorService = app(TicketIndicatorService::class);
    $notificationCount = $indicatorService->getTotalNotificationCount($user);
@endphp

<div class="space-y-2">
    <!-- Create Support Ticket Button -->
    <a href="{{ route('support.create_ticket') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
        <i class="fa-solid fa-plus-circle w-5 text-center text-slate-700 text-sm"></i>
        <span>Create Ticket</span>
    </a>

    <!-- My Support Tickets with Badge -->
    <a href="{{ route('support.my_tickets') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px] relative">
        <i class="fa-solid fa-ticket w-5 text-center text-slate-700 text-sm"></i>
        <span>My Support Tickets</span>
        @if($notificationCount > 0)
            <span class="absolute top-1 right-2 inline-flex items-center justify-center w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full">
                {{ $notificationCount > 9 ? '9+' : $notificationCount }}
            </span>
        @endif
    </a>
</div>
