@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
                <h1 class="text-[20px] font-bold text-navy">Compliance Checklist Review</h1>
                <p class="text-[12px] text-slate-500 mt-1">{{ $incident->reference_number }} • {{ $incident->employee?->name ?? 'N/A' }}</p>
            </div>
            <a href="{{ route('incidents.hr_pending_review') }}" class="text-[12px] font-medium text-blue-600 hover:text-blue-700">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto p-6">
        <form id="complianceForm" method="POST" action="{{ route('incidents.submit_compliance', $incident) }}">
            @csrf

            <!-- Instruction Banner -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-[12px] text-blue-900">
                    <i class="fa-solid fa-info-circle mr-2"></i>
                    <strong>HSB Circular No. 3 of 2017 Compliance:</strong> Please review all mandatory documents below. Each document must be marked as either Accepted or Rejected. The "Finalize Compliance & Escalate" button will only be enabled once all documents are explicitly marked as Accepted.
                </p>
            </div>

            <!-- Compliance Grid -->
            <div class="space-y-3 mb-8">
                @php
                    $documentCount = 0;
                    $requiredCount = count($mandatoryDocuments);
                @endphp

                @foreach($mandatoryDocuments as $key => $label)
                @php $documentCount++; @endphp
                <div class="bg-white border border-slate-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-slate-100 rounded-full text-[11px] font-bold text-slate-700">
                                    {{ $documentCount }}
                                </span>
                                <h3 class="text-[13px] font-semibold text-navy">{{ $label }}</h3>
                            </div>
                            <p class="text-[11px] text-slate-500">Required per HSB Circular No. 3 of 2017 and NSSA guidelines</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Accept Button -->
                            <button type="button" class="accept-btn px-4 py-2 bg-emerald-600 text-white rounded-lg text-[11px] font-semibold hover:bg-emerald-700 transition-colors" data-key="{{ $key }}" data-label="{{ $label }}">
                                <i class="fa-solid fa-check mr-1"></i>Accept
                            </button>

                            <!-- Reject Button -->
                            <button type="button" class="reject-btn px-4 py-2 bg-red-600 text-white rounded-lg text-[11px] font-semibold hover:bg-red-700 transition-colors" data-key="{{ $key }}" data-label="{{ $label }}">
                                <i class="fa-solid fa-times mr-1"></i>Reject
                            </button>
                        </div>
                    </div>

                    <!-- Status Indicator -->
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <div class="status-indicator" data-key="{{ $key }}">
                            <span class="text-[11px] text-slate-500">Status: <span class="font-semibold text-slate-700 status-text">Pending</span></span>
                        </div>
                    </div>

                    <!-- Hidden Inputs -->
                    <input type="hidden" name="compliance_decisions[{{ $key }}][status]" class="status-input" value="">
                    <input type="hidden" name="compliance_decisions[{{ $key }}][reason]" class="reason-input" value="">
                </div>
                @endforeach
            </div>

            <!-- Submit Button -->
            <div class="flex gap-3">
                <button type="submit" id="finalizeBtn" disabled class="px-6 py-3 bg-blue-600 text-white rounded-lg text-[13px] font-semibold hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                    <i class="fa-solid fa-check-double"></i>Finalize Compliance & Escalate
                </button>
                <a href="{{ route('incidents.hr_pending_review') }}" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg text-[13px] font-semibold hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div id="rejectionModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full mx-4">
        <h2 class="text-[16px] font-bold text-navy mb-2">Rejection Reason Required</h2>
        <p class="text-[12px] text-slate-600 mb-4">Please provide a reason for rejecting: <span id="rejectingLabel" class="font-semibold"></span></p>

        <form id="rejectionForm">
            <textarea id="rejectionReason" class="w-full border border-slate-300 rounded-lg p-3 text-[12px] focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter correction reason..." rows="4" maxlength="500"></textarea>
            <p class="text-[10px] text-slate-500 mt-1"><span id="charCount">0</span>/500 characters</p>

            <div class="flex gap-2 mt-4">
                <button type="button" id="confirmRejectBtn" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-[12px] font-semibold hover:bg-red-700">
                    Confirm Rejection
                </button>
                <button type="button" id="cancelRejectBtn" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-[12px] font-semibold hover:bg-slate-50">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let currentRejectKey = null;
    let currentRejectLabel = null;
    let complianceStatus = {};
    const totalDocuments = {{ $requiredCount }};

    // Initialize all documents as pending
    @foreach($mandatoryDocuments as $key => $label)
        complianceStatus['{{ $key }}'] = 'PENDING';
    @endforeach

    document.addEventListener('DOMContentLoaded', function() {
        // Accept buttons
        document.querySelectorAll('.accept-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const key = this.dataset.key;
                const label = this.dataset.label;
                
                complianceStatus[key] = 'ACCEPTED';
                updateDocumentUI(key, 'ACCEPTED');
                updateFinalizeButton();
            });
        });

        // Reject buttons
        document.querySelectorAll('.reject-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const key = this.dataset.key;
                const label = this.dataset.label;
                
                currentRejectKey = key;
                currentRejectLabel = label;
                document.getElementById('rejectingLabel').textContent = label;
                document.getElementById('rejectionReason').value = '';
                document.getElementById('charCount').textContent = '0';
                document.getElementById('rejectionModal').classList.remove('hidden');
            });
        });

        // Character count for rejection reason
        document.getElementById('rejectionReason').addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });

        // Confirm rejection
        document.getElementById('confirmRejectBtn').addEventListener('click', function() {
            const reason = document.getElementById('rejectionReason').value.trim();
            
            if (!reason) {
                alert('Please provide a reason for rejection');
                return;
            }

            complianceStatus[currentRejectKey] = 'REJECTED';
            updateDocumentUI(currentRejectKey, 'REJECTED', reason);
            document.getElementById('rejectionModal').classList.add('hidden');
            updateFinalizeButton();
        });

        // Cancel rejection
        document.getElementById('cancelRejectBtn').addEventListener('click', function() {
            document.getElementById('rejectionModal').classList.add('hidden');
            currentRejectKey = null;
            currentRejectLabel = null;
        });

        // Handle form submission
        document.getElementById('complianceForm').addEventListener('submit', function(e) {
            // Verify all documents are accepted before submitting
            const allAccepted = Object.values(complianceStatus).every(status => status === 'ACCEPTED');
            if (!allAccepted) {
                e.preventDefault();
                alert('All documents must be marked as Accepted before finalizing.');
                return;
            }
        });
    });

    function updateDocumentUI(key, status, reason = null) {
        const form = document.querySelector(`input[name="compliance_decisions[${key}][status]"]`).closest('.bg-white');
        const statusInput = form.querySelector('.status-input');
        const reasonInput = form.querySelector('.reason-input');
        const statusText = form.querySelector('.status-text');
        const statusIndicator = form.querySelector('.status-indicator');

        statusInput.value = status;
        if (reason) {
            reasonInput.value = reason;
        }

        // Update visual feedback
        if (status === 'ACCEPTED') {
            statusIndicator.innerHTML = '<span class="text-[11px] text-emerald-700 font-semibold"><i class="fa-solid fa-check mr-1"></i>Accepted</span>';
            statusIndicator.className = 'status-indicator p-2 bg-emerald-50 rounded border border-emerald-200';
        } else if (status === 'REJECTED') {
            statusIndicator.innerHTML = `<div class="text-[11px] text-red-700 font-semibold"><i class="fa-solid fa-times mr-1"></i>Rejected</div><div class="text-[10px] text-red-600 mt-1">Reason: ${reason}</div>`;
            statusIndicator.className = 'status-indicator p-2 bg-red-50 rounded border border-red-200';
        }
    }

    function updateFinalizeButton() {
        const allAccepted = Object.values(complianceStatus).every(status => status === 'ACCEPTED');
        document.getElementById('finalizeBtn').disabled = !allAccepted;
    }
</script>
@endsection
