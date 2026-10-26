@extends('layouts.dashboard')

@section('page_title', 'Document Verification · HSC')

@section('header_title', 'Document Verification')
@section('header_subtitle', 'Verify document completeness and upload status')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-file-check text-brand"></i> DOCUMENT VERIFICATION
    </h2>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Total Documents</p>
        <p class="text-3xl font-bold text-slate-900 mt-1">{{ $documentStats['total_documents'] }}</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Verified</p>
        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $documentStats['verified_documents'] }}</p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Pending</p>
        <p class="text-3xl font-bold text-amber-600 mt-1">{{ $documentStats['pending_documents'] }}</p>
    </div>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">Cases with Attachments</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3">Case Reference</th>
                    <th class="text-center py-2 px-3">Document Count</th>
                    <th class="text-center py-2 px-3">Verified</th>
                    <th class="text-center py-2 px-3">Pending</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidentsWithAttachments as $incident)
                    @php
                        $verified = $incident->attachments->where('upload_status', 'VERIFIED')->count();
                        $pending = $incident->attachments->where('upload_status', 'PENDING')->count();
                    @endphp
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-bold text-brand">{{ $incident->reference_number }}</td>
                        <td class="py-2 px-3 text-center font-bold">{{ $incident->attachments->count() }}</td>
                        <td class="py-2 px-3 text-center text-emerald-600 font-bold">{{ $verified }}</td>
                        <td class="py-2 px-3 text-center text-amber-600 font-bold">{{ $pending }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-slate-500">No documents found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
