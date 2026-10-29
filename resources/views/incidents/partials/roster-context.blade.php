{{-- Roster Context Partial - Displays rotational staff assignment information --}}

@if($incident->is_rotational_staff && $incident->rosterAssignment)
<div class="roster-context-card">
    <div class="roster-context-header">
        <div class="roster-context-title">
            <i class="fa-solid fa-building-user"></i>
            <span>Rotational Staff Assignment Context</span>
        </div>
        <div class="roster-badge">
            <i class="fa-solid fa-route"></i> Rotational Assignment
        </div>
    </div>
    
    <div class="roster-context-body">
        <div class="roster-info-grid">
            <div class="roster-field">
                <label class="roster-label">Worker's Primary Facility</label>
                <div class="roster-value">
                    <i class="fa-solid fa-hospital"></i>
                    <span>{{ $incident->workerPrimaryFacility->name ?? 'N/A' }}</span>
                </div>
                <p class="roster-hint">Home base facility for this staff member</p>
            </div>

            <div class="roster-field">
                <label class="roster-label">Cost Center Facility</label>
                <div class="roster-value">
                    <i class="fa-solid fa-dollar-sign"></i>
                    <span>{{ $incident->costCenterFacility->name ?? 'N/A' }}</span>
                </div>
                <p class="roster-hint">Facility financially responsible for this incident</p>
            </div>

            <div class="roster-field">
                <label class="roster-label">Assignment Type</label>
                <div class="roster-value">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>{{ ucwords(str_replace('_', ' ', $incident->rosterAssignment->assignment_type ?? 'N/A')) }}</span>
                </div>
                <p class="roster-hint">Type of temporary assignment</p>
            </div>

            <div class="roster-field">
                <label class="roster-label">Assignment Period</label>
                <div class="roster-value">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>
                        @if($incident->rosterAssignment)
                            {{ $incident->rosterAssignment->assignment_start_date->format('d M Y') }} 
                            - 
                            {{ $incident->rosterAssignment->assignment_end_date->format('d M Y') }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <p class="roster-hint">Duration of rotational assignment</p>
            </div>
        </div>

        <div class="roster-note">
            <i class="fa-solid fa-info-circle"></i>
            <p>This incident occurred during a rotational assignment. The worker's primary facility is <strong>{{ $incident->workerPrimaryFacility->name ?? 'N/A' }}</strong>, but the cost is attributed to <strong>{{ $incident->costCenterFacility->name ?? 'N/A' }}</strong> as per the assignment agreement.</p>
        </div>
    </div>
</div>

<style>
.roster-context-card {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 2px solid #0ea5e9;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15);
}

.roster-context-header {
    background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
    padding: 14px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #0284c7;
}

.roster-context-title {
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.roster-context-title i {
    font-size: 16px;
}

.roster-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.4);
    padding: 6px 12px;
    border-radius: 6px;
    color: white;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.roster-context-body {
    padding: 20px;
}

.roster-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 18px;
}

@media (max-width: 768px) {
    .roster-info-grid {
        grid-template-columns: 1fr;
    }
}

.roster-field {
    background: white;
    padding: 14px;
    border-radius: 8px;
    border: 1px solid #e0f2fe;
    box-shadow: 0 2px 6px rgba(14, 165, 233, 0.08);
}

.roster-label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    color: #0369a1;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.roster-value {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #0c4a6e;
    margin-bottom: 6px;
}

.roster-value i {
    color: #0284c7;
    font-size: 14px;
    width: 18px;
    text-align: center;
}

.roster-hint {
    font-size: 10px;
    color: #64748b;
    margin: 0;
    line-height: 1.4;
    font-style: italic;
}

.roster-note {
    background: white;
    border: 1px solid #bae6fd;
    border-left: 4px solid #0284c7;
    border-radius: 8px;
    padding: 14px 16px;
    display: flex;
    gap: 12px;
    align-items: start;
}

.roster-note i {
    color: #0284c7;
    font-size: 16px;
    flex-shrink: 0;
    margin-top: 2px;
}

.roster-note p {
    margin: 0;
    font-size: 12px;
    color: #475569;
    line-height: 1.6;
}

.roster-note strong {
    color: #0c4a6e;
    font-weight: 700;
}
</style>
@endif
