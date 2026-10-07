@extends('layouts.dashboard')

@section('title', 'Compliance - DMO Dashboard')

@section('content')
<div class="container-fluid px-4 py-8">
    <!-- Header with Date Range Filter -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Compliance</h1>
            <p class="text-gray-600 mt-1">Statutory deadline tracking and reporting</p>
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

    <!-- Compliance KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Statutory Deadlines Met -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Deadlines Met</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['statutory_deadline_met'] }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <x-lucide-check-circle-2 class="w-8 h-8 text-green-600" />
                </div>
            </div>
        </div>

        <!-- At Risk -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">At Risk</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['statutory_deadline_at_risk'] }}</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <x-lucide-alert-circle class="w-8 h-8 text-yellow-600" />
                </div>
            </div>
        </div>

        <!-- First Medical Report -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Medical Reports</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['first_medical_report_submitted'] }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <x-lucide-file-text class="w-8 h-8 text-blue-600" />
                </div>
            </div>
        </div>

        <!-- Final Report Clearance -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Closed Cases</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['final_report_clearance'] }}</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <x-lucide-check class="w-8 h-8 text-purple-600" />
                </div>
            </div>
        </div>
    </div>

    <!-- Compliance Status Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Compliance Progress -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Compliance Status</h3>
            
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Statutory Compliance</span>
                        @php
                            $total = $statistics['statutory_deadline_met'] + $statistics['statutory_deadline_at_risk'];
                            $percentage = $total > 0 ? round(($statistics['statutory_deadline_met'] / $total) * 100) : 0;
                        @endphp
                        <span class="text-sm font-semibold text-gray-900">{{ $percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-emerald-600 h-3 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Medical Report Submission</span>
                        @php
                            $reportPercentage = $statistics['first_medical_report_submitted'] > 0 ? 100 : 0;
                        @endphp
                        <span class="text-sm font-semibold text-gray-900">{{ $reportPercentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ $reportPercentage }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Case Closure Rate</span>
                        @php
                            $totalCases = count($allIncidents);
                            $closureRate = $totalCases > 0 ? round(($statistics['final_report_clearance'] / $totalCases) * 100) : 0;
                        @endphp
                        <span class="text-sm font-semibold text-gray-900">{{ $closureRate }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-purple-600 h-3 rounded-full transition-all" style="width: {{ $closureRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compliance by Facility -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cases by Facility</h3>
            @if($complianceByFacility->count() > 0)
                <div class="space-y-3">
                    @foreach($complianceByFacility as $facility => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $facility }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ ($count / $complianceByFacility->max() * 100) }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900 w-8 text-right">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">No facility data available</p>
            @endif
        </div>
    </div>

    <!-- Cases List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Cases Requiring Attention</h2>
        </div>

        @if($allIncidents->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Reference</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Facility</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Reported</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Compliance Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($allIncidents as $incident)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-emerald-700">{{ $incident->reference_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $incident->facility?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $incident->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $hoursElapsed = $incident->created_at->diffInHours(now());
                                        if ($hoursElapsed <= 48) {
                                            $status = 'Met';
                                            $badgeClass = 'bg-green-100 text-green-800';
                                        } elseif ($hoursElapsed <= 72) {
                                            $status = 'At Risk';
                                            $badgeClass = 'bg-yellow-100 text-yellow-800';
                                        } else {
                                            $status = 'Overdue';
                                            $badgeClass = 'bg-red-100 text-red-800';
                                        }
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">{{ $status }}</span>
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
