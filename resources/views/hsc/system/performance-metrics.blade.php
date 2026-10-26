@extends('layouts.dashboard')

@section('page_title', 'Performance Metrics · HSC')

@section('header_title', 'Performance Metrics')
@section('header_subtitle', 'System SLA compliance and performance indicators')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-gauge text-brand"></i> PERFORMANCE METRICS
    </h2>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Average Processing Times</h3>
        <div class="space-y-4">
            <div>
                <p class="text-xs text-slate-600 mb-2">Avg. Supervisor Verification Time</p>
                <p class="text-3xl font-bold text-slate-900">{{ round($metrics['avg_supervisor_verification_time'], 1) }}</p>
                <p class="text-xs text-slate-500">days</p>
            </div>
            <div class="pt-4 border-t border-slate-200">
                <p class="text-xs text-slate-600 mb-2">Avg. Time to Resolution</p>
                <p class="text-3xl font-bold text-slate-900">{{ round($metrics['avg_time_to_resolution'], 1) }}</p>
                <p class="text-xs text-slate-500">days (Target: 30 days)</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">System Health Indicators</h3>
        <div class="space-y-4">
            <div>
                <p class="text-xs text-slate-600 mb-2">Escalations This Month</p>
                <p class="text-3xl font-bold text-slate-900">{{ $metrics['total_escalations_this_month'] }}</p>
                <p class="text-xs text-slate-500">cases escalated to clinical review</p>
            </div>
            <div class="pt-4 border-t border-slate-200">
                <p class="text-xs text-slate-600 mb-2">Overall Compliance Rate</p>
                <p class="text-3xl font-bold text-emerald-600">{{ $metrics['compliance_percentage'] }}%</p>
                <p class="text-xs text-slate-500">of cases meet requirements</p>
            </div>
        </div>
    </div>
</div>
@endsection
