@extends('layouts.app')

@section('title', 'Workforce Distribution Report')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-users text-blue-600 mr-3"></i>
                    Workforce Distribution Report
                </h1>
                <p class="text-slate-600 mt-2">View staff distribution across facilities including permanent and rotational staff</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('system.roster.reports.cost-center') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    <i class="fas fa-chart-pie mr-2"></i>Cost Center Report
                </a>
                <a href="{{ route('system.roster.reports.rotation-movements') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    <i class="fas fa-exchange-alt mr-2"></i>Rotation Movements
                </a>
                <a href="{{ route('dashboards.super_admin') }}" class="text-slate-600 hover:text-slate-700 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Date Selector -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="{{ route('system.roster.reports.workforce-distribution') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Select Date</label>
                    <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-slate-500 mt-1">Shows staff counts for the selected date</p>
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Update Report
                    </button>
                    <a href="{{ route('system.roster.reports.workforce-distribution') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        Today
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
                    <p class="text-sm font-medium text-slate-600">Total Staff</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">
                        {{ $workforceDistribution->sum('total_staff') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Permanent Staff</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">
                        {{ $workforceDistribution->sum('permanent_staff') }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ $workforceDistribution->sum('total_staff') > 0 ? round(($workforceDistribution->sum('permanent_staff') / $workforceDistribution->sum('total_staff')) * 100, 1) : 0 }}% of total
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Rotational Staff</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">
                        {{ $workforceDistribution->sum('rotational_staff') }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ $workforceDistribution->sum('total_staff') > 0 ? round(($workforceDistribution->sum('rotational_staff') / $workforceDistribution->sum('total_staff')) * 100, 1) : 0 }}% of total
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Workforce Distribution Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-slate-100 border-b border-slate-200 px-6 py-4">
            <h3 class="text-lg font-bold text-slate-800">
                <i class="fas fa-hospital text-slate-600 mr-2"></i>
                Workforce by Facility
            </h3>
            <p class="text-sm text-slate-600 mt-1">Staff counts as of {{ $date->format('F d, Y') }}</p>
        </div>

        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Facility</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Permanent Staff</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Rotational Staff</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Total Staff</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Distribution</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($workforceDistribution as $facility)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-800">
                            <i class="fas fa-building text-slate-500 mr-2"></i>
                            {{ $facility->facility_name }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 font-semibold rounded">
                                {{ $facility->permanent_staff }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 font-semibold rounded">
                                {{ $facility->rotational_staff }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 font-bold rounded">
                                {{ $facility->total_staff }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Visual bar -->
                                <div class="w-32 h-4 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full flex">
                                        @php
                                            $permanentPercent = $facility->total_staff > 0 ? ($facility->permanent_staff / $facility->total_staff) * 100 : 0;
                                            $rotationalPercent = $facility->total_staff > 0 ? ($facility->rotational_staff / $facility->total_staff) * 100 : 0;
                                        @endphp
                                        <div class="bg-green-500" style="width: {{ $permanentPercent }}%"></div>
                                        <div class="bg-purple-500" style="width: {{ $rotationalPercent }}%"></div>
                                    </div>
                                </div>
                                <span class="text-xs text-slate-600 font-medium">
                                    {{ round($permanentPercent, 0) }}% / {{ round($rotationalPercent, 0) }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-600">
                            <i class="fas fa-users text-3xl opacity-50 mb-3 block"></i>
                            No workforce data available for this date
                        </td>
                    </tr>
                @endforelse
            </tbody>
            
            @if($workforceDistribution->isNotEmpty())
                <tfoot class="bg-slate-100 border-t border-slate-200">
                    <tr>
                        <td class="px-6 py-4 text-sm font-bold text-slate-800">Total</td>
                        <td class="px-6 py-4 text-center text-sm font-bold text-slate-800">
                            {{ $workforceDistribution->sum('permanent_staff') }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-bold text-slate-800">
                            {{ $workforceDistribution->sum('rotational_staff') }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-bold text-slate-800">
                            {{ $workforceDistribution->sum('total_staff') }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-bold text-slate-800">
                            100%
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    <!-- Legend -->
    <div class="mt-6 bg-white rounded-lg shadow p-4">
        <p class="text-sm font-medium text-slate-700 mb-3">Legend:</p>
        <div class="flex flex-wrap gap-6">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-green-500 rounded"></div>
                <span class="text-sm text-slate-600"><strong>Permanent Staff:</strong> Staff with primary facility assignment</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-purple-500 rounded"></div>
                <span class="text-sm text-slate-600"><strong>Rotational Staff:</strong> Staff with active assignments on this date</span>
            </div>
        </div>
    </div>
</div>
@endsection
