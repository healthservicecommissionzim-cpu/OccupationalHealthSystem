@extends('layouts.dashboard')

@section('page_title', 'Statistical Summary · HSC')

@section('header_title', 'Statistical Summary')
@section('header_subtitle', 'Key performance indicators and system statistics')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-chart-bar text-brand"></i> STATISTICAL SUMMARY
    </h2>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Total Cases</p>
        <p class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['total_cases'] }}</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Active Cases</p>
        <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['active_cases'] }}</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Resolved Cases</p>
        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['resolved_cases'] }}</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Rejected Cases</p>
        <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['rejected_cases'] }}</p>
    </div>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-slate-900">Cases by Incident Type</h3>
        </div>
        <div class="p-4">
            <div class="space-y-3">
                @forelse($stats['total_by_type'] as $item)
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-600">{{ $item->incidentType?->name ?? 'Unknown' }}</span>
                        <span class="px-2 py-1 bg-brand bg-opacity-10 text-brand rounded text-xs font-bold">{{ $item->count }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500">No data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-slate-900">Top 10 Facilities</h3>
        </div>
        <div class="p-4">
            <div class="space-y-2">
                @forelse($stats['total_by_facility'] as $facility)
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-600 truncate">{{ $facility->name }}</span>
                        <span class="px-2 py-1 bg-brand bg-opacity-10 text-brand rounded text-xs font-bold">{{ $facility->incidents_count }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500">No data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
