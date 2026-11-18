@extends('layouts.dashboard')

@section('page_title', 'Repeat Incident Patterns · HSC')

@section('header_title', 'Repeat Incident Patterns')
@section('header_subtitle', 'Incidents from facilities with 2+ occurrences in the last 180 days')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
            <i class="fa-solid fa-repeat text-orange-600"></i> REPEAT INCIDENT PATTERNS
        </h2>
        <div class="flex gap-2">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Search by reference, name, ID..." 
                    class="px-3 py-2 border border-slate-200 rounded-lg text-[12px]" value="{{ request('search') }}">
                <select name="facility" class="px-3 py-2 border border-slate-200 rounded-lg text-[12px]">
                    <option value="">All Facilities</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ request('facility') == $facility->id ? 'selected' : '' }}>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </select>
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

<!-- Alert Banner -->
@if($facilitiesWithRepeats > 0)
<div class="mb-6 p-4 bg-orange-50 border-l-4 border-orange-500 rounded-lg">
    <div class="flex items-start gap-3">
        <i class="fa-solid fa-triangle-exclamation text-orange-600 text-lg mt-0.5"></i>
        <div>
            <h3 class="text-sm font-bold text-orange-900">Action Required: {{ $facilitiesWithRepeats }} Facilities with Repeat Incidents</h3>
            <p class="text-xs text-orange-800 mt-1">
                These incidents are from facilities that have experienced 2 or more incidents in the last 6 months. 
                Immediate safety audits and corrective actions recommended.
            </p>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Total Incidents</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $incidents->total() }}</p>
        <p class="text-[10px] text-slate-500 mt-1">From repeat facilities</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Pending Review</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $statusCounts->get('PENDING_SUPERVISOR_VERIFICATION', 0) }}</p>
        <p class="text-[10px] text-slate-500 mt-1">Awaiting verification</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">In HR Review</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $statusCounts->get('ESCALATED_TO_HR', 0) }}</p>
        <p class="text-[10px] text-slate-500 mt-1">Under investigation</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Facilities Affected</p>
        <p class="text-2xl font-bold text-orange-600 mt-1">{{ $facilitiesWithRepeats }}</p>
        <p class="text-[10px] text-slate-500 mt-1">With 2+ incidents</p>
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
                    <td class="py-3 px-4 text-xs text-slate-700">
                        <div>{{ $incident->facility?->name ?? 'N/A' }}</div>
                        <div class="text-[10px] text-orange-600 font-semibold mt-0.5">
                            <i class="fa-solid fa-repeat"></i> Repeat Facility
                        </div>
                    </td>
                    <td class="py-3 px-4 text-xs text-slate-700">{{ $incident->incidentType?->name ?? 'Unknown' }}</td>
                    <td class="py-3 px-4 text-xs text-slate-500">{{ $incident->created_at->format('M d, Y') }}</td>
                    <td class="py-3 px-4 text-center">
                        @php
                            $statusColors = [
                                'PENDING_SUPERVISOR_VERIFICATION' => 'bg-amber-100 text-amber-700',
                                'VERIFIED_SUPERVISOR' => 'bg-blue-100 text-blue-700',
                                'ESCALATED_TO_HR' => 'bg-purple-100 text-purple-700',
                                'RESOLVED' => 'bg-emerald-100 text-emerald-700',
                                'CLOSED' => 'bg-slate-100 text-slate-700',
                                'REJECTED' => 'bg-red-100 text-red-700',
                            ];
                            $colorClass = $statusColors[$incident->status] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $colorClass }}">
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
                    <td colspan="7" class="py-8 text-center text-slate-500 text-sm">
                        @if(request('search') || request('facility') || request('status'))
                            <i class="fa-solid fa-search text-3xl text-slate-300 mb-2 block"></i>
                            No incidents found matching your filters
                        @else
                            <i class="fa-solid fa-check-circle text-3xl text-emerald-500 mb-2 block"></i>
                            <div class="font-semibold text-emerald-700">No Repeat Incidents Found</div>
                            <div class="text-xs text-slate-500 mt-1">All facilities have fewer than 2 incidents in the last 180 days</div>
                        @endif
                    </td>
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
