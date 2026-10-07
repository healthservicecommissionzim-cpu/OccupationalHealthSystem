@extends('layouts.dashboard')

@section('title', 'Medical Review - DMO Dashboard')

@section('content')
<div class="container-fluid px-4 py-8">
    <!-- Header with Date Range Filter -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Medical Review</h1>
            <p class="text-gray-600 mt-1">Cases pending verification and review</p>
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

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pending Review -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending Review</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $statistics['pending_review'] }}</p>
                </div>
                <div class="bg-emerald-50 p-3 rounded-lg">
                    <x-lucide-inbox class="w-8 h-8 text-emerald-600" />
                </div>
            </div>
        </div>

        <!-- Verified -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Verified</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $statistics['verified'] }}</p>
                </div>
                <div class="bg-emerald-50 p-3 rounded-lg">
                    <x-lucide-check-circle-2 class="w-8 h-8 text-emerald-600" />
                </div>
            </div>
        </div>

        <!-- Recommended -->
        <div class="bg-white rounded-lg shadow border-l-4 border-emerald-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Recommended</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $statistics['recommended'] }}</p>
                </div>
                <div class="bg-emerald-50 p-3 rounded-lg">
                    <x-lucide-trending-up class="w-8 h-8 text-emerald-600" />
                </div>
            </div>
        </div>
    </div>

    <!-- Cases Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Cases for Review</h2>
        </div>

        @if($incidents->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Reference</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Worker Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Facility</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Received</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($incidents as $incident)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-emerald-700">{{ $incident->reference_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $incident->injured_worker_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $incident->facility?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($incident->executive_audit_verified)
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>
                                    @elseif($incident->executive_recommendation_submitted_at)
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Recommended</span>
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $incident->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('executive.review', $incident) }}" class="inline-block px-3 py-1 bg-emerald-600 text-white rounded text-sm hover:bg-emerald-700">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $incidents->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-gray-600">No cases pending review</p>
            </div>
        @endif
    </div>
</div>
@endsection
