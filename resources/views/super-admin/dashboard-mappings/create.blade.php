@extends('layouts.dashboard')

@section('page_title', 'Create Dashboard Mapping')

@section('header_title', '')
@section('header_subtitle', '')

@section('dashboard_content')

<style>
    #sidebar { display: none !important; }
    #header { display: none !important; }
    #mainContent { margin-left: 0 !important; }
    #mainContent.collapsed { margin-left: 0 !important; }
</style>

<!-- Breadcrumb -->
<div class="mb-6 flex items-center gap-2 text-sm">
    <a href="{{ route('dashboards.super_admin') }}" class="text-brand hover:underline">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-slate-400"></i>
    <a href="{{ route('system.dashboard_mappings.index') }}" class="text-brand hover:underline">Dashboard Mappings</a>
    <i class="fa-solid fa-chevron-right text-slate-400"></i>
    <span class="text-slate-600">Create New</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Section -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
            <form action="{{ route('system.dashboard_mappings.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Role Selection -->
                <div>
                    <label for="role_id" class="block text-sm font-bold text-slate-900 mb-2">
                        <i class="fa-solid fa-shield text-brand mr-2"></i>
                        Select Role <span class="text-red-600">*</span>
                    </label>
                    <select id="role_id" name="role_id" required 
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-colors @error('role_id') border-red-500 @enderror">
                        <option value="">-- Choose a Role --</option>
                        @forelse ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </option>
                        @empty
                        <option value="" disabled>No roles available</option>
                        @endforelse
                    </select>
                    @error('role_id')
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-2">Total roles available: <strong>{{ $roles->count() }}</strong></p>
                </div>

                <!-- Dashboard Selection -->
                <div>
                    <label for="dashboard_name" class="block text-sm font-bold text-slate-900 mb-2">
                        <i class="fa-solid fa-window-maximize text-brand mr-2"></i>
                        Select Dashboard <span class="text-red-600">*</span>
                    </label>
                    <select id="dashboard_name" name="dashboard_name" required 
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-colors @error('dashboard_name') border-red-500 @enderror"
                            onchange="updateDashboardInfo()">
                        <option value="">-- Choose a Dashboard --</option>
                        @foreach ($dashboards as $key => $dashboard)
                        <option value="{{ $key }}" 
                                data-route="{{ $dashboard['route'] }}"
                                data-description="{{ $dashboard['description'] }}"
                                {{ old('dashboard_name') == $key ? 'selected' : '' }}>
                            {{ $dashboard['name'] }}
                        </option>
                        @endforeach
                    </select>
                    @error('dashboard_name')
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    <p class="text-sm text-slate-600 mt-2" id="dashboard_description">
                        Select a dashboard to see its description
                    </p>
                </div>

                <!-- Dashboard Route Selection -->
                <div>
                    <label for="dashboard_route" class="block text-sm font-bold text-slate-900 mb-2">
                        <i class="fa-solid fa-link text-brand mr-2"></i>
                        Dashboard Route <span class="text-red-600">*</span>
                    </label>
                    <select id="dashboard_route" name="dashboard_route" required 
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-colors font-mono text-sm @error('dashboard_route') border-red-500 @enderror">
                        <option value="">-- Select a Dashboard Route --</option>
                        @foreach ($dashboards as $key => $dashboard)
                        <option value="{{ $dashboard['route'] }}" {{ old('dashboard_route') == $dashboard['route'] ? 'selected' : '' }}>
                            {{ $dashboard['route'] }}
                        </option>
                        @endforeach
                    </select>
                    @error('dashboard_route')
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-2">Choose from available dashboard URLs</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-slate-900 mb-2">
                        <i class="fa-solid fa-note-sticky text-brand mr-2"></i>
                        Description (Optional)
                    </label>
                    <textarea id="description" name="description" rows="3" 
                              class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-colors resize-none @error('description') border-red-500 @enderror"
                              placeholder="Internal notes about this mapping...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-2">Internal notes for your reference</p>
                </div>

                <!-- Sort Order / Priority -->
                <div>
                    <label for="sort_order" class="block text-sm font-bold text-slate-900 mb-2">
                        <i class="fa-solid fa-arrow-up-arrow-down text-brand mr-2"></i>
                        Priority (Sort Order)
                    </label>
                    <input type="number" id="sort_order" name="sort_order" min="0" max="1000" 
                           value="{{ old('sort_order', 0) }}"
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-colors @error('sort_order') border-red-500 @enderror">
                    @error('sort_order')
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-2">Lower numbers = higher priority (for users with multiple roles)</p>
                </div>

                <!-- Active Status -->
                <div class="pt-4 border-t border-slate-200">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                               class="w-5 h-5 border border-slate-300 rounded accent-brand cursor-pointer">
                        <span class="text-sm font-medium text-slate-900">Active Mapping</span>
                    </label>
                    <p class="text-xs text-slate-600 mt-2 ml-8">Inactive mappings won't be used for routing</p>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-6 border-t border-slate-200">
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-brand text-white rounded-lg hover:bg-brand-dark transition-colors font-medium shadow-sm">
                        <i class="fa-solid fa-save text-sm"></i>
                        Create Mapping
                    </button>
                    <a href="{{ route('system.dashboard_mappings.index') }}" class="flex items-center gap-2 px-6 py-2.5 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors font-medium">
                        <i class="fa-solid fa-x text-sm"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <!-- Available Roles Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 sticky top-20 mb-4">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-users text-brand text-lg"></i>
                All Available Roles
            </h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @forelse ($roles as $role)
                <div class="p-2 rounded border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-colors">
                    <p class="font-semibold text-slate-900 text-xs">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">ID: {{ $role->id }}</p>
                </div>
                @empty
                <p class="text-xs text-slate-600 italic">No roles found</p>
                @endforelse
            </div>
            <p class="text-xs text-slate-600 mt-3 pt-3 border-t border-slate-200"><strong>Total:</strong> {{ $roles->count() }} roles</p>
        </div>

        <!-- Available Dashboards Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 sticky top-96">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-window-maximize text-brand text-lg"></i>
                All Available Dashboards
            </h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach ($dashboards as $key => $dashboard)
                <div class="p-3 rounded-lg border border-slate-200 hover:border-brand hover:bg-brand hover:bg-opacity-5 transition-colors">
                    <p class="font-semibold text-slate-900 text-xs">{{ $dashboard['name'] }}</p>
                    <code class="text-xs bg-slate-100 px-1.5 py-0.5 rounded text-slate-700 font-mono block mt-1.5 overflow-x-auto">{{ $dashboard['route'] }}</code>
                    <p class="text-xs text-slate-600 mt-1">{{ Str::limit($dashboard['description'], 60) }}</p>
                </div>
                @endforeach
            </div>
            <p class="text-xs text-slate-600 mt-3 pt-3 border-t border-slate-200"><strong>Total:</strong> {{ count($dashboards) }} dashboards</p>
        </div>
    </div>
</div>

<script>
const dashboards = @json($dashboards);

function updateDashboardInfo() {
    const select = document.getElementById('dashboard_name');
    const selected = select.options[select.selectedIndex];
    const route = selected.dataset.route;
    const description = selected.dataset.description;
    
    // Update the route field
    document.getElementById('dashboard_route').value = route || '';
    
    // Update description
    document.getElementById('dashboard_description').textContent = description || 'Select a dashboard to see its description';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateDashboardInfo();
});
</script>

@endsection
