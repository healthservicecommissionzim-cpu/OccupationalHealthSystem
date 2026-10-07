@props(['incident'])

<div class="leave-information-section bg-white rounded-lg shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-6">
            <h3 class="text-lg font-semibold text-slate-800">
                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                Leave Information Management
            </h3>
            <!-- Days Elapsed -->
            <div class="bg-blue-50 px-4 py-2 rounded-lg">
                <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Days Elapsed</p>
                <p class="text-xl font-bold text-blue-700" id="daysElapsedDisplay">—</p>
            </div>
        </div>
        @if($incident->leave_information_saved_at)
            <span class="text-xs font-medium text-green-700 bg-green-50 px-3 py-1 rounded-full">
                <i class="fas fa-check-circle mr-1"></i>
                Saved {{ $incident->leave_information_saved_at->format('M d, Y H:i') }}
            </span>
        @endif
    </div>

    <form id="leaveInformationForm" class="space-y-4">
        @csrf

        <!-- Sick Leave Start Date -->
        <div class="form-group">
            <label for="sick_leave_start_date" class="block text-sm font-medium text-slate-700 mb-2">
                Sick Leave Start Date
                <span class="text-red-500">*</span>
            </label>
            <input 
                type="date" 
                id="sick_leave_start_date" 
                name="sick_leave_start_date"
                value="{{ $incident->sick_leave_start_date?->format('Y-m-d') }}"
                min="{{ $incident->incident_date?->format('Y-m-d') }}"
                max="{{ now()->format('Y-m-d') }}"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
            >
            <small class="text-slate-500 mt-1 block">Must be between incident date ({{ $incident->incident_date?->format('M d, Y') }}) and today</small>
            <div class="error-text text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <!-- Medical Certificate Provided -->
        <div class="form-group">
            <label for="medical_certificate_provided" class="flex items-center space-x-3 cursor-pointer">
                <input 
                    type="checkbox" 
                    id="medical_certificate_provided" 
                    name="medical_certificate_provided"
                    value="1"
                    @checked($incident->medical_certificate_provided)
                    class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-2 focus:ring-blue-500"
                >
                <span class="text-sm font-medium text-slate-700">Medical Certificate Provided</span>
            </label>
            <small class="text-slate-500 mt-2 block ml-7">
                Check this if a valid medical certificate from a registered doctor has been provided
            </small>
        </div>

        <!-- Expected Recovery Date (Conditional) -->
        <div id="recoveryDateGroup" class="form-group hidden">
            <label for="expected_recovery_date" class="block text-sm font-medium text-slate-700 mb-2">
                Expected Recovery Date
                <span class="text-red-500" id="recoveryRequired">*</span>
            </label>
            <input 
                type="date" 
                id="expected_recovery_date" 
                name="expected_recovery_date"
                value="{{ $incident->expected_recovery_date?->format('Y-m-d') }}"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
            <small class="text-slate-500 mt-1 block">Must be after leave start date and within 365 days</small>
            <div class="error-text text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <!-- Read-Only Fields Display - REMOVED Payment Stage -->
        <!-- Payment stage removed per user request -->

        <!-- Milestones Reached -->
        @if($incident->leaveMilestones()->exists())
            <div class="mt-6 pt-4 border-t border-slate-200">
                <p class="text-sm font-semibold text-slate-700 mb-3">Milestones Reached</p>
                <div class="space-y-2">
                    @foreach($incident->leaveMilestones as $milestone)
                        <div class="flex items-start space-x-3 bg-amber-50 p-3 rounded-lg">
                            <i class="fas fa-flag text-amber-600 mt-1"></i>
                            <div>
                                <p class="text-sm font-medium text-slate-800">
                                    {{ $milestone->milestone_type === '90_DAY' ? '90-Day' : '180-Day' }} Milestone
                                </p>
                                <p class="text-xs text-slate-600 mt-1">
                                    Flagged on {{ $milestone->applied_at->format('M d, Y H:i') }} 
                                    ({{ $milestone->days_elapsed_at_flag }} days elapsed)
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Form Actions -->
        <div class="flex gap-3 mt-6 pt-4 border-t border-slate-200">
            <button 
                type="submit" 
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                id="saveBtn"
            >
                <i class="fas fa-save mr-2"></i>
                Save Leave Information
            </button>
            <button 
                type="reset" 
                class="px-4 py-2 border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium rounded-lg transition-colors duration-200"
            >
                Clear
            </button>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden text-center py-4">
            <div class="inline-block">
                <i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i>
                <p class="text-slate-600 text-sm mt-2">Saving leave information...</p>
            </div>
        </div>

        <!-- Error Alert -->
        <div id="errorAlert" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                <div>
                    <p class="font-semibold text-red-800" id="errorTitle">Error</p>
                    <p class="text-red-700 text-sm mt-1" id="errorMessage"></p>
                </div>
            </div>
        </div>

        <!-- Success Alert -->
        <div id="successAlert" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                <div>
                    <p class="font-semibold text-green-800">Success</p>
                    <p class="text-green-700 text-sm mt-1">Leave information has been saved successfully</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('leaveInformationForm');
    const medicalCertCheckbox = document.getElementById('medical_certificate_provided');
    const recoveryDateGroup = document.getElementById('recoveryDateGroup');
    const recoveryDateInput = document.getElementById('expected_recovery_date');
    const incidentDate = new Date('{{ $incident->incident_date?->format("Y-m-d") }}');
    
    // Toggle recovery date field based on medical certificate checkbox
    medicalCertCheckbox.addEventListener('change', function() {
        if (this.checked) {
            recoveryDateGroup.classList.remove('hidden');
            recoveryDateInput.required = true;
        } else {
            recoveryDateGroup.classList.add('hidden');
            recoveryDateInput.required = false;
            recoveryDateInput.value = '';
        }
    });
    
    // Trigger on page load to show/hide recovery date field
    medicalCertCheckbox.dispatchEvent(new Event('change'));
    
    // Calculate days elapsed on leave start date change
    document.getElementById('sick_leave_start_date').addEventListener('change', function() {
        if (this.value) {
            const startDate = new Date(this.value);
            const today = new Date();
            const daysElapsed = Math.floor((today - startDate) / (1000 * 60 * 60 * 24));
            document.getElementById('daysElapsedDisplay').textContent = daysElapsed + ' days';
        }
    });
    
    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        document.getElementById('saveBtn').disabled = true;
        document.getElementById('loadingState').classList.remove('hidden');
        document.getElementById('errorAlert').classList.add('hidden');
        document.getElementById('successAlert').classList.add('hidden');
        
        try {
            const response = await fetch(`/api/incidents/{{ $incident->id }}/leave-information`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify(data),
            });
            
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to save leave information');
            }
            
            document.getElementById('successAlert').classList.remove('hidden');
            setTimeout(() => {
                location.reload();
            }, 2000);
            
        } catch (error) {
            document.getElementById('errorMessage').textContent = error.message;
            document.getElementById('errorAlert').classList.remove('hidden');
        } finally {
            document.getElementById('saveBtn').disabled = false;
            document.getElementById('loadingState').classList.add('hidden');
        }
    });
});
</script>
