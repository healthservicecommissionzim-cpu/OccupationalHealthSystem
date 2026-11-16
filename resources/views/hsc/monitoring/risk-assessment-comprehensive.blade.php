@extends('layouts.app')

@section('title', 'AI-Powered Risk Assessment & Predictive Safety')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; padding: 24px;">
    
    {{-- Page Header --}}
    <div style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div>
                <h1 style="font-size: 26px; font-weight: 700; color: #101a31; margin: 0;">
                    <i class="fas fa-shield-alt" style="color: #dc2626; margin-right: 8px;"></i>
                    Risk Assessment & Predictive Safety
                </h1>
                <p style="color: #64748b; margin: 4px 0 0 0; font-size: 14px;">
                    AI-driven hazard analysis powered by historical IOD/DOD incident data
                </p>
            </div>
            <button onclick="openCreateAssessmentModal()" style="background: #dc2626; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">
                <i class="fas fa-plus"></i> New Risk Assessment
            </button>
        </div>
    </div>

    {{-- Top KPI & Predictive Banner --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        
        {{-- Total Assessments --}}
        <div style="background: white; padding: 16px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clipboard-list" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <div style="font-size: 28px; font-weight: 700; color: #101a31;">{{ $totalAssessments }}</div>
                    <div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;">Total Assessments</div>
                </div>
            </div>
        </div>

        {{-- High Risk Count --}}
        <div style="background: white; padding: 16px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation-triangle" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <div style="font-size: 28px; font-weight: 700; color: #dc2626;">{{ $highRiskCount }}</div>
                    <div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;">High Risk Hazards</div>
                </div>
            </div>
        </div>

        {{-- AI Escalation Warnings --}}
        <div style="background: white; padding: 16px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-robot" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <div style="font-size: 28px; font-weight: 700; color: #f59e0b;">{{ $escalationWarnings }}</div>
                    <div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;">AI Escalation Warnings</div>
                </div>
            </div>
        </div>

        {{-- Linked Incidents (30d) --}}
        <div style="background: white; padding: 16px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-link" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <div style="font-size: 28px; font-weight: 700; color: #8b5cf6;">{{ $totalLinkedIncidents30d }}</div>
                    <div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;">Incidents (30 Days)</div>
                </div>
            </div>
        </div>

        {{-- DOD Total --}}
        <div style="background: white; padding: 16px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 2px solid #dc2626;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-skull-crossbones" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <div style="font-size: 28px; font-weight: 700; color: #dc2626;">{{ $totalLinkedDODs }}</div>
                    <div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;">Death on Duty (Total)</div>
                </div>
            </div>
        </div>

        {{-- Overdue Actions --}}
        <div style="background: white; padding: 16px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <div style="font-size: 28px; font-weight: 700; color: #ef4444;">{{ $overdueActions }}</div>
                    <div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;">Overdue CAPAs</div>
                </div>
            </div>
        </div>

    </div>

    {{-- Predictive Insights Banner --}}
    @if($aiPredictedHighRisks->count() > 0)
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 20px; border-radius: 12px; margin-bottom: 24px; color: white; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.3);">
        <div style="display: flex; align-items: start; gap: 16px;">
            <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas fa-brain" style="font-size: 28px;"></i>
            </div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 700;">
                    ⚠️ AI Predictive Alert: {{ $aiPredictedHighRisks->count() }} Location(s) Show Elevated Risk
                </h3>
                <p style="margin: 0; font-size: 14px; line-height: 1.6; opacity: 0.95;">
                    Our predictive analytics engine has detected {{ $aiPredictedHighRisks->count() }} location(s) where AI-calculated risk scores are 
                    <strong>higher than manual assessments</strong>, based on historical incident patterns. These areas may require immediate review 
                    and enhanced controls despite current manual ratings.
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- Filters --}}
    <div style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form method="GET" action="{{ route('hsc.risk.assessment') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; align-items: end;">
            
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 6px;">FACILITY</label>
                <select name="facility_id" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">All Facilities</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ $selectedFacility == $facility->id ? 'selected' : '' }}>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 6px;">HAZARD CATEGORY</label>
                <select name="hazard_category" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">All Categories</option>
                    @foreach($hazardCategories as $category)
                        <option value="{{ $category }}" {{ $selectedCategory == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 6px;">STATUS</label>
                <select name="status" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">All Statuses</option>
                    <option value="ACTIVE" {{ $selectedStatus == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                    <option value="NEEDS_REVIEW" {{ $selectedStatus == 'NEEDS_REVIEW' ? 'selected' : '' }}>Needs Review</option>
                    <option value="EXPIRED" {{ $selectedStatus == 'EXPIRED' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <button type="submit" style="background: #3b82f6; color: white; padding: 10px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">
                <i class="fas fa-filter"></i> Apply Filters
            </button>

        </form>
    </div>

    {{-- Interactive 5x5 Risk Matrix --}}
    <div style="background: white; padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; color: #101a31;">
            <i class="fas fa-th" style="color: #3b82f6; margin-right: 8px;"></i>
            Interactive 5×5 Risk Matrix
        </h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 8px;">
                <thead>
                    <tr>
                        <th style="padding: 12px; text-align: center; font-size: 12px; font-weight: 700; color: #64748b;"></th>
                        <th style="padding: 12px; text-align: center; font-size: 12px; font-weight: 700; color: #64748b;">RARE (1)</th>
                        <th style="padding: 12px; text-align: center; font-size: 12px; font-weight: 700; color: #64748b;">UNLIKELY (2)</th>
                        <th style="padding: 12px; text-align: center; font-size: 12px; font-weight: 700; color: #64748b;">POSSIBLE (3)</th>
                        <th style="padding: 12px; text-align: center; font-size: 12px; font-weight: 700; color: #64748b;">LIKELY (4)</th>
                        <th style="padding: 12px; text-align: center; font-size: 12px; font-weight: 700; color: #64748b;">ALMOST CERTAIN (5)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riskMatrix as $severity => $likelihoodRow)
                        <tr>
                            <td style="padding: 12px; text-align: center; font-size: 12px; font-weight: 700; color: #64748b;">
                                @if($severity == 5) CATASTROPHIC (5)
                                @elseif($severity == 4) MAJOR (4)
                                @elseif($severity == 3) MODERATE (3)
                                @elseif($severity == 2) MINOR (2)
                                @else NEGLIGIBLE (1)
                                @endif
                            </td>
                            @foreach($likelihoodRow as $likelihood => $cell)
                                @php
                                    $bgColor = '#10b981'; // Green - Low
                                    if ($cell['level'] === 'CRITICAL') $bgColor = '#dc2626'; // Red
                                    elseif ($cell['level'] === 'HIGH') $bgColor = '#f59e0b'; // Orange
                                    elseif ($cell['level'] === 'MEDIUM') $bgColor = '#eab308'; // Yellow
                                @endphp
                                <td style="padding: 16px; text-align: center; background: {{ $bgColor }}; color: white; border-radius: 8px; cursor: pointer; position: relative;">
                                    <div style="font-size: 20px; font-weight: 700;">{{ $cell['score'] }}</div>
                                    <div style="font-size: 11px; margin-top: 4px;">
                                        {{ $cell['residual_count'] }} Current
                                    </div>
                                    @if($cell['predicted_count'] > 0)
                                        <div style="font-size: 10px; margin-top: 2px; background: rgba(255,255,255,0.3); padding: 2px 6px; border-radius: 4px; display: inline-block;">
                                            <i class="fas fa-robot"></i> {{ $cell['predicted_count'] }} AI Predicted
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; gap: 24px; flex-wrap: wrap; font-size: 12px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 16px; height: 16px; background: #dc2626; border-radius: 4px;"></div>
                <span style="color: #64748b; font-weight: 600;">Critical (20-25)</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 16px; height: 16px; background: #f59e0b; border-radius: 4px;"></div>
                <span style="color: #64748b; font-weight: 600;">High (15-19)</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 16px; height: 16px; background: #eab308; border-radius: 4px;"></div>
                <span style="color: #64748b; font-weight: 600;">Medium (10-14)</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 16px; height: 16px; background: #10b981; border-radius: 4px;"></div>
                <span style="color: #64748b; font-weight: 600;">Low (5-9)</span>
            </div>
        </div>
    </div>

    {{-- Hazard & Controls Table --}}
    <div style="background: white; padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #101a31;">
                <i class="fas fa-list-ul" style="color: #3b82f6; margin-right: 8px;"></i>
                Risk Assessments with AI Predictions
            </h3>
            <input type="text" id="searchTable" placeholder="Search assessments..." style="padding: 8px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; width: 300px;">
        </div>

        <div style="overflow-x: auto;">
            <table id="riskTable" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Facility/Location</th>
                        <th style="padding: 12px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Hazard</th>
                        <th style="padding: 12px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Residual Risk</th>
                        <th style="padding: 12px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">AI Predicted</th>
                        <th style="padding: 12px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Incidents (30d)</th>
                        <th style="padding: 12px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Status</th>
                        <th style="padding: 12px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riskAssessments as $assessment)
                        <tr style="border-bottom: 1px solid #e2e8f0;" class="risk-row">
                            <td style="padding: 14px;">
                                <div style="font-weight: 600; color: #101a31; font-size: 13px;">{{ $assessment->facility->name }}</div>
                                @if($assessment->specific_zone)
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Zone: {{ $assessment->specific_zone }}</div>
                                @endif
                                @if($assessment->department)
                                    <div style="font-size: 11px; color: #64748b;">Dept: {{ $assessment->department->name }}</div>
                                @endif
                            </td>
                            <td style="padding: 14px;">
                                <div style="font-weight: 600; color: #101a31; font-size: 13px;">{{ Str::limit($assessment->hazard_description, 60) }}</div>
                                <span style="display: inline-block; margin-top: 4px; padding: 2px 8px; background: #e0e7ff; color: #3730a3; font-size: 10px; font-weight: 600; border-radius: 4px;">
                                    {{ $assessment->hazard_category }}
                                </span>
                            </td>
                            <td style="padding: 14px; text-align: center;">
                                @php
                                    $riskColor = '#10b981';
                                    if ($assessment->residual_risk_level === 'CRITICAL') $riskColor = '#dc2626';
                                    elseif ($assessment->residual_risk_level === 'HIGH') $riskColor = '#f59e0b';
                                    elseif ($assessment->residual_risk_level === 'MEDIUM') $riskColor = '#eab308';
                                @endphp
                                <div style="display: inline-block; padding: 6px 12px; background: {{ $riskColor }}; color: white; font-weight: 700; border-radius: 6px; font-size: 14px;">
                                    {{ $assessment->residual_risk_score }}
                                </div>
                                <div style="font-size: 10px; color: #64748b; margin-top: 4px; font-weight: 600;">
                                    {{ $assessment->residual_risk_level }}
                                </div>
                            </td>
                            <td style="padding: 14px; text-align: center;">
                                @if($assessment->predicted_risk_score)
                                    @php
                                        $predColor = '#10b981';
                                        if ($assessment->predicted_risk_level === 'CRITICAL') $predColor = '#dc2626';
                                        elseif ($assessment->predicted_risk_level === 'HIGH') $predColor = '#f59e0b';
                                        elseif ($assessment->predicted_risk_level === 'MEDIUM') $predColor = '#eab308';
                                    @endphp
                                    <div style="display: inline-block; padding: 6px 12px; background: {{ $predColor }}; color: white; font-weight: 700; border-radius: 6px; font-size: 14px; position: relative;">
                                        <i class="fas fa-robot" style="font-size: 10px; margin-right: 4px;"></i>
                                        {{ $assessment->predicted_risk_score }}
                                        @if($assessment->escalation_warning)
                                            <i class="fas fa-exclamation-triangle" style="position: absolute; top: -4px; right: -4px; font-size: 10px; color: #fef3c7; background: #dc2626; border-radius: 50%; padding: 2px;"></i>
                                        @endif
                                    </div>
                                    <div style="font-size: 10px; color: #64748b; margin-top: 4px; font-weight: 600;">
                                        {{ $assessment->predicted_risk_level }}
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-size: 12px;">—</span>
                                @endif
                            </td>
                            <td style="padding: 14px; text-align: center;">
                                <div style="font-weight: 700; font-size: 16px; color: {{ $assessment->linked_incidents_30d > 0 ? '#dc2626' : '#10b981' }};">
                                    {{ $assessment->linked_incidents_30d }}
                                </div>
                                @if($assessment->linked_dod_total > 0)
                                    <div style="font-size: 10px; color: #dc2626; font-weight: 700; margin-top: 2px;">
                                        <i class="fas fa-skull-crossbones"></i> {{ $assessment->linked_dod_total }} DOD
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 14px;">
                                @if($assessment->status === 'ACTIVE')
                                    <span style="display: inline-block; padding: 4px 10px; background: #d1fae5; color: #065f46; font-size: 11px; font-weight: 700; border-radius: 6px;">ACTIVE</span>
                                @elseif($assessment->status === 'NEEDS_REVIEW')
                                    <span style="display: inline-block; padding: 4px 10px; background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 700; border-radius: 6px;">NEEDS REVIEW</span>
                                @elseif($assessment->status === 'EXPIRED')
                                    <span style="display: inline-block; padding: 4px 10px; background: #fecaca; color: #991b1b; font-size: 11px; font-weight: 700; border-radius: 6px;">EXPIRED</span>
                                @endif
                                @if($assessment->escalation_warning)
                                    <div style="font-size: 10px; color: #dc2626; margin-top: 4px; font-weight: 600;">
                                        <i class="fas fa-exclamation-triangle"></i> AI Alert
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 14px; text-align: center;">
                                <button onclick="viewDetails({{ $assessment->id }})" style="background: #3b82f6; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: #94a3b8;">
                                No risk assessments found. Click "New Risk Assessment" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- CAPA Tracker --}}
    <div style="background: white; padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; color: #101a31;">
            <i class="fas fa-tasks" style="color: #3b82f6; margin-right: 8px;"></i>
            Corrective & Preventive Actions (CAPA)
        </h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Action</th>
                        <th style="padding: 12px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Facility</th>
                        <th style="padding: 12px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Priority</th>
                        <th style="padding: 12px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Assigned To</th>
                        <th style="padding: 12px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Due Date</th>
                        <th style="padding: 12px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Status</th>
                        <th style="padding: 12px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingActions as $action)
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 14px;">
                                <div style="font-weight: 600; color: #101a31; font-size: 13px;">{{ Str::limit($action->action_description, 80) }}</div>
                                <span style="display: inline-block; margin-top: 4px; padding: 2px 8px; background: {{ $action->action_type === 'CORRECTIVE' ? '#fef3c7' : '#dbeafe' }}; color: {{ $action->action_type === 'CORRECTIVE' ? '#92400e' : '#1e40af' }}; font-size: 10px; font-weight: 600; border-radius: 4px;">
                                    {{ $action->action_type }}
                                </span>
                            </td>
                            <td style="padding: 14px; font-size: 13px; color: #64748b;">
                                {{ $action->riskAssessment->facility->name }}
                            </td>
                            <td style="padding: 14px; text-align: center;">
                                @php
                                    $priorityColor = '#10b981';
                                    if ($action->priority === 'CRITICAL') $priorityColor = '#dc2626';
                                    elseif ($action->priority === 'HIGH') $priorityColor = '#f59e0b';
                                    elseif ($action->priority === 'MEDIUM') $priorityColor = '#eab308';
                                @endphp
                                <span style="display: inline-block; padding: 4px 10px; background: {{ $priorityColor }}; color: white; font-size: 11px; font-weight: 700; border-radius: 6px;">
                                    {{ $action->priority }}
                                </span>
                            </td>
                            <td style="padding: 14px; font-size: 13px; color: #64748b;">
                                {{ $action->assignedTo->name ?? 'Unassigned' }}
                            </td>
                            <td style="padding: 14px; text-align: center; font-size: 13px;">
                                <div style="color: {{ $action->is_overdue ? '#dc2626' : '#64748b' }}; font-weight: {{ $action->is_overdue ? '700' : '500' }};">
                                    {{ $action->due_date?->format('M d, Y') ?? 'No date' }}
                                </div>
                                @if($action->is_overdue)
                                    <div style="font-size: 10px; color: #dc2626; font-weight: 700; margin-top: 2px;">
                                        <i class="fas fa-exclamation-circle"></i> OVERDUE
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 14px; text-align: center;">
                                @if($action->status === 'PENDING')
                                    <span style="display: inline-block; padding: 4px 10px; background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 700; border-radius: 6px;">PENDING</span>
                                @elseif($action->status === 'IN_PROGRESS')
                                    <span style="display: inline-block; padding: 4px 10px; background: #dbeafe; color: #1e40af; font-size: 11px; font-weight: 700; border-radius: 6px;">IN PROGRESS</span>
                                @endif
                            </td>
                            <td style="padding: 14px; text-align: center;">
                                <div style="font-size: 14px; font-weight: 700; color: #3b82f6;">{{ $action->completion_percentage }}%</div>
                                <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 3px; margin-top: 4px; overflow: hidden;">
                                    <div style="width: {{ $action->completion_percentage }}%; height: 100%; background: #3b82f6; transition: width 0.3s;"></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: #94a3b8;">
                                No pending corrective actions.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- JavaScript for Search and Interactions --}}
<script>
// Live search
document.getElementById('searchTable').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#riskTable tbody .risk-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

function viewDetails(id) {
    alert('View details for risk assessment ID: ' + id + '\n\nThis will open a detailed modal showing:\n- Full hazard description\n- All control measures\n- AI predictive insights\n- Historical incidents\n- Audit trail\n- Corrective actions');
}

function openCreateAssessmentModal() {
    alert('Create New Risk Assessment Modal\n\nThis will open a form to:\n1. Select facility and location\n2. Describe the hazard\n3. Score inherent risk (before controls)\n4. List control measures by hierarchy\n5. Score residual risk (after controls)\n6. AI will automatically calculate predictive risk');
}
</script>

@endsection
