@extends('layouts.dashboard')

@section('page_title', 'Escalation Tracking · HSC')

@section('header_title', 'Escalation Tracking')
@section('header_subtitle', 'Follow critical cases escalated to clinical and provincial review')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-arrow-up-right-dots text-red-600"></i> ESCALATION TRACKING
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Reference</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Employee</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Facility</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600">Escalation Status</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Escalated Date</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600">Assigned HR User</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($escalatedCases as $incident)
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-3 px-4 font-semibold text-brand">{{ $incident->reference_number }}</td>
                        <td class="py-3 px-4 text-sm">{{ $incident->first_name }} {{ $incident->surname }}</td>
                        <td class="py-3 px-4 text-sm">{{ $incident->facility?->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                {{ str_replace('_', ' ', $incident->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-slate-500">{{ $incident->escalated_to_dmo_at?->format('M d, Y') ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-center text-sm">{{ $incident->assignedHRUser?->name ?? 'Unassigned' }}</td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('incidents.hsc_view', $incident) }}" class="text-brand hover:underline text-xs font-semibold">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-500">No escalated cases</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($escalatedCases->hasPages())
<div class="mt-6">
    {{ $escalatedCases->links() }}
</div>
@endif
@endsection
