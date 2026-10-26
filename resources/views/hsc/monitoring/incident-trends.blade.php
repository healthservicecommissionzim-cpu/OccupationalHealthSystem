@extends('layouts.dashboard')

@section('page_title', 'Incident Trends · HSC')

@section('header_title', 'Incident Trends')
@section('header_subtitle', 'Analyze patterns and trends in occupational health incidents')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-chart-area text-brand"></i> INCIDENT TRENDS ANALYSIS
    </h2>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-4">Incidents by Type (90 Days)</h3>
        <div class="space-y-3">
            @foreach($trendsByType as $item)
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-600">{{ $item->incidentType?->name ?? 'Unknown' }}</span>
                    <div class="flex-1 mx-3 bg-slate-200 rounded-full h-2">
                        <div class="bg-brand h-2 rounded-full" style="width: {{ ($item->count / $trendsByType->sum('count')) * 100 }}%"></div>
                    </div>
                    <span class="text-xs font-bold text-slate-900">{{ $item->count }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-4">30-Day Daily Trend</h3>
        <p class="text-xs text-slate-500">Trend data visualization will appear here</p>
        <div id="trendChart" style="height: 300px;" class="mt-4"></div>
    </div>
</div>
@endsection
