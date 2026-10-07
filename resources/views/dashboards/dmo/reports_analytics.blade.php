@extends('layouts.dashboard')

@section('title', 'Reports & Analytics - DMO Dashboard')

@section('content')
<div class="container-fluid px-4 py-8">
    <!-- Header with Date Range Filter -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
            <p class="text-gray-600 mt-1">Operational and clinical management reports</p>
        </div>
        
        <form method="GET" class="flex gap-2">
            <select name="date_range" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>All Time</option>
                <option value="7days" {{ $dateRange === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                <option value="30days" {{ $dateRange === '30days' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="90days" {{ $dateRange === '90days' ? 'selected' : '' }}>Last 90 Days</option>
            </select>
        </form>
    </div>

    <!-- Operational Reports KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Open Cases -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Open Cases</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['open_cases'] }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <x-lucide-briefcase class="w-8 h-8 text-blue-600" />
                </div>
            </div>
        </div>

        <!-- Return to Work (RTW) -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">RTW Pacing</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['rtw_pacing'] }}</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <x-lucide-trending-up class="w-8 h-8 text-purple-600" />
                </div>
            </div>
        </div>

        <!-- Overdue Certificates -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Overdue Certs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['overdue_certs'] }}</p>
                </div>
                <div class="bg-red-50 p-3 rounded-lg">
                    <x-lucide-alert-triangle class="w-8 h-8 text-red-600" />
                </div>
            </div>
        </div>

        <!-- Complex Cases -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Complex Cases</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['complex_cases'] }}</p>
                </div>
                <div class="bg-orange-50 p-3 rounded-lg">
                    <x-lucide-wrench class="w-8 h-8 text-orange-600" />
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Incidents by Type -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Incidents by Type</h3>
            @if($incidentsByInjury->count() > 0)
                <div class="space-y-3">
                    @foreach($incidentsByInjury as $type => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $type }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ ($count / $incidentsByInjury->max() * 100) }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900 w-8 text-right">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">No data available</p>
            @endif
        </div>

        <!-- Case Status Overview -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Case Status Overview</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <span class="text-sm text-gray-600">Pending Review</span>
                    </div>
                    <span class="text-sm font-medium">{{ $statistics['open_cases'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                        <span class="text-sm text-gray-600">RTW Cases</span>
                    </div>
                    <span class="text-sm font-medium">{{ $statistics['rtw_pacing'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <span class="text-sm text-gray-600">Overdue Documents</span>
                    </div>
                    <span class="text-sm font-medium">{{ $statistics['overdue_certs'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                        <span class="text-sm text-gray-600">Complex Cases</span>
                    </div>
                    <span class="text-sm font-medium">{{ $statistics['complex_cases'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Cases -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Recent Cases</h2>
        </div>
        
        @if($allIncidents->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Reference</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Injury Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Days Open</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($allIncidents->take(10) as $incident)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-emerald-700">{{ $incident->reference_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $incident->incidentType?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $incident->created_at->diffInDays(now()) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($incident->created_at->diffInDays(now()) > 45)
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Complex</span>
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-gray-600">No cases to display</p>
            </div>
        @endif
    </div>
</div>
@endsection
