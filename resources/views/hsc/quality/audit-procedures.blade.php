@extends('layouts.dashboard')

@section('page_title', 'Procedure Adherence · HSC')

@section('header_title', 'Procedure Adherence')
@section('header_subtitle', 'Verify protocol and procedure compliance across all cases')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-gavel text-brand"></i> PROCEDURE ADHERENCE
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">Protocol Compliance Checklist</h3>
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
                    <th class="text-center py-2 px-3">Compliance Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($procedureAdherence as $id => $audit)
                    @php
                        $checks = [
                            'has_supervisor_verification' => $audit['has_supervisor_verification'],
                            'has_hod_report' => $audit['has_hod_report'],
                            'has_first_aid_record' => $audit['has_first_aid_record'],
                            'has_required_documents' => $audit['has_required_documents'],
                            'has_witness_statements' => $audit['has_witness_statements'],
                        ];
                        $complete = collect($checks)->filter()->count();
                    @endphp
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-bold text-brand">{{ $audit['incident']->reference_number }}</td>
                        <td class="py-2 px-3 text-center">
                            <span class="text-{{ $checks['has_supervisor_verification'] ? 'emerald-600' : 'red-600' }}">
                                <i class="fa-solid fa-{{ $checks['has_supervisor_verification'] ? 'check-circle' : 'times-circle' }}"></i>
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="text-{{ $checks['has_hod_report'] ? 'emerald-600' : 'red-600' }}">
                                <i class="fa-solid fa-{{ $checks['has_hod_report'] ? 'check-circle' : 'times-circle' }}"></i>
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="text-{{ $checks['has_first_aid_record'] ? 'emerald-600' : 'red-600' }}">
                                <i class="fa-solid fa-{{ $checks['has_first_aid_record'] ? 'check-circle' : 'times-circle' }}"></i>
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="text-{{ $checks['has_required_documents'] ? 'emerald-600' : 'red-600' }}">
                                <i class="fa-solid fa-{{ $checks['has_required_documents'] ? 'check-circle' : 'times-circle' }}"></i>
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="text-{{ $checks['has_witness_statements'] ? 'emerald-600' : 'red-600' }}">
                                <i class="fa-solid fa-{{ $checks['has_witness_statements'] ? 'check-circle' : 'times-circle' }}"></i>
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-bold {{ $audit['compliance_status'] === 'COMPLIANT' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ str_replace('_', ' ', $audit['compliance_status']) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-center text-slate-500">No audit data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
