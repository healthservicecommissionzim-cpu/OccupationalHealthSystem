@extends('layouts.dashboard')

@section('page_title', 'Case Audits · HSC')

@section('header_title', 'Case Audits')
@section('header_subtitle', 'Random sample audit of case completeness and compliance')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
            <i class="fa-solid fa-magnifying-glass-chart text-brand"></i> CASE AUDITS
        </h2>
        <form method="GET" class="flex gap-2">
            <select name="sample_size" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                <option value="20" {{ request('sample_size', 20) == 20 ? 'selected' : '' }}>20 Cases</option>
                <option value="50" {{ request('sample_size') == 50 ? 'selected' : '' }}>50 Cases</option>
                <option value="100" {{ request('sample_size') == 100 ? 'selected' : '' }}>100 Cases</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-brand text-white rounded-lg text-sm font-semibold hover:bg-brand-dark">Load Sample</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">Random Sample: {{ count($auditCases) }} Cases</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3">Reference</th>
                    <th class="text-center py-2 px-3">Supervisor Verified</th>
                    <th class="text-center py-2 px-3">HOD Report</th>
                    <th class="text-center py-2 px-3">First Aid</th>
                    <th class="text-center py-2 px-3">Documents</th>
                    <th class="text-center py-2 px-3">Witnesses</th>
                    <th class="text-center py-2 px-3">Completeness</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auditCases as $incident)
                    @php
                        $checks = $completenessChecklist[$incident->id];
                        $complete = collect($checks)->filter()->count();
                        $total = count($checks);
                    @endphp
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-bold text-brand">{{ $incident->reference_number }}</td>
                        <td class="py-2 px-3 text-center">
                            <span class="text-{{ $checks['supervisor_verified'] ? 'emerald-600' : 'slate-400' }}">
                                <i class="fa-solid fa-{{ $checks['supervisor_verified'] ? 'check-circle' : 'circle' }}"></i>
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="text-{{ $checks['hod_report'] ? 'emerald-600' : 'slate-400' }}">
                                <i class="fa-solid fa-{{ $checks['hod_report'] ? 'check-circle' : 'circle' }}"></i>
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="text-{{ $checks['first_aid_record'] ? 'emerald-600' : 'slate-400' }}">
                                <i class="fa-solid fa-{{ $checks['first_aid_record'] ? 'check-circle' : 'circle' }}"></i>
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="text-{{ $checks['documents_attached'] ? 'emerald-600' : 'slate-400' }}">
                                <i class="fa-solid fa-{{ $checks['documents_attached'] ? 'check-circle' : 'circle' }}"></i>
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="text-{{ $checks['witness_statements'] ? 'emerald-600' : 'slate-400' }}">
                                <i class="fa-solid fa-{{ $checks['witness_statements'] ? 'check-circle' : 'circle' }}"></i>
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-bold {{ $complete == $total ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $complete }}/{{ $total }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
