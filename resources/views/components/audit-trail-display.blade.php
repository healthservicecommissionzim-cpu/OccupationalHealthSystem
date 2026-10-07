@props(['incident'])

@php
    $auditTrail = \App\Models\AuditLog::where('incident_id', $incident->id)
        ->whereIn('action', ['leave_information_saved', 'milestone_flag_applied', 'medical_board_decision'])
        ->orderBy('created_at', 'desc')
        ->get();
@endphp

@if($auditTrail->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">
            <i class="fas fa-history mr-2 text-slate-600"></i>
            Audit Trail
        </h2>

        <div class="space-y-4">
            @foreach($auditTrail as $entry)
                <div class="border-l-4 border-blue-600 pl-4 py-3 bg-slate-50 rounded-r">
                    <!-- Action Header -->
                    <div class="flex items-start justify-between mb-2">
                        <p class="font-semibold text-slate-800">
                            @switch($entry->action)
                                @case('leave_information_saved')
                                    <i class="fas fa-file-alt text-green-600 mr-2"></i>Leave Information Saved
                                    @break
                                @case('milestone_flag_applied')
                                    <i class="fas fa-flag text-amber-600 mr-2"></i>Milestone Flag Applied
                                    @break
                                @case('medical_board_decision')
                                    <i class="fas fa-clipboard-check text-blue-600 mr-2"></i>Medical Board Decision
                                    @break
                                @default
                                    {{ ucwords(str_replace('_', ' ', $entry->action)) }}
                            @endswitch
                        </p>
                        <span class="text-xs text-slate-600">
                            {{ $entry->created_at->format('M d, Y H:i') }}
                        </span>
                    </div>

                    <!-- User Info -->
                    <p class="text-sm text-slate-600 mb-3">
                        By: <span class="font-medium">{{ $entry->changed_by_user_id ? $entry->changedByUser?->name : 'System (Automated)' }}</span>
                    </p>

                    <!-- Changed Fields -->
                    @if($entry->old_values || $entry->new_values)
                        <div class="space-y-2 mt-3 text-sm">
                            @php
                                $oldValues = json_decode($entry->old_values, true) ?? [];
                                $newValues = json_decode($entry->new_values, true) ?? [];
                            @endphp

                            @foreach($newValues as $field => $newValue)
                                @php
                                    $oldValue = $oldValues[$field] ?? null;
                                    $fieldLabel = ucwords(str_replace('_', ' ', $field));
                                @endphp

                                @if($oldValue !== $newValue)
                                    <div class="grid grid-cols-3 gap-3">
                                        <div class="text-slate-600">{{ $fieldLabel }}:</div>
                                        <div class="bg-red-50 px-2 py-1 rounded text-red-800">
                                            {{ $oldValue ?? '(empty)' }}
                                        </div>
                                        <div class="bg-green-50 px-2 py-1 rounded text-green-800">
                                            {{ $newValue ?? '(empty)' }}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <!-- Metadata (Reason/Decision Details) -->
                    @if($entry->metadata)
                        @php
                            $metadata = json_decode($entry->metadata, true) ?? [];
                        @endphp

                        @if(!empty($metadata))
                            <div class="mt-3 bg-blue-50 border border-blue-200 rounded p-3 text-sm">
                                @if(isset($metadata['decision']))
                                    <p class="text-slate-700">
                                        <span class="font-medium">Decision:</span>
                                        {{ ucwords(str_replace('_', ' ', $metadata['decision'])) }}
                                    </p>
                                @endif

                                @if(isset($metadata['decision_reason']))
                                    <p class="text-slate-700 mt-1">
                                        <span class="font-medium">Reason:</span>
                                        {{ $metadata['decision_reason'] }}
                                    </p>
                                @endif

                                @if(isset($metadata['flag_reason']))
                                    <p class="text-slate-700">
                                        <span class="font-medium">Flag Reason:</span>
                                        {{ str_replace('_', ' ', $metadata['flag_reason']) }}
                                    </p>
                                @endif

                                @if(isset($metadata['decided_by']))
                                    <p class="text-slate-600 text-xs mt-1">
                                        Decided by: {{ $metadata['decided_by'] }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="bg-slate-50 rounded-lg p-6 text-center">
        <i class="fas fa-info-circle text-slate-400 text-2xl mb-2 block"></i>
        <p class="text-slate-600">No leave-related audit history</p>
    </div>
@endif
