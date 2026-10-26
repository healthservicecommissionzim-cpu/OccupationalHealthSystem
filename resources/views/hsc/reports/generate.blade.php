@extends('layouts.dashboard')

@section('page_title', 'Generate Reports · HSC')

@section('header_title', 'Generate Reports')
@section('header_subtitle', 'Create custom reports filtered by date, facility, incident type')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-file-export text-brand"></i> GENERATE CUSTOM REPORTS
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
    <form class="space-y-5">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase">Start Date</label>
                <input type="date" class="mt-2 w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase">End Date</label>
                <input type="date" class="mt-2 w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase">Incident Type</label>
                <select class="mt-2 w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    <option>All Types</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase">Facility</label>
                <select class="mt-2 w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    <option>All Facilities</option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase">Report Type</label>
            <div class="mt-2 space-y-2">
                <label class="flex items-center">
                    <input type="radio" name="report_type" value="summary" checked class="w-4 h-4">
                    <span class="ml-2 text-sm">Summary Report</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="report_type" value="detailed" class="w-4 h-4">
                    <span class="ml-2 text-sm">Detailed Report</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="report_type" value="compliance" class="w-4 h-4">
                    <span class="ml-2 text-sm">Compliance Report</span>
                </label>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-brand text-white rounded-lg text-sm font-semibold hover:bg-brand-dark">
                <i class="fa-solid fa-download"></i> Generate Report
            </button>
            <button type="button" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold hover:bg-slate-300">
                Reset
            </button>
        </div>
    </form>
</div>
@endsection
