@extends('layouts.dashboard')

@section('page_title', 'Comparative Analysis · HSC')

@section('header_title', 'Comparative Analysis')
@section('header_subtitle', 'Year-over-year and period-over-period comparisons')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-code-branch text-brand"></i> COMPARATIVE ANALYSIS
    </h2>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-4">Month-over-Month Comparison</h3>
        <div class="space-y-4">
            <div>
                <p class="text-xs text-slate-600 mb-2">This Month</p>
                <p class="text-3xl font-bold text-slate-900">{{ $comparison['this_month'] }}</p>
                <p class="text-xs text-slate-500 mt-1">incidents reported</p>
            </div>
            <div>
                <p class="text-xs text-slate-600 mb-2">Last Month</p>
                <p class="text-3xl font-bold text-slate-900">{{ $comparison['last_month'] }}</p>
                <p class="text-xs text-slate-500 mt-1">incidents reported</p>
            </div>
            <div class="pt-4 border-t border-slate-200">
                @php
                    $monthChange = $comparison['this_month'] - $comparison['last_month'];
                    $monthPercent = $comparison['last_month'] > 0 ? round(($monthChange / $comparison['last_month']) * 100, 1) : 0;
                @endphp
                <p class="text-xs text-slate-600 mb-2">Change</p>
                <p class="text-2xl font-bold {{ $monthChange >= 0 ? 'text-red-600' : 'text-emerald-600' }}">
                    {{ $monthChange >= 0 ? '+' : '' }}{{ $monthChange }} ({{ $monthPercent }}%)
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-4">Quarter-over-Quarter Comparison</h3>
        <div class="space-y-4">
            <div>
                <p class="text-xs text-slate-600 mb-2">This Quarter</p>
                <p class="text-3xl font-bold text-slate-900">{{ $comparison['this_quarter'] }}</p>
                <p class="text-xs text-slate-500 mt-1">incidents reported</p>
            </div>
            <div>
                <p class="text-xs text-slate-600 mb-2">Last Quarter</p>
                <p class="text-3xl font-bold text-slate-900">{{ $comparison['last_quarter'] }}</p>
                <p class="text-xs text-slate-500 mt-1">incidents reported</p>
            </div>
            <div class="pt-4 border-t border-slate-200">
                @php
                    $quarterChange = $comparison['this_quarter'] - $comparison['last_quarter'];
                    $quarterPercent = $comparison['last_quarter'] > 0 ? round(($quarterChange / $comparison['last_quarter']) * 100, 1) : 0;
                @endphp
                <p class="text-xs text-slate-600 mb-2">Change</p>
                <p class="text-2xl font-bold {{ $quarterChange >= 0 ? 'text-red-600' : 'text-emerald-600' }}">
                    {{ $quarterChange >= 0 ? '+' : '' }}{{ $quarterChange }} ({{ $quarterPercent }}%)
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
