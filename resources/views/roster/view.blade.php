@extends('layouts.app')

@section('title', 'View Staff Rosters')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-users text-blue-600 mr-3"></i>
                    Staff Rosters
                </h1>
                <p class="text-slate-600 mt-2">
                    View rotational staff, locum doctors, and nursing students across facilities
                    @if($user->hasRole('district_hr'))
                        <span class="text-sm">({{ $user->district->name ?? 'Your District' }})</span>
                    @elseif($user->hasRole('hospital_hr'))
                        <span class="text-sm">({{ $user->facility->name ?? 'Your Facility' }})</span>
                    @endif
                </p>
            </div>
            <div>
                <a href="{{ url()->previous() }}" class="text-slate-600 hover:text-slate-700 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- Read-Only Notice -->
    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6">
        <i class="fas fa-info-circle mr-2"></i>
        <span class="font-medium">Read-Only Access:</span> 
        You have view-only access to roster information. Contact your Super Admin to modify roster data.
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="{{ route('roster.view') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Search Staff</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Staff name or email..." 
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
                <a href="{{ route('roster.view') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    @if($rosters->total() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600">Total Staff</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $rosters->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600">Active Rosters</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $rosters->where('active', true)->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600">Rotational Staff</p>
                        <p class="text-2xl font-bold text-purple-600">
                            {{ $rosters->where('staff_type', 'rotational')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600">Total Assignments</p>
                        <p class="text-2xl font-bold text-orange-600">
                            {{ $rosters->sum(function($roster) { return $roster->assignments->count(); }) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Rosters Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Staff Member</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Staff Type</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Primary Facility</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Start Date</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">End Date</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Current Assignments</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($rosters as $roster)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="font-medium">{{ $roster->user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $roster->user->email }}</div>
                            @if($roster->user->phone)
                                <div class="text-xs text-slate-500">
                                    <i class="fas fa-phone mr-1"></i>{{ $roster->user->phone }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full uppercase
                                @if($roster->staff_type === 'permanent') bg-blue-100 text-blue-800
                                @elseif($roster->staff_type === 'locum') bg-purple-100 text-purple-800
                                @elseif($roster->staff_type === 'student') bg-green-100 text-green-800
                                @else bg-orange-100 text-orange-800
                                @endif">
                                {{ $roster->staff_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="font-medium">{{ $roster->primaryFacility->name }}</div>
                            @if($roster->primaryFacility->district)
                                <div class="text-xs text-slate-500">{{ $roster->primaryFacility->district->name }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            {{ $roster->start_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            {{ $roster->end_date ? $roster->end_date->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            @if($roster->active)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Active
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-times-circle mr-1"></i>Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            @php
                                $activeAssignments = $roster->assignments->where('status', 'active');
                                $totalAssignments = $roster->assignments->count();
                            @endphp
                            @if($activeAssignments->count() > 0)
                                <button onclick="toggleAssignments({{ $roster->id }})" 
                                    class="text-blue-600 hover:text-blue-700 font-medium cursor-pointer">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $activeAssignments->count() }} active
                                    @if($totalAssignments > $activeAssignments->count())
                                        <span class="text-slate-500">({{ $totalAssignments }} total)</span>
                                    @endif
                                    <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                </button>
                            @elseif($totalAssignments > 0)
                                <span class="text-slate-500">
                                    <i class="fas fa-history mr-1"></i>
                                    {{ $totalAssignments }} historical
                                </span>
                            @else
                                <span class="text-slate-400">No assignments</span>
                            @endif
                        </td>
                    </tr>
                    <!-- Expandable Assignments Row -->
                    <tr id="assignments-{{ $roster->id }}" class="hidden bg-slate-50">
                        <td colspan="7" class="px-6 py-4">
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h4 class="font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-clipboard-list mr-2"></i>Current Assignments
                                </h4>
                                @if($roster->assignments->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($roster->assignments->sortByDesc('assignment_start_date') as $assignment)
                                            <div class="bg-white rounded-lg p-4 border border-slate-200">
                                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                    <div>
                                                        <p class="text-xs text-slate-500 mb-1">Facility</p>
                                                        <p class="text-sm font-medium text-slate-800">
                                                            {{ $assignment->facility->name }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-slate-500 mb-1">Assignment Period</p>
                                                        <p class="text-sm text-slate-700">
                                                            {{ $assignment->assignment_start_date->format('M d, Y') }} 
                                                            <i class="fas fa-arrow-right text-xs mx-1"></i> 
                                                            {{ $assignment->assignment_end_date->format('M d, Y') }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-slate-500 mb-1">Assignment Type</p>
                                                        <p class="text-sm text-slate-700">
                                                            <span class="capitalize">{{ str_replace('_', ' ', $assignment->assignment_type) }}</span>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-slate-500 mb-1">Status</p>
                                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                                            @if($assignment->status === 'active') bg-green-100 text-green-800
                                                            @elseif($assignment->status === 'completed') bg-slate-100 text-slate-700
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                            {{ ucfirst($assignment->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @if($assignment->notes)
                                                    <div class="mt-3 pt-3 border-t border-slate-200">
                                                        <p class="text-xs text-slate-500 mb-1">Notes</p>
                                                        <p class="text-sm text-slate-700">{{ $assignment->notes }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500">No assignments found for this staff member.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-600">
                            <i class="fas fa-inbox text-4xl opacity-50 mb-3 block"></i>
                            <p class="font-medium">No rosters found</p>
                            <p class="text-sm text-slate-500 mt-1">Try adjusting your filters or search criteria</p>
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

<script>
function toggleAssignments(rosterId) {
    const row = document.getElementById(`assignments-${rosterId}`);
    const icon = event.target.closest('button').querySelector('.fa-chevron-down, .fa-chevron-up');
    
    if (row.classList.contains('hidden')) {
        row.classList.remove('hidden');
        if (icon) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }
    } else {
        row.classList.add('hidden');
        if (icon) {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
}
</script>
@endsection
