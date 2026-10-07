@extends('layouts.cos-dashboard')

@section('title', 'Audit Report')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Audit Report</h1>
            <p class="text-gray-600 mt-1">Case audit statistics and trends</p>
        </div>
        @can('export_data')
        <a href="{{ route('cos.audit-log.report-export') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Export Report
        </a>
        @endcan
    </div>

    <!-- Report Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date', now()->subMonths(3)->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label for="actor_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <select id="actor_id" name="actor_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('actor_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                        Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Report Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium">Total Actions</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total_actions'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium">Escalations</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $statistics['escalations'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium">Nudges Sent</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $statistics['nudges_sent'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium">Notes Added</p>
            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $statistics['notes_added'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Actions by Type -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions by Type</h3>
        <div class="space-y-2">
            @foreach($actionsByType as $action => $count)
            <div class="flex items-center justify-between">
                <span class="text-gray-700">{{ ucwords(str_replace('_', ' ', $action)) }}</span>
                <div class="flex items-center gap-2">
                    <div class="w-24 h-2 bg-gray-200 rounded">
                        <div class="h-2 bg-blue-600 rounded" style="width: {{ ($count / ($statistics['total_actions'] ?: 1) * 100) }}%"></div>
                    </div>
                    <span class="text-gray-900 font-medium w-12 text-right">{{ $count }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Top Users -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Users by Activity</h3>
            <div class="space-y-3">
                @forelse($topUsers as $user => $count)
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">{{ $user }}</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-900 text-sm font-medium rounded">{{ $count }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-sm">No data available</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cases with Most Activity</h3>
            <div class="space-y-3">
                @forelse($topCases as $case => $count)
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">{{ $case }}</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-900 text-sm font-medium rounded">{{ $count }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-sm">No data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
