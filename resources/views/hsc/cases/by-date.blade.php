@extends('layouts.dashboard')

@section('page_title', 'Cases by Date · HSC')

@section('header_title', 'Cases by Date Range')
@section('header_subtitle', 'Filter incidents by date to identify patterns and trends')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-calendar text-brand"></i> CASES BY DATE RANGE
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-6">
    <form method="GET" class="flex gap-4 items-end">
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 px-3 py-2 border border-slate-200 rounded-lg text-sm">
        </div>
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 px-3 py-2 border border-slate-200 rounded-lg text-sm">
        </div>
        <button type="submit" class="px-4 py-2 bg-brand text-white rounded-lg text-sm font-semibold hover:bg-brand-dark">
            Filter
        </button>
    </form>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Period</p>
        <p class="text-sm text-slate-900 mt-1">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} → {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Total Cases</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $incidents->total() }}</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Average per Day</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $dateStats->count() > 0 ? round($incidents->total() / $dateStats->count(), 1) : 0 }}</p>
    </div>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">Incidents by Day</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Date</th>
                    <th class="text-center py-2 px-3 text-xs font-semibold text-slate-600">Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dateStats as $stat)
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-semibold text-slate-900">{{ \Carbon\Carbon::parse($stat->date)->format('l, M d Y') }}</td>
                        <td class="py-2 px-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-brand bg-opacity-10 text-brand font-bold rounded-full text-xs">{{ $stat->count }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="py-8 text-center text-slate-500 text-sm">No data for selected date range</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mt-6">
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">Incidents Details</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Reference</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Employee</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Facility</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Date</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Status</th>
                    <th class="text-center py-2 px-3 text-xs font-semibold text-slate-600">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidents as $incident)
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-semibold text-brand text-xs">{{ $incident->reference_number }}</td>
                        <td class="py-2 px-3 text-xs">{{ $incident->first_name }} {{ $incident->surname }}</td>
                        <td class="py-2 px-3 text-xs">{{ $incident->facility?->name ?? 'N/A' }}</td>
                        <td class="py-2 px-3 text-xs text-slate-500">{{ $incident->created_at->format('M d, Y') }}</td>
                        <td class="py-2 px-3 text-xs">
                            <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs">{{ str_replace('_', ' ', $incident->status) }}</span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <a href="{{ route('incidents.hsc_view', $incident) }}" class="inline-flex items-center gap-1 px-2 py-1 bg-brand text-white rounded text-xs hover:bg-brand-dark">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500 text-sm">No incidents found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($incidents->hasPages())
<div class="mt-6">
    {{ $incidents->links() }}
</div>
@endif
@endsection
