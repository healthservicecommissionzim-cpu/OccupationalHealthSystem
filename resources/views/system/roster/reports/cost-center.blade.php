@extends('layouts.app')

@section('title', 'Cost Center Report')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-chart-pie text-purple-600 mr-3"></i>
                    Cost Center Report
                </h1>
                <p class="text-slate-600 mt-2">Track financial liability across facilities for incidents involving rotational staff</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('system.roster.reports.workforce-distribution') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    <i class="fas fa-users mr-2"></i>Workforce Distribution
                </a>
                <a href="{{ route('dashboards.super_admin') }}" class="text-slate-600 hover:text-slate-700 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Date Range Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="{{ route('system.roster.reports.cost-center') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('system.roster.reports.cost-center') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Total Incidents</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ $summaryStatistics['total_incidents'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-medical text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Rotational Staff Incidents</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ $summaryStatistics['rotational_incidents'] }}</p>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ $summaryStatistics['total_incidents'] > 0 ? round(($summaryStatistics['rotational_incidents'] / $summaryStatistics['total_incidents']) * 100, 1) : 0 }}% of total
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Cost Centers Affected</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ count($incidentsByCostCenter) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hospital text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Button -->
    <div class="mb-6">
        <a href="{{ route('system.roster.reports.export.cost-center', request()->all()) }}" 
            class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
            <i class="fas fa-file-csv mr-2"></i>Export CSV
        </a>
    </div>

    <!-- Incidents by Cost Center -->
    @if(count($incidentsByCostCenter) > 0)
        @foreach($incidentsByCostCenter as $costCenterGroup)
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <!-- Cost Center Header -->
                <div class="bg-slate-100 border-b border-slate-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">
                                <i class="fas fa-building text-slate-600 mr-2"></i>
                                {{ $costCenterGroup['cost_center_facility_name'] }}
                            </h3>
                            <p class="text-sm text-slate-600 mt-1">
                                Cost Center Facility - {{ $costCenterGroup['incidents']->count() }} incident(s)
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                {{ $costCenterGroup['incidents']->where('is_rotational_staff', true)->count() }} Rotational
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Incidents Table -->
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Worker Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Primary Facility</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Incident Facility</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Cost Center</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Date</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Rotational</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($costCenterGroup['incidents'] as $incident)
                            <tr class="hover:bg-slate-50 transition-colors" x-data="{ expanded: false }">
                                <td class="px-6 py-4 text-sm font-medium text-blue-600">
                                    <a href="{{ route('incidents.show', $incident['incident_id']) }}" class="hover:underline">
                                        {{ $incident['reference_number'] }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $incident['worker_name'] }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $incident['worker_primary_facility_name'] }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $incident['incident_facility_name'] }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $incident['cost_center_facility_name'] }}</td>
                                <td class="px-6 py-4 text-center text-sm text-slate-700">
                                    {{ $incident['incident_date']->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm">
                                    @if($incident['is_rotational_staff'])
                                        <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                            <i class="fas fa-check-circle mr-1"></i>Yes
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-full">
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-sm">
                                    @if($incident['is_rotational_staff'] && isset($incident['roster_context']))
                                        <button @click="expanded = !expanded" class="text-blue-600 hover:text-blue-700 font-medium">
                                            <i class="fas" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                            <span x-text="expanded ? 'Hide' : 'Show'"></span>
                                        </button>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                            </tr>
                            
                            @if($incident['is_rotational_staff'] && isset($incident['roster_context']))
                                <tr x-show="expanded" x-collapse class="bg-slate-50">
                                    <td colspan="8" class="px-6 py-4">
                                        @include('system.roster.reports.partials.roster-context', ['context' => $incident['roster_context']])
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-chart-pie text-5xl text-slate-300 mb-4"></i>
            <p class="text-slate-600 text-lg">No incidents found for the selected date range</p>
            <p class="text-slate-500 text-sm mt-2">Try adjusting your filters or clear them to see all incidents</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
