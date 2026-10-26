@extends('layouts.dashboard')

@section('page_title', 'Search Cases · HSC')

@section('header_title', 'Search Cases')
@section('header_subtitle', 'Find specific incidents using multiple search criteria')

@section('sidebar_content')
@include('hsc.sidebar')
@endsection

@section('dashboard_content')
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-magnifying-glass text-brand"></i> SEARCH CASES
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 mb-6">
    <form method="GET" class="space-y-4">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase">Search Query</label>
                <input type="text" name="query" placeholder="Reference, Name, or ID" 
                    value="{{ request('query') }}" 
                    class="mt-2 w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:border-brand focus:ring-1 focus:ring-brand">
                <p class="text-xs text-slate-500 mt-1">Search by case reference, employee name, or ID number</p>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase">Incident Type</label>
                <select name="incident_type" class="mt-2 w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:border-brand focus:ring-1 focus:ring-brand">
                    <option value="">All Types</option>
                    <option value="1" {{ request('incident_type') === '1' ? 'selected' : '' }}>Needle Stick Injury</option>
                    <option value="2" {{ request('incident_type') === '2' ? 'selected' : '' }}>Occupational Disease</option>
                    <option value="3" {{ request('incident_type') === '3' ? 'selected' : '' }}>Hazard Exposure</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-600 uppercase">Facility</label>
                <select name="facility" class="mt-2 w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:border-brand focus:ring-1 focus:ring-brand">
                    <option value="">All Facilities</option>
                </select>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-brand text-white rounded-lg text-sm font-semibold hover:bg-brand-dark">
                <i class="fa-solid fa-search"></i> Search
            </button>
            <a href="{{ route('hsc.cases.search') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold hover:bg-slate-300">
                Clear
            </a>
        </div>
    </form>
</div>

@if(request('query') || request('incident_type') || request('facility'))
    <div class="mb-4">
        <p class="text-sm text-slate-600">
            Found <span class="font-bold text-brand">{{ $incidents->total() }}</span> result(s)
        </p>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Reference</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Employee</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Facility</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Type</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Date</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Status</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incidents as $incident)
                        <tr class="border-b border-slate-200 hover:bg-slate-50">
                            <td class="py-3 px-4 font-semibold text-brand text-xs">{{ $incident->reference_number }}</td>
                            <td class="py-3 px-4 text-xs">{{ $incident->first_name }} {{ $incident->surname }}</td>
                            <td class="py-3 px-4 text-xs">{{ $incident->facility?->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-xs">{{ $incident->incidentType?->name ?? 'Unknown' }}</td>
                            <td class="py-3 px-4 text-xs text-slate-500">{{ $incident->created_at->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-xs">
                                <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs">{{ str_replace('_', ' ', $incident->status) }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('incidents.hsc_view', $incident) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-brand text-white rounded text-xs hover:bg-brand-dark">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500 text-sm">
                                <i class="fa-solid fa-search text-2xl text-slate-300 mb-2"></i>
                                <p>No cases match your search criteria</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($incidents->hasPages())
    <div class="mt-6">
        {{ $incidents->links() }}
    </div>
    @endif
@else
    <div class="bg-slate-50 border border-slate-200 rounded-lg p-8 text-center">
        <i class="fa-solid fa-search text-4xl text-slate-300 mb-4"></i>
        <p class="text-slate-600 mb-2">Enter search criteria to find cases</p>
        <p class="text-xs text-slate-500">Search by case reference number, employee name, or ID</p>
    </div>
@endif
@endsection
