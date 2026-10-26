@extends('layouts.dashboard')

@section('page_title', 'Notification History · HSC')

@section('header_title', 'Notification History')
@section('header_subtitle', 'Complete audit trail of all system notifications sent')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-bell text-brand"></i> NOTIFICATION HISTORY
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3">Case Reference</th>
                    <th class="text-left py-2 px-3">Notification Type</th>
                    <th class="text-left py-2 px-3">Channel</th>
                    <th class="text-left py-2 px-3">Recipient</th>
                    <th class="text-center py-2 px-3">Status</th>
                    <th class="text-left py-2 px-3">Sent At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-bold text-brand">{{ $notification->incident?->reference_number ?? 'N/A' }}</td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs">{{ str_replace('_', ' ', $notification->notification_type) }}</span>
                        </td>
                        <td class="py-2 px-3 text-slate-600">{{ $notification->notification_channel ?? 'N/A' }}</td>
                        <td class="py-2 px-3">{{ $notification->recipientUser?->name ?? 'System' }}</td>
                        <td class="py-2 px-3 text-center">
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-bold {{ $notification->status === 'SENT' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ $notification->status }}
                            </span>
                        </td>
                        <td class="py-2 px-3 text-slate-500">{{ $notification->sent_at?->format('M d, Y H:i') ?? $notification->created_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-slate-500">No notification history</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($notifications->hasPages())
<div class="mt-6">
    {{ $notifications->links() }}
</div>
@endif
@endsection
