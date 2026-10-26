@extends('layouts.dashboard')

@section('page_title', 'All Cases · HSC')

@section('header_title', 'All Cases')
@section('header_subtitle', 'Complete view of all incidents in the system')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
            <i class="fa-solid fa-list-check text-brand"></i> ALL CASES
        </h2>
        <div class="flex gap-2">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Search by reference, name, ID..." 
                    class="px-3 py-2 border border-slate-200 rounded-lg text-[12px]" value="{{ request('search') }}">
                <select name="status" class="px-3 py-2 border border-slate-200 rounded-lg text-[12px]">
                    <option value="">All Statuses</option>
                    <option value="PENDING_SUPERVISOR_VERIFICATION" {{ request('status') === 'PENDING_SUPERVISOR_VERIFICATION' ? 'selected' : '' }}>Pending Verification</option>
                    <option value="ESCALATED_TO_HR" {{ request('status') === 'ESCALATED_TO_HR' ? 'selected' : '' }}>HR Review</option>
                    <option value="RESOLVED" {{ request('status') === 'RESOLVED' ? 'selected' : '' }}>Resolved</option>
                    <option value="REJECTED" {{ request('status') === 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-brand text-white rounded-lg text-[12px] font-semibold hover:bg-brand-dark">
                    Filter
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Total Cases</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $statusCounts->sum() ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Pending Review</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $statusCounts->get('PENDING_SUPERVISOR_VERIFICATION', 0) }}</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">In HR Review</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $statusCounts->get('ESCALATED_TO_HR', 0) }}</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Resolved</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $statusCounts->get('RESOLVED', 0) + $statusCounts->get('CLOSED', 0) }}</p>
    </div>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50">
                <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Reference</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Employee</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Facility</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Type</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Date</th>
                <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Status</th>
                <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incidents as $incident)
                <tr class="border-b border-slate-200 hover:bg-slate-50">
                    <td class="py-3 px-4 text-xs font-semibold text-brand">{{ $incident->reference_number }}</td>
                    <td class="py-3 px-4 text-xs text-slate-700">{{ $incident->first_name }} {{ $incident->surname }}</td>
                    <td class="py-3 px-4 text-xs text-slate-700">{{ $incident->facility?->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4 text-xs text-slate-700">{{ $incident->incidentType?->name ?? 'Unknown' }}</td>
                    <td class="py-3 px-4 text-xs text-slate-500">{{ $incident->created_at->format('M d, Y') }}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                            {{ str_replace('_', ' ', $incident->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <a href="{{ route('incidents.hsc_view', $incident) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-brand text-white rounded text-xs font-semibold hover:bg-brand-dark">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-slate-500 text-sm">No cases found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($incidents->hasPages())
<div class="mt-6">
    {{ $incidents->links() }}
</div>
@endif
@endsection
