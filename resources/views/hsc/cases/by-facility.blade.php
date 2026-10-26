@extends('layouts.dashboard')

@section('page_title', 'Cases by Facility · HSC')

@section('header_title', 'Cases by Facility')
@section('header_subtitle', 'View incident distribution across all health facilities')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-hospital text-brand"></i> CASES BY FACILITY
    </h2>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    @foreach($facilityStats as $facility)
        <div class="bg-white rounded-lg border border-slate-200 p-4 hover:shadow-md transition-all">
            <p class="text-xs text-slate-600 font-semibold truncate">{{ $facility->name }}</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $facility->incidents_count }}</p>
            <p class="text-xs text-slate-500 mt-1">incidents</p>
        </div>
    @endforeach
</div>

<div class="space-y-4">
    @foreach($facilities as $facility)
        @php
            $incidents = $facility->incidents;
        @endphp
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">{{ $facility->name }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $facility->district?->name ?? 'N/A' }} District</p>
                </div>
                <span class="px-3 py-1 bg-brand bg-opacity-10 text-brand text-xs font-bold rounded-full">{{ $incidents->count() }} cases</span>
            </div>
            
            @if($incidents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50">
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Reference</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Employee</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Type</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Date</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Status</th>
                                <th class="text-center py-2 px-3 text-xs font-semibold text-slate-600">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incidents->take(5) as $incident)
                                <tr class="border-b border-slate-200 hover:bg-slate-50">
                                    <td class="py-2 px-3 font-semibold text-brand text-xs">{{ $incident->reference_number }}</td>
                                    <td class="py-2 px-3 text-xs">{{ $incident->first_name }} {{ $incident->surname }}</td>
                                    <td class="py-2 px-3 text-xs">{{ $incident->incidentType?->name ?? 'Unknown' }}</td>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($incidents->count() > 5)
                    <div class="px-4 py-2 border-t border-slate-200 bg-slate-50 text-center text-xs text-slate-500">
                        Showing 5 of {{ $incidents->count() }} cases
                    </div>
                @endif
            @else
                <div class="py-8 text-center text-slate-500 text-sm">
                    <p>No cases for this facility</p>
                </div>
            @endif
        </div>
    @endforeach
</div>
@endsection
