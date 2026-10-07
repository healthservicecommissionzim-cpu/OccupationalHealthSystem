@extends('layouts.cos-dashboard')

@section('cos-content')
<div class="space-y-6">
    <!-- Case Header -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $case->reference_number }}</h2>
                <p class="text-gray-600 mt-1">{{ $case->first_name }} {{ $case->surname }}</p>
                <div class="flex gap-4 mt-3">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                        Stage: {{ $case->current_stage }}
                    </span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded text-sm">
                        Status: {{ $case->status }}
                    </span>
                    @if($isOverdue)
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded text-sm">
                            <i class="fas fa-exclamation-circle"></i> Overdue
                        </span>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Facility: {{ $case->facility?->name }}</p>
                <p class="text-sm text-gray-600 mt-1">Created: {{ $case->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Timeline Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Timeline Visualization -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Case Timeline</h3>
                <div class="space-y-4">
                    @foreach($timeline as $event)
                        <div class="timeline-item {{ $event['stage'] === $case->current_stage ? 'current' : '' }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $event['event'] }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $event['description'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">By: {{ $event['actor'] }}</p>
                                </div>
                                <div class="text-right text-xs text-gray-500">
                                    {{ $event['timestamp']->format('d M Y H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Monitoring Notes -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Monitoring Notes</h3>

                @can('add_monitoring_note')
                    <form method="POST" action="{{ route('cos.case.add_note', $case->id) }}" class="mb-6">
                        @csrf
                        <textarea name="content" placeholder="Add a governance observation..." required 
                                  class="w-full px-3 py-2 border rounded @error('content') border-red-500 @enderror"
                                  rows="3"></textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Add Note
                        </button>
                    </form>
                @endcan

                @if($case->monitoringNotes->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($case->monitoringNotes as $note)
                            <div class="p-3 bg-blue-50 border-l-4 border-blue-500 rounded">
                                <p class="text-sm text-gray-900">{{ $note->content }}</p>
                                <p class="text-xs text-gray-600 mt-2">
                                    By {{ $note->author->name }} on {{ $note->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No monitoring notes yet</p>
                @endif
            </div>
        </div>

        <!-- Actions Column -->
        <div class="space-y-4">
            <!-- Current Holder -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h4 class="font-semibold text-gray-900 mb-3">Current Holder</h4>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-600">Name:</span>
                        <p class="font-semibold">{{ $currentHolder['holder_name'] }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Designation:</span>
                        <p>{{ $currentHolder['holder_designation'] }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Duration:</span>
                        <p class="font-semibold">{{ $currentHolder['duration_at_stage_days'] }} days</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white p-4 rounded-lg shadow space-y-2">
                <h4 class="font-semibold text-gray-900 mb-3">Actions</h4>

                @can('send_reminder')
                    <form method="POST" action="{{ route('cos.case.send_nudge', $case->id) }}">
                        @csrf
                        <button type="submit" class="w-full bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600 text-sm">
                            <i class="fas fa-bell"></i> Send Nudge
                        </button>
                    </form>
                @endcan

                @can('escalate_case')
                    <button onclick="document.getElementById('escalateModal').style.display='block'" 
                            class="w-full bg-purple-600 text-white px-3 py-2 rounded hover:bg-purple-700 text-sm">
                        <i class="fas fa-arrow-up"></i> Escalate
                    </button>
                @endcan

                @can('run_compliance_checklist')
                    <a href="{{ route('cos.compliance.show', $case->id) }}" 
                       class="block text-center bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 text-sm">
                        <i class="fas fa-clipboard-list"></i> Run Checklist
                    </a>
                @endcan

                @can('view_audit_log')
                    <a href="{{ route('cos.audit.show', $case->id) }}" 
                       class="block text-center bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 text-sm">
                        <i class="fas fa-history"></i> Audit Log
                    </a>
                @endcan
            </div>

            <!-- Case Information -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h4 class="font-semibold text-gray-900 mb-3">Case Details</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type:</span>
                        <span class="font-semibold">{{ $case->case_type ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Created:</span>
                        <span>{{ $case->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Updated:</span>
                        <span>{{ $case->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Escalate Modal -->
    @can('escalate_case')
        <div id="escalateModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Escalate Case</h3>
                <form method="POST" action="{{ route('cos.case.escalate', $case->id) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Next Stage</label>
                            <select name="next_stage" required class="w-full px-3 py-2 border rounded">
                                <option value="">Select stage...</option>
                                <option value="pmd_review">PMD Review</option>
                                <option value="hsc_resolution">HSC Resolution</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Holder User</label>
                            <select name="next_holder_id" required class="w-full px-3 py-2 border rounded">
                                <option value="">Select user...</option>
                                <!-- Users would be populated from database -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                            <textarea name="reason" class="w-full px-3 py-2 border rounded" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Escalate
                        </button>
                        <button type="button" onclick="document.getElementById('escalateModal').style.display='none'" 
                                class="flex-1 bg-gray-300 text-gray-900 px-4 py-2 rounded hover:bg-gray-400">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endcan
</div>
@endsection
