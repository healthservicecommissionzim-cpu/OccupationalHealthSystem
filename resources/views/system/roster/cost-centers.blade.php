@extends('layouts.app')

@section('title', 'Cost Center Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-dollar-sign text-blue-600 mr-3"></i>
                    Cost Center Management
                </h1>
                <p class="text-slate-600 mt-2">Configure cost center codes for facilities</p>
            </div>
            <a href="{{ route('system.roster.manage-rosters.index') }}" class="text-slate-600 hover:text-slate-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Rosters
            </a>
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

    <!-- Create Cost Center Form -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold text-slate-800 mb-4">
            <i class="fas fa-plus-circle text-blue-600 mr-2"></i>
            {{ isset($editingCostCenter) ? 'Edit Cost Center' : 'Create New Cost Center' }}
        </h2>
        
        <form method="POST" action="{{ isset($editingCostCenter) ? route('system.roster.cost-centers.update', $editingCostCenter->id) : route('system.roster.cost-centers.store') }}">
            @csrf
            @if(isset($editingCostCenter))
                @method('PUT')
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
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

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="facility_id" class="block text-sm font-medium text-slate-700 mb-2">
                        Facility <span class="text-red-500">*</span>
                    </label>
                    <select name="facility_id" id="facility_id" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('facility_id') border-red-500 @enderror"
                        required>
                        <option value="">Select facility...</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" 
                                {{ (old('facility_id', $editingCostCenter->facility_id ?? '') == $facility->id) ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="cost_center_code" class="block text-sm font-medium text-slate-700 mb-2">
                        Cost Center Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="cost_center_code" id="cost_center_code" 
                        value="{{ old('cost_center_code', $editingCostCenter->cost_center_code ?? '') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cost_center_code') border-red-500 @enderror"
                        placeholder="e.g., CC-001"
                        required>
                </div>

                <div>
                    <label for="cost_center_name" class="block text-sm font-medium text-slate-700 mb-2">
                        Cost Center Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="cost_center_name" id="cost_center_name" 
                        value="{{ old('cost_center_name', $editingCostCenter->cost_center_name ?? '') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cost_center_name') border-red-500 @enderror"
                        placeholder="e.g., Emergency Department"
                        required>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>{{ isset($editingCostCenter) ? 'Update' : 'Create' }}
                    </button>
                    @if(isset($editingCostCenter))
                        <a href="{{ route('system.roster.cost-centers.index') }}" 
                            class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                            Cancel
                        </a>
                    @endif
                </div>
            </div>

            @if(isset($editingCostCenter))
                <div class="mt-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" 
                            {{ old('is_active', $editingCostCenter->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">Active</span>
                    </label>
                </div>
            @endif
        </form>
    </div>

    <!-- Cost Centers Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-3 bg-slate-100 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800">
                <i class="fas fa-list mr-2"></i>All Cost Centers
            </h2>
        </div>
        
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Facility</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Code</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Name</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Created</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($costCenters as $costCenter)
                    <tr class="hover:bg-slate-50 transition-colors {{ !$costCenter->is_active ? 'opacity-60' : '' }}">
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="font-medium">{{ $costCenter->facility->name }}</div>
                            <div class="text-xs text-slate-500">{{ $costCenter->facility->facilityType->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 text-xs font-mono font-semibold rounded">
                                {{ $costCenter->cost_center_code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $costCenter->cost_center_name }}</td>
                        <td class="px-6 py-4 text-center text-sm">
                            @if($costCenter->is_active)
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
                            {{ $costCenter->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('system.roster.cost-centers.edit', $costCenter->id) }}" 
                                    class="text-blue-600 hover:text-blue-700 font-medium">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                @if($costCenter->is_active)
                                    <form action="{{ route('system.roster.cost-centers.update', $costCenter->id) }}" 
                                        method="POST" 
                                        onsubmit="return confirm('Are you sure you want to deactivate this cost center?');"
                                        class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="hidden" name="facility_id" value="{{ $costCenter->facility_id }}">
                                        <input type="hidden" name="cost_center_code" value="{{ $costCenter->cost_center_code }}">
                                        <input type="hidden" name="cost_center_name" value="{{ $costCenter->cost_center_name }}">
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
                        <td colspan="6" class="px-6 py-8 text-center text-slate-600">
                            <i class="fas fa-inbox text-3xl opacity-50 mb-3 block"></i>
                            No cost centers configured yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($costCenters->hasPages())
        <div class="mt-6">
            {{ $costCenters->links() }}
        </div>
    @endif

    <!-- Statistics -->
    @if($costCenters->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">Total Cost Centers</p>
                        <p class="text-2xl font-bold text-slate-800 mt-2">{{ $costCenters->total() }}</p>
                    </div>
                    <i class="fas fa-dollar-sign text-blue-500 text-3xl opacity-50"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">Active</p>
                        <p class="text-2xl font-bold text-green-700 mt-2">{{ $costCenters->where('is_active', true)->count() }}</p>
                    </div>
                    <i class="fas fa-check-circle text-green-500 text-3xl opacity-50"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">Inactive</p>
                        <p class="text-2xl font-bold text-slate-700 mt-2">{{ $costCenters->where('is_active', false)->count() }}</p>
                    </div>
                    <i class="fas fa-ban text-slate-500 text-3xl opacity-50"></i>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
