@extends('layouts.app')

@section('title', 'Manage Staff Rosters')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-users-cog text-blue-600 mr-3"></i>
                    Manage Staff Rosters
                </h1>
                <p class="text-slate-600 mt-2">Track rotational staff, locum doctors, and nursing students across facilities</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('system.roster.import.form') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    <i class="fas fa-file-upload mr-2"></i>Bulk Import
                </a>
                <a href="{{ route('dashboards.super_admin') }}" class="text-slate-600 hover:text-slate-700 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Multiple Assignment Conflicts Warning -->
    @if($conflicts->isNotEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg mb-6">
            <div class="px-4 py-3 border-b border-yellow-200">
                <h3 class="text-yellow-900 font-semibold flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Data Integrity Warning: Multiple Assignment Conflicts Detected
                </h3>
                <p class="text-yellow-800 text-sm mt-1">
                    The following staff members have overlapping assignments at different facilities on the same date. This requires Super Admin resolution.
                </p>
            </div>
            <div class="px-4 py-3 space-y-4">
                @foreach($conflicts as $conflict)
                    <div class="bg-white border border-yellow-300 rounded p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $conflict['user_name'] }}</div>
                                <div class="text-sm text-slate-600">
                                    Conflict Date: <span class="font-medium">{{ \Carbon\Carbon::parse($conflict['conflict_date'])->format('M d, Y') }}</span>
                                </div>
                                <div class="text-sm text-yellow-700 font-medium mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Assigned to {{ $conflict['facility_count'] }} different facilities on the same date
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Conflicting Assignments:</div>
                            @foreach($conflict['assignments'] as $assignment)
                                <div class="flex items-center justify-between bg-slate-50 rounded px-3 py-2 text-sm">
                                    <div>
                                        <span class="font-medium text-slate-900">{{ $assignment['facility_name'] }}</span>
                                        <span class="text-slate-600 mx-2">•</span>
                                        <span class="text-slate-600">{{ $assignment['assignment_type'] }}</span>
                                    </div>
                                    <div class="text-slate-600 text-xs">
                                        {{ \Carbon\Carbon::parse($assignment['assignment_start_date'])->format('M d, Y') }} - 
                                        {{ \Carbon\Carbon::parse($assignment['assignment_end_date'])->format('M d, Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-xs text-slate-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Action Required: Cancel or modify one of the conflicting assignments to resolve this data integrity issue.
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="{{ route('system.roster.manage-rosters.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="User name..." 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Staff Type</label>
                    <select name="staff_type" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="permanent" {{ request('staff_type') === 'permanent' ? 'selected' : '' }}>Permanent</option>
                        <option value="locum" {{ request('staff_type') === 'locum' ? 'selected' : '' }}>Locum</option>
                        <option value="student" {{ request('staff_type') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="rotational" {{ request('staff_type') === 'rotational' ? 'selected' : '' }}>Rotational</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Primary Facility</label>
                    <select name="facility_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Facilities</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="active" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('system.roster.manage-rosters.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Create Roster Button -->
    <div class="mb-6">
        <a href="{{ route('system.roster.manage-rosters.create') }}" 
            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Create Roster
        </a>
    </div>

    <!-- Rosters Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">User</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Staff Type</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Primary Facility</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Start Date</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">End Date</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Assignments</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($rosters as $roster)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="font-medium">{{ $roster->user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $roster->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full uppercase">
                                {{ $roster->staff_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $roster->primaryFacility->name }}</td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            {{ $roster->start_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            {{ $roster->end_date ? $roster->end_date->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            @if($roster->active)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    Active
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 text-xs font-semibold rounded-full">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            <a href="{{ route('system.roster.facility-assignments.index', ['staff_roster_id' => $roster->id]) }}" 
                                class="text-blue-600 hover:text-blue-700 font-medium">
                                {{ $roster->assignments->count() }} assignment(s)
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('system.roster.manage-rosters.edit', $roster->id) }}" 
                                    class="text-blue-600 hover:text-blue-700 font-medium">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                @if($roster->active)
                                    <form action="{{ route('system.roster.manage-rosters.destroy', $roster->id) }}" 
                                        method="POST" 
                                        onsubmit="return confirm('Are you sure you want to deactivate this roster?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                                            <i class="fas fa-times-circle mr-1"></i>Deactivate
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-600">
                            <i class="fas fa-inbox text-3xl opacity-50 mb-3 block"></i>
                            No rosters found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($rosters->hasPages())
        <div class="mt-6">
            {{ $rosters->links() }}
        </div>
    @endif
</div>
@endsection
