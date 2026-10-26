@extends('layouts.dashboard')

@section('page_title', 'Timeline Compliance · HSC')

@section('header_title', 'Timeline Compliance')
@section('header_subtitle', 'Verify cases are processed within SLA timeframes')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-hourglass-end text-brand"></i> TIMELINE COMPLIANCE
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">SLA Compliance (30-Day Resolution Target)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3">Reference</th>
                    <th class="text-left py-2 px-3">Supervisor Verification (Days)</th>
                    <th class="text-left py-2 px-3">Total Time to Resolution (Days)</th>
                    <th class="text-center py-2 px-3">Within SLA?</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidents as $item)
                    @php
                        $within_sla = $item['within_sla'];
                    @endphp
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-bold text-brand">{{ $item['incident']->reference_number }}</td>
                        <td class="py-2 px-3">
                            <span class="text-{{ $item['supervisor_verification_time'] && $item['supervisor_verification_time'] <= 7 ? 'emerald-600' : 'amber-600' }} font-bold">
                                {{ $item['supervisor_verification_time'] ?? 'Pending' }}
                            </span>
                        </td>
                        <td class="py-2 px-3">
                            <span class="text-{{ $item['total_time_to_resolution'] && $item['total_time_to_resolution'] <= 30 ? 'emerald-600' : 'amber-600' }} font-bold">
                                {{ $item['total_time_to_resolution'] ?? 'Pending' }}
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-bold {{ $within_sla ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                <i class="fa-solid fa-{{ $within_sla ? 'check-circle' : 'times-circle' }} mr-1"></i> {{ $within_sla ? 'Yes' : 'No' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-slate-500">No timeline data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
