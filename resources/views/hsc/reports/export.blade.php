@extends('layouts.dashboard')

@section('page_title', 'Export Data · HSC')

@section('header_title', 'Export Data')
@section('header_subtitle', 'Bulk export incident data for external analysis')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-download text-brand"></i> EXPORT DATA
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
    <form class="space-y-5">
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase">Export Format</label>
            <div class="mt-2 grid grid-cols-2 gap-2">
                <label class="flex items-center p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50">
                    <input type="radio" name="format" value="csv" checked class="w-4 h-4">
                    <span class="ml-2 text-sm font-medium">CSV File</span>
                </label>
                <label class="flex items-center p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50">
                    <input type="radio" name="format" value="excel" class="w-4 h-4">
                    <span class="ml-2 text-sm font-medium">Excel Spreadsheet</span>
                </label>
                <label class="flex items-center p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50">
                    <input type="radio" name="format" value="pdf" class="w-4 h-4">
                    <span class="ml-2 text-sm font-medium">PDF Report</span>
                </label>
                <label class="flex items-center p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50">
                    <input type="radio" name="format" value="json" class="w-4 h-4">
                    <span class="ml-2 text-sm font-medium">JSON Data</span>
                </label>
            </div>
        </div>

        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase">Select Fields to Export</label>
            <div class="mt-2 space-y-2">
                <label class="flex items-center">
                    <input type="checkbox" class="w-4 h-4" checked>
                    <span class="ml-2 text-sm">Reference Number</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="w-4 h-4" checked>
                    <span class="ml-2 text-sm">Employee Details</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="w-4 h-4" checked>
                    <span class="ml-2 text-sm">Facility & Department</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="w-4 h-4" checked>
                    <span class="ml-2 text-sm">Incident Type & Date</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="w-4 h-4" checked>
                    <span class="ml-2 text-sm">Status & Dates</span>
                </label>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-brand text-white rounded-lg text-sm font-semibold hover:bg-brand-dark">
                <i class="fa-solid fa-download"></i> Export Data
            </button>
        </div>
    </form>
</div>
@endsection
