@extends('layouts.dashboard')

@section('page_title', 'Performance Metrics')

@section('header_title', 'HR Performance Metrics')
@section('header_subtitle', 'Review Rate, Processing Time & Monthly Summary')

@section('content')
<div class="space-y-6">
    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Review Rate Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Review Rate</p>
                    <p class="text-4xl font-bold text-slate-900 mt-2">{{ $statistics['review_rate'] }}%</p>
                    <p class="text-xs text-slate-500 mt-3">{{ $statistics['reviewed_cases'] }} of {{ $statistics['total_cases'] }} cases reviewed</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-percent text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-500 h-full" style="width: {{ $statistics['review_rate'] }}%"></div>
            </div>
        </div>

        <!-- Processing Time Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Avg Processing Time</p>
                    <p class="text-4xl font-bold text-slate-900 mt-2">{{ $statistics['avg_days'] }} <span class="text-lg">days</span></p>
                    <p class="text-xs text-slate-500 mt-3">{{ $statistics['avg_hours'] }} hours average</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-hourglass-end text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-slate-600">
                <span class="inline-block bg-green-50 px-2 py-1 rounded">{{ $statistics['reviewed_count'] }} cases processed</span>
            </div>
        </div>

        <!-- Total Cases Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Total Cases</p>
                    <p class="text-4xl font-bold text-slate-900 mt-2">{{ $statistics['total_cases'] }}</p>
                    <p class="text-xs text-slate-500 mt-3">{{ $statistics['pending_review'] }} pending review</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 space-y-1 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-600">Reviewed:</span>
                    <span class="font-semibold text-slate-900">{{ $statistics['reviewed_cases'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Pending:</span>
                    <span class="font-semibold text-slate-900">{{ $statistics['pending_review'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Summary Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-purple-600"></i>
                Monthly Summary (Last 12 Months)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Month</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Cases Reported</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-700 uppercase">% of Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($monthlyData as $month)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('F Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block bg-blue-50 px-3 py-1 rounded-full text-sm font-semibold text-blue-700">
                                    {{ $month->total }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-slate-700">
                                    {{ $statistics['total_cases'] > 0 ? round(($month->total / $statistics['total_cases']) * 100, 1) : 0 }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                                <i class="fa-solid fa-inbox text-2xl mb-3 block"></i>
                                No data available for the selected period
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-end">
        <a href="{{ route('dashboards.hr') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-600 text-white rounded-lg font-medium hover:bg-slate-700 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>
</div>
@endsection
