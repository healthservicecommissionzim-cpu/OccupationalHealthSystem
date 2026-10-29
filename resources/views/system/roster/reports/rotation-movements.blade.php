@extends('layouts.app')

@section('title', 'Rotation Movements Report')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-exchange-alt text-green-600 mr-3"></i>
                    Rotation Movements Report
                </h1>
                <p class="text-slate-600 mt-2">Track incoming and outgoing staff movements for facilities</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('system.roster.reports.cost-center') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    <i class="fas fa-chart-pie mr-2"></i>Cost Center Report
                </a>
                <a href="{{ route('system.roster.reports.workforce-distribution') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    <i class="fas fa-users mr-2"></i>Workforce Distribution
                </a>
                <a href="{{ route('dashboards.super_admin') }}" class="text-slate-600 hover:text-slate-700 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="{{ route('system.roster.reports.rotation-movements') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Facility <span class="text-red-500">*</span>
                    </label>
                    <select name="facility_id" required
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a facility...</option>
                        @foreach($facilities as $fac)
                            <option value="{{ $fac->id }}" {{ (isset($facility) && $facility->id == $fac->id) ? 'selected' : '' }}>
                                {{ $fac->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('system.roster.reports.rotation-movements') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    @if(isset($facility))
        <!-- Facility Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg shadow p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">
                        <i class="fas fa-hospital mr-2"></i>
                        {{ $facility->name }}
                    </h2>
                    <p class="text-blue-100 mt-1">
                        Movements from {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="flex gap-4">
                        <div class="bg-white/10 rounded-lg px-4 py-2">
                            <p class="text-sm text-blue-100">Incoming</p>
                            <p class="text-2xl font-bold">{{ $movements['incoming']->count() }}</p>
                        </div>
                        <div class="bg-white/10 rounded-lg px-4 py-2">
                            <p class="text-sm text-blue-100">Outgoing</p>
                            <p class="text-2xl font-bold">{{ $movements['outgoing']->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Incoming Staff Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <div class="bg-green-50 border-b border-green-200 px-6 py-4">
                <h3 class="text-lg font-bold text-green-800">
                    <i class="fas fa-arrow-down text-green-600 mr-2"></i>
                    Incoming Staff
                </h3>
                <p class="text-sm text-green-700 mt-1">
                    Staff with assignments starting at this facility within the selected date range
                </p>
            </div>

            @if($movements['incoming']->isNotEmpty())
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Staff Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Assignment Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Primary Facility</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Start Date</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">End Date</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Duration</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($movements['incoming'] as $assignment)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-800">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-circle text-slate-400 mr-2"></i>
                                        <div>
                                            <div>{{ $assignment->staffRoster->user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $assignment->staffRoster->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    @if($assignment->assignment_type === 'rotation')
                                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                            <i class="fas fa-sync-alt mr-1"></i>Rotation
                                        </span>
                                    @elseif($assignment->assignment_type === 'locum_contract')
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">
                                            <i class="fas fa-briefcase mr-1"></i>Locum
                                        </span>
                                    @elseif($assignment->assignment_type === 'student_placement')
                                        <span class="inline-block px-2 py-1 bg-orange-100 text-orange-800 rounded text-xs font-semibold">
                                            <i class="fas fa-graduation-cap mr-1"></i>Student
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <i class="fas fa-home text-slate-500 mr-1"></i>
                                    {{ $assignment->staffRoster->primaryFacility->name }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-slate-700">
                                    <span class="inline-block px-2 py-1 bg-green-50 text-green-800 rounded text-xs font-medium">
                                        <i class="fas fa-calendar-check mr-1"></i>
                                        {{ $assignment->assignment_start_date->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-slate-700">
                                    {{ $assignment->assignment_end_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-slate-700">
                                    <span class="text-slate-600 font-medium">
                                        {{ $assignment->assignment_start_date->diffInDays($assignment->assignment_end_date) }} days
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-12 text-center text-slate-600">
                    <i class="fas fa-arrow-down text-4xl text-slate-300 mb-3 block"></i>
                    <p class="text-lg">No incoming staff for this period</p>
                    <p class="text-sm text-slate-500 mt-1">No assignments starting at this facility within the selected date range</p>
                </div>
            @endif
        </div>

        <!-- Outgoing Staff Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-orange-50 border-b border-orange-200 px-6 py-4">
                <h3 class="text-lg font-bold text-orange-800">
                    <i class="fas fa-arrow-up text-orange-600 mr-2"></i>
                    Outgoing Staff
                </h3>
                <p class="text-sm text-orange-700 mt-1">
                    Staff with assignments ending at this facility within the selected date range
                </p>
            </div>

            @if($movements['outgoing']->isNotEmpty())
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Staff Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Assignment Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Primary Facility</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Start Date</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">End Date</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Duration</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($movements['outgoing'] as $assignment)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-800">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-circle text-slate-400 mr-2"></i>
                                        <div>
                                            <div>{{ $assignment->staffRoster->user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $assignment->staffRoster->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    @if($assignment->assignment_type === 'rotation')
                                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                            <i class="fas fa-sync-alt mr-1"></i>Rotation
                                        </span>
                                    @elseif($assignment->assignment_type === 'locum_contract')
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">
                                            <i class="fas fa-briefcase mr-1"></i>Locum
                                        </span>
                                    @elseif($assignment->assignment_type === 'student_placement')
                                        <span class="inline-block px-2 py-1 bg-orange-100 text-orange-800 rounded text-xs font-semibold">
                                            <i class="fas fa-graduation-cap mr-1"></i>Student
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <i class="fas fa-home text-slate-500 mr-1"></i>
                                    {{ $assignment->staffRoster->primaryFacility->name }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-slate-700">
                                    {{ $assignment->assignment_start_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-slate-700">
                                    <span class="inline-block px-2 py-1 bg-orange-50 text-orange-800 rounded text-xs font-medium">
                                        <i class="fas fa-calendar-times mr-1"></i>
                                        {{ $assignment->assignment_end_date->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-slate-700">
                                    <span class="text-slate-600 font-medium">
                                        {{ $assignment->assignment_start_date->diffInDays($assignment->assignment_end_date) }} days
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-12 text-center text-slate-600">
                    <i class="fas fa-arrow-up text-4xl text-slate-300 mb-3 block"></i>
                    <p class="text-lg">No outgoing staff for this period</p>
                    <p class="text-sm text-slate-500 mt-1">No assignments ending at this facility within the selected date range</p>
                </div>
            @endif
        </div>

    @else
        <!-- No Facility Selected -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-hospital text-5xl text-slate-300 mb-4"></i>
            <p class="text-slate-600 text-lg">Please select a facility to view rotation movements</p>
            <p class="text-slate-500 text-sm mt-2">Use the filter above to choose a facility and date range</p>
        </div>
    @endif
</div>
@endsection
