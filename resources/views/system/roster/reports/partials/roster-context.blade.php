{{-- Roster Context Partial for Reports --}}
{{-- Displays expandable roster assignment context for rotational incidents --}}

<div class="bg-white border border-slate-200 rounded-lg p-6">
    <h4 class="text-sm font-semibold text-slate-700 mb-4 flex items-center">
        <i class="fas fa-info-circle text-purple-600 mr-2"></i>
        Roster Assignment Context
    </h4>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Assignment Type -->
        <div class="bg-slate-50 rounded-lg p-4">
            <p class="text-xs font-medium text-slate-600 mb-1">Assignment Type</p>
            <p class="text-sm font-semibold text-slate-800">
                @if($context['assignment_type'] === 'rotation')
                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                        <i class="fas fa-sync-alt mr-1"></i>Rotation
                    </span>
                @elseif($context['assignment_type'] === 'locum_contract')
                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs">
                        <i class="fas fa-briefcase mr-1"></i>Locum Contract
                    </span>
                @elseif($context['assignment_type'] === 'student_placement')
                    <span class="inline-block px-2 py-1 bg-orange-100 text-orange-800 rounded text-xs">
                        <i class="fas fa-graduation-cap mr-1"></i>Student Placement
                    </span>
                @else
                    <span class="text-slate-500">{{ ucfirst(str_replace('_', ' ', $context['assignment_type'])) }}</span>
                @endif
            </p>
        </div>

        <!-- Primary Facility -->
        <div class="bg-slate-50 rounded-lg p-4">
            <p class="text-xs font-medium text-slate-600 mb-1">Worker's Primary Facility</p>
            <p class="text-sm font-semibold text-slate-800">
                <i class="fas fa-home text-slate-600 mr-1"></i>
                {{ $context['primary_facility_name'] }}
            </p>
        </div>

        <!-- Assignment Date Range -->
        <div class="bg-slate-50 rounded-lg p-4">
            <p class="text-xs font-medium text-slate-600 mb-1">Assignment Date Range</p>
            <p class="text-sm font-semibold text-slate-800">
                <i class="fas fa-calendar-alt text-slate-600 mr-1"></i>
                {{ $context['assignment_start_date']->format('M d, Y') }}
                <span class="text-slate-500 mx-1">to</span>
                {{ $context['assignment_end_date']->format('M d, Y') }}
            </p>
            <p class="text-xs text-slate-500 mt-1">
                Duration: {{ $context['assignment_start_date']->diffInDays($context['assignment_end_date']) }} days
            </p>
        </div>

        <!-- Cost Center Facility -->
        <div class="bg-slate-50 rounded-lg p-4">
            <p class="text-xs font-medium text-slate-600 mb-1">Cost Center Facility</p>
            <p class="text-sm font-semibold text-slate-800">
                <i class="fas fa-dollar-sign text-slate-600 mr-1"></i>
                {{ $context['cost_center_facility_name'] }}
            </p>
            <p class="text-xs text-slate-500 mt-1">Financial responsibility</p>
        </div>
    </div>

    @if(isset($context['notes']) && $context['notes'])
        <div class="mt-4 pt-4 border-t border-slate-200">
            <p class="text-xs font-medium text-slate-600 mb-1">Assignment Notes</p>
            <p class="text-sm text-slate-700">{{ $context['notes'] }}</p>
        </div>
    @endif
</div>
