@extends('layouts.cos-dashboard')

@section('title', 'Create Announcement')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Create Announcement</h1>

        <form action="{{ route('cos.announcements.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title') }}"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-600 focus:border-transparent @error('title') border-red-500 @enderror"
                    placeholder="Announcement title">
                @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                <textarea 
                    id="content" 
                    name="content" 
                    rows="6"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-600 focus:border-transparent @error('content') border-red-500 @enderror"
                    placeholder="Announcement content...">{{ old('content') }}</textarea>
                @error('content')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Target Audience -->
            <div>
                <label for="target_audience" class="block text-sm font-medium text-gray-700 mb-2">Target Audience *</label>
                <select 
                    id="target_audience" 
                    name="target_audience"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-600 focus:border-transparent @error('target_audience') border-red-500 @enderror">
                    <option value="">-- Select Audience --</option>
                    <option value="supervisors" {{ old('target_audience') === 'supervisors' ? 'selected' : '' }}>Supervisors</option>
                    <option value="pmds" {{ old('target_audience') === 'pmds' ? 'selected' : '' }}>PMDs</option>
                    <option value="doctors" {{ old('target_audience') === 'doctors' ? 'selected' : '' }}>Medical Doctors</option>
                    <option value="all_staff" {{ old('target_audience') === 'all_staff' ? 'selected' : '' }}>All Staff</option>
                </select>
                @error('target_audience')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Scheduling -->
            <div>
                <label for="scheduled_for" class="block text-sm font-medium text-gray-700 mb-2">Schedule (Optional)</label>
                <p class="text-sm text-gray-600 mb-2">Leave empty for immediate dispatch</p>
                <input 
                    type="datetime-local" 
                    id="scheduled_for" 
                    name="scheduled_for" 
                    value="{{ old('scheduled_for') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-600 focus:border-transparent @error('scheduled_for') border-red-500 @enderror">
                @error('scheduled_for')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4 border-t">
                <button 
                    type="submit" 
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Create Announcement
                </button>
                <a 
                    href="{{ route('cos.announcements.index') }}" 
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
