@extends('layouts.dashboard')

@section('page_title', 'Facility Performance · HSC')

@section('header_title', 'Facility Performance')
@section('header_subtitle', 'Monitor incident resolution rates and facility efficiency metrics')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-building text-brand"></i> FACILITY PERFORMANCE METRICS
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">90-Day Performance Summary</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Facility Name</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600">Incidents (90d)</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600">Resolved</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600">Pending</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600">Resolution Rate</th>
                </tr>
            </thead>
            <tbody>
                @forelse($facilities as $item)
                    @php
                        $total = $item['total_incidents_90days'];
                        $resolved = $item['resolved'];
                        $pending = $item['pending'];
                        $rate = $item['resolution_rate'];
                    @endphp
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-3 px-4 font-semibold text-slate-900">{{ $item['facility']->name }}</td>
                        <td class="py-3 px-4 text-center font-bold text-slate-900">{{ $total }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">{{ $resolved }}</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 {{ $pending > 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700' }} rounded-full text-xs font-bold">{{ $pending }}</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center gap-2 justify-center">
                                <div class="w-16 bg-slate-200 rounded-full h-2">
                                    <div class="bg-brand h-2 rounded-full transition-all" style="width: {{ $rate }}%"></div>
                                </div>
                                <span class="text-xs font-bold w-10">{{ $rate }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">No facility data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
