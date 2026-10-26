@extends('layouts.dashboard')

@section('page_title', 'User Activity Log · HSC')

@section('header_title', 'User Activity Log')
@section('header_subtitle', 'Complete audit trail of all system user actions')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-user-secret text-brand"></i> USER ACTIVITY LOG
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3">User</th>
                    <th class="text-left py-2 px-3">Action</th>
                    <th class="text-left py-2 px-3">Description</th>
                    <th class="text-left py-2 px-3">Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-semibold">{{ $log->user?->name ?? 'System' }}</td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs">{{ $log->action ?? 'N/A' }}</span>
                        </td>
                        <td class="py-2 px-3 text-slate-600">{{ $log->description ?? 'No description' }}</td>
                        <td class="py-2 px-3 text-slate-500">{{ $log->created_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-slate-500">No activity logs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($logs->hasPages())
<div class="mt-6">
    {{ $logs->links() }}
</div>
@endif
@endsection
