@extends('layouts.dashboard')

@section('page_title', 'Workflow Health · HSC')

@section('header_title', 'Workflow Health')
@section('header_subtitle', 'Monitor case processing efficiency through workflow stages')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-heartbeat text-brand"></i> WORKFLOW HEALTH MONITORING
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
    <h3 class="text-sm font-semibold text-slate-900 mb-4">Cases by Workflow Stage</h3>
    <div class="space-y-4">
        @php
            $total = collect($stageCounts)->sum();
        @endphp
        @foreach($stageCounts as $stage => $count)
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-slate-600 mb-1">{{ str_replace('_', ' ', $stage) }}</p>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-brand h-2 rounded-full transition-all" style="width: {{ $total > 0 ? ($count / $total) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <span class="ml-3 px-3 py-1 bg-brand bg-opacity-10 text-brand font-bold rounded-full text-sm min-w-[3rem] text-center">{{ $count }}</span>
            </div>
        @endforeach
    </div>
    <div class="mt-6 pt-4 border-t border-slate-200">
        <p class="text-xs text-slate-600">
            <span class="font-bold text-brand">{{ $total }}</span> total cases in workflow
        </p>
    </div>
</div>
@endsection
