@extends('layouts.app')

@section('title', isset($roster) ? 'Edit Roster' : 'Create Roster')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                    {{ isset($roster) ? 'Edit Roster' : 'Create Roster' }}
                </h1>
                <p class="text-slate-600 mt-2">
                    {{ isset($roster) ? 'Update roster information' : 'Add a new staff roster record' }}
                </p>
            </div>
            <a href="{{ route('system.roster.manage-rosters.index') }}" class="text-slate-600 hover:text-slate-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Rosters
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ isset($roster) ? route('system.roster.manage-rosters.update', $roster->id) : route('system.roster.manage-rosters.store') }}">
            @csrf
            @if(isset($roster))
                @method('PUT')
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                    <div class="font-semibold mb-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>Please fix the following errors:
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                <!-- User Selection -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-slate-700 mb-2">
                        User <span class="text-red-500">*</span>
                    </label>
                    @if(isset($roster))
                        <input type="text" value="{{ $roster->user->name }} ({{ $roster->user->email }})" 
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" readonly>
                        <input type="hidden" name="user_id" value="{{ $roster->user_id }}">
                    @else
                        <select name="user_id" id="user_id" 
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @enderror"
                            required>
                            <option value="">Select a user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    @endif
                    @error('user_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-1">Select the staff member for this roster</p>
                </div>

                <!-- Staff Type -->
                <div>
                    <label for="staff_type" class="block text-sm font-medium text-slate-700 mb-2">
                        Staff Type <span class="text-red-500">*</span>
                    </label>
                    <select name="staff_type" id="staff_type" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('staff_type') border-red-500 @enderror"
                        required>
                        <option value="">Select staff type...</option>
                        <option value="permanent" {{ (old('staff_type', $roster->staff_type ?? '') === 'permanent') ? 'selected' : '' }}>
                            Permanent
                        </option>
                        <option value="locum" {{ (old('staff_type', $roster->staff_type ?? '') === 'locum') ? 'selected' : '' }}>
                            Locum
                        </option>
                        <option value="student" {{ (old('staff_type', $roster->staff_type ?? '') === 'student') ? 'selected' : '' }}>
                            Student
                        </option>
                        <option value="rotational" {{ (old('staff_type', $roster->staff_type ?? '') === 'rotational') ? 'selected' : '' }}>
                            Rotational
                        </option>
                    </select>
                    @error('staff_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Primary Facility -->
                <div>
                    <label for="primary_facility_id" class="block text-sm font-medium text-slate-700 mb-2">
                        Primary Facility <span class="text-red-500">*</span>
                    </label>
                    <select name="primary_facility_id" id="primary_facility_id" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('primary_facility_id') border-red-500 @enderror"
                        required>
                        <option value="">Select primary facility...</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ (old('primary_facility_id', $roster->primary_facility_id ?? '') == $facility->id) ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('primary_facility_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-1">The staff member's home base or permanent facility</p>
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-slate-700 mb-2">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" 
                            value="{{ old('start_date', isset($roster) ? $roster->start_date->format('Y-m-d') : '') }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror"
                            required>
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-slate-700 mb-2">
                            End Date <span class="text-slate-400">(Optional)</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" 
                            value="{{ old('end_date', isset($roster) && $roster->end_date ? $roster->end_date->format('Y-m-d') : '') }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-slate-500 mt-1">Leave blank for ongoing roster</p>
                    </div>
                </div>

                <!-- Active Status (Edit only) -->
                @if(isset($roster))
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="active" value="1" 
                                {{ old('active', $roster->active) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-700">Active</span>
                        </label>
                        <p class="text-xs text-slate-500 mt-1 ml-6">Uncheck to deactivate this roster</p>
                    </div>
                @endif

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">
                        Notes <span class="text-slate-400">(Optional)</span>
                    </label>
                    <textarea name="notes" id="notes" rows="4" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                        placeholder="Additional notes or comments...">{{ old('notes', $roster->notes ?? '') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>{{ isset($roster) ? 'Update Roster' : 'Create Roster' }}
                    </button>
                    <a href="{{ route('system.roster.manage-rosters.index') }}" 
                        class="px-6 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    @if(isset($roster))
        <!-- Audit Log Section -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-slate-800 mb-4">
                <i class="fas fa-history text-slate-600 mr-2"></i>Change History
            </h2>
            <p class="text-sm text-slate-600 mb-4">Created by {{ $roster->createdBy->name }} on {{ $roster->created_at->format('M d, Y H:i') }}</p>
            <p class="text-sm text-slate-600">Last updated: {{ $roster->updated_at->format('M d, Y H:i') }}</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Add searchable dropdown functionality if needed
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('user_id');
        if (userSelect) {
            // Simple filter functionality
            userSelect.addEventListener('change', function() {
                if (this.value) {
                    this.classList.remove('border-red-500');
                }
            });
        }
    });
</script>
@endpush
@endsection
