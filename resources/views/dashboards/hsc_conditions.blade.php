@extends('layouts.dashboard')

@section('page_title', 'HSC Conditions Dashboard')

@section('header_title', 'HSC Conditions Officer')
@section('header_subtitle', 'Occupational Health & Safety Monitoring')

@section('sidebar_content')
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>

<!-- Dashboard -->
<a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand-light transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Dashboard</span>
</a>

<!-- Incident Mapping -->
<a href="{{ route('hsc.cases.incident-mapping') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-map-location-dot w-5 text-center text-blue-600 text-sm"></i>
    <span>Incident Mapping</span>
</a>

<!-- Case Management Section -->
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-folder-open w-5 text-center text-slate-700 text-sm"></i>
            Case Management
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('hsc.cases.all') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-list-check w-4 text-center text-slate-700"></i>
            <span>Injury on Duty Cases</span>
        </a>
        <a href="{{ route('hsc.cases.death-on-duty') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-heart-crack w-4 text-center text-red-600"></i>
            <span>Death on Duty Cases</span>
        </a>
    </div>
</div>

<!-- Monitoring & Analytics Section -->
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-chart-line w-5 text-center text-slate-700 text-sm"></i>
            Monitoring & Analytics
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('hsc.compliance.dashboard') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-clipboard-check w-4 text-center text-slate-700"></i>
            <span>Compliance Dashboard</span>
        </a>
        <a href="{{ route('hsc.incident.trends') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-chart-area w-4 text-center text-slate-700"></i>
            <span>Incident Trends</span>
        </a>
        <a href="{{ route('hsc.risk.assessment') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-exclamation-triangle w-4 text-center text-orange-600"></i>
            <span>Risk Assessment</span>
        </a>
    </div>
</div>

<!-- Insights and Reports Section -->
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-lightbulb w-5 text-center text-slate-700 text-sm"></i>
            Insights and Reports
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('hsc.cases.by-facility') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-hospital w-4 text-center text-slate-700"></i>
            <span>Injury Cases By Facility</span>
        </a>
    </div>
</div>

<!-- Support Section -->
<a href="{{ route('support.create_ticket') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-headset w-5 text-center text-slate-700 text-sm"></i>
    <span>IT Support</span>
</a>


@endsection

@section('dashboard_content')
<!-- Header -->
<!-- COMPONENT 1: Macro KPI Banner - HSC Safety Conditions Monitoring -->
<div class="mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-brand"></i> SAFETY CONDITIONS OVERVIEW
            </h2>
            <a href="{{ route('hsc.workflow.health') }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-lg text-[11px] font-semibold text-blue-700 hover:bg-blue-100 transition-colors">
                <i class="fa-solid fa-arrow-trend-up text-[10px]"></i>
                <span>Workflow Health</span>
            </a>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <button class="flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-[11px] font-medium text-slate-600 hover:bg-slate-50 transition-colors shadow-sm w-full sm:w-auto">
                <i class="fa-regular fa-calendar text-slate-400 text-[10px]"></i>Last 30 Days
                <i class="fa-solid fa-chevron-down text-slate-400 text-[10px]"></i>
            </button>
            @can('export_reports')
            <button class="flex items-center justify-center gap-1.5 px-2.5 py-1.5 bg-brand text-white rounded-lg text-[11px] font-medium hover:bg-brand-dark transition-colors shadow-sm w-full sm:w-auto">
                <i class="fa-solid fa-file-export text-[10px]"></i>
                <span class="hidden sm:inline">Export Report</span>
                <span class="sm:hidden">Export</span>
            </button>
            @endcan
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5 md:gap-3">
        <!-- Total Cases -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="total">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-[10px] font-semibold text-slate-600 uppercase tracking-wide">Total Cases</span>
                    <p class="text-lg font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['total_cases'] ?? 0) }}</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">All time</p>
                </div>
                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-briefcase text-blue-600 text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Active Cases -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="active">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-[10px] font-semibold text-slate-600 uppercase tracking-wide">Active Cases</span>
                    <p class="text-lg font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['active_workflows'] ?? 0) }}</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">In workflow</p>
                </div>
                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-spinner text-amber-600 text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Resolved Cases -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="resolved">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-[10px] font-semibold text-slate-600 uppercase tracking-wide">Resolved Cases</span>
                    <p class="text-lg font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['resolved_incidents'] ?? 0) }}</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">
                        {{ ($incidentStatistics['total_incidents'] ?? 0) > 0 ? round((($incidentStatistics['resolved_incidents'] ?? 0) / ($incidentStatistics['total_incidents'] ?? 1)) * 100, 1) : 0 }}% rate
                    </p>
                </div>
                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Rejected Cases -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="rejected">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-[10px] font-semibold text-slate-600 uppercase tracking-wide">Rejected Cases</span>
                    <p class="text-lg font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['rejected_incidents'] ?? 0) }}</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Not approved</p>
                </div>
                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-red-50 to-red-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark text-red-600 text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Hazards Identified -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="hazards">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-[10px] font-semibold text-slate-600 uppercase tracking-wide">Hazards Identified</span>
                    <p class="text-lg font-bold text-slate-900 mt-0.5">14</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Requiring action</p>
                </div>
                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-orange-600 text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Compliance Status -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="compliance">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-[10px] font-semibold text-slate-600 uppercase tracking-wide">Compliance Rate</span>
                    <p class="text-lg font-bold text-slate-900 mt-0.5">82%</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">This month</p>
                </div>
                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-trending-up text-purple-600 text-sm"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- COMPONENT 2: Incident Types by Category Chart -->
<div class="mb-6 bg-white rounded-lg border border-slate-200 shadow-sm p-5 mt-6 md:mt-8">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
                <i class="fa-solid fa-chart-bar text-brand"></i> INCIDENT TYPES BY
            </h2>
            <!-- View Toggle Dropdown -->
            <select id="chartViewToggle" class="px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-[12px] font-medium text-slate-700 hover:border-brand focus:border-brand focus:ring-1 focus:ring-brand transition-colors cursor-pointer">
                <option value="province">PROVINCE</option>
                <option value="district">DISTRICT</option>
            </select>
        </div>
        <span class="bg-brand bg-opacity-10 text-brand text-[11px] font-bold px-3 py-1 rounded-full" id="totalIncidentsLabel">
            {{ $injuryTypesByProvince->sum('count') }} Total Cases
        </span>
    </div>
    <!-- Loading skeleton (hidden after chart loads) -->
    <div id="chartSkeleton" class="animate-pulse">
        <div class="h-80 bg-slate-100 rounded"></div>
    </div>
    <!-- Chart container (initially hidden) -->
    <div id="incidentsTypesChart" style="height: 380px; display: none;"></div>
</div>

<!-- Recent Incidents Table -->
@if($recentIncidentsTable->count() > 0)
@php
    $totalCount = $recentIncidentsTable->count();
@endphp
<style>
    /* Match supervisor dashboard styling */
    .dashboard-incidents-table { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 24px; margin-top: 32px; }
    .dashboard-incidents-table .table-header { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; }
    .dashboard-incidents-table .table-title { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
    .dashboard-incidents-table .expand-btn { padding: 7px 12px; background: #079b6d; color: white; border-radius: 6px; font-size: 10px; font-weight: 650; transition: all 0.2s; border: none; cursor: pointer; }
    .dashboard-incidents-table .expand-btn:hover { background: #067c58; transform: translateY(-1px); }
    .dashboard-incidents-table table { width: 100%; border-collapse: collapse; }
    .dashboard-incidents-table thead { background: #f8fafb; }
    .dashboard-incidents-table th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
    .dashboard-incidents-table td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
    .dashboard-incidents-table tbody tr { transition: background-color 0.2s ease; }
    .dashboard-incidents-table tbody tr:hover { background: #f8fafb; }
    .dashboard-incidents-table tbody tr:last-child td { border-bottom: none; }
    .dashboard-incidents-table .ref-number { font-weight: 700; color: #079b6d; font-size: 12px; }
    .dashboard-incidents-table .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 999px; font-size: 9px; font-weight: 750; white-space: nowrap; text-transform: uppercase; }
    .dashboard-incidents-table .status-badge.pending { background: #fff8e7; color: #c67a00; }
    .dashboard-incidents-table .status-badge.verified { background: #e9f8f3; color: #067c58; }
    .dashboard-incidents-table .status-badge.escalated { background: #eff6ff; color: #1e40af; }
    .dashboard-incidents-table .status-badge.rejected { background: #fee; color: #991b1b; }
    .dashboard-incidents-table .status-badge.approved { background: #d1fae5; color: #065f46; }
    .dashboard-incidents-table .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; text-decoration: none; border-radius: 6px; font-size: 10px; font-weight: 650; transition: all 0.2s; }
    .dashboard-incidents-table .action-btn:hover { background: #067c58; transform: translateY(-1px); }
    .dashboard-incidents-table .table-footer { padding: 16px 22px; background: white; border-top: 1px solid #e4e9ee; text-align: center; }
    .dashboard-incidents-table .footer-text { font-size: 11px; color: #8a95a5; font-weight: 600; }
    .dashboard-incidents-table .count-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; background: #e9f8f3; color: #067c58; font-weight: 700; margin: 0 4px; }
</style>

<div class="dashboard-incidents-table" x-data="{ expanded: false }">
    <!-- Header Section -->
    <div class="table-header">
        <div class="table-title">
            <i class="fa-solid fa-table-list"></i>
            &nbsp; Recent Incidents
            <span style="display: inline-block; margin-left: 12px; padding: 4px 10px; border-radius: 6px; background: #e9f8f3; color: #067c58; font-size: 10px; font-weight: 700;">{{ $totalCount }}</span>
        </div>
        <button @click="expanded = !expanded" class="expand-btn">
            <i class="fa-solid fa-eye" x-show="!expanded"></i>
            <i class="fa-solid fa-eye-slash" x-show="expanded"></i>
            <span x-show="!expanded">View All</span>
            <span x-show="expanded">Show Less</span>
        </button>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">#</th>
                    <th>Reference</th>
                    <th class="hidden sm:table-cell">Employee Name</th>
                    <th class="hidden md:table-cell">Facility</th>
                    <th class="hidden lg:table-cell">Incident Type</th>
                    <th>Date Reported</th>
                    <th style="text-align: center;">Status</th>
                    <th style="width: 100px; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentIncidentsTable as $incident)
                    @php
                        $displayCount = $loop->iteration;
                        $status = $incident->status ?? 'PENDING_SUPERVISOR_VERIFICATION';
                        $statusLabel = '';
                        $statusClass = '';
                        
                        switch($status) {
                            case 'PENDING_SUPERVISOR_VERIFICATION':
                                $statusLabel = 'Awaiting Supervisor';
                                $statusClass = 'pending';
                                break;
                            case 'VERIFIED_SUPERVISOR':
                                $statusLabel = 'Verified by Supervisor';
                                $statusClass = 'verified';
                                break;
                            case 'REJECTED_SUPERVISOR':
                                $statusLabel = 'Rejected by Supervisor';
                                $statusClass = 'rejected';
                                break;
                            case 'ESCALATED_TO_HR':
                            case 'PENDING_HR_REVIEW':
                            case 'HR_REVIEW_IN_PROGRESS':
                                $statusLabel = 'Awaiting HR Review';
                                $statusClass = 'escalated';
                                break;
                            case 'APPROVED_BY_HR':
                            case 'HR_APPROVED':
                                $statusLabel = 'Approved by HR';
                                $statusClass = 'approved';
                                break;
                            case 'REJECTED_BY_HR':
                            case 'HR_REJECTED':
                                $statusLabel = 'Rejected by HR';
                                $statusClass = 'rejected';
                                break;
                            case 'ESCALATED_TO_PMD':
                            case 'PENDING_PMD_REVIEW':
                                $statusLabel = 'Awaiting PMD Review';
                                $statusClass = 'escalated';
                                break;
                            case 'PMD_REVIEW_IN_PROGRESS':
                                $statusLabel = 'PMD Reviewing';
                                $statusClass = 'escalated';
                                break;
                            case 'APPROVED_BY_PMD':
                            case 'PMD_APPROVED':
                                $statusLabel = 'Approved by PMD';
                                $statusClass = 'approved';
                                break;
                            case 'REJECTED_BY_PMD':
                            case 'PMD_REJECTED':
                                $statusLabel = 'Rejected by PMD';
                                $statusClass = 'rejected';
                                break;
                            case 'PENDING_ACTION':
                                $statusLabel = 'Pending Action';
                                $statusClass = 'pending';
                                break;
                            case 'RESOLVED':
                            case 'CLOSED':
                                $statusLabel = 'Resolved';
                                $statusClass = 'approved';
                                break;
                            case 'REJECTED':
                                $statusLabel = 'Rejected';
                                $statusClass = 'rejected';
                                break;
                            default:
                                $statusLabel = 'Pending Review';
                                $statusClass = 'pending';
                        }
                    @endphp
                    <tr x-show="expanded || {{ $displayCount }} <= 5">
                        <td style="text-align: center; font-weight: 700; color: #8a95a5;">{{ $loop->iteration }}</td>
                        <td><span class="ref-number">{{ $incident->reference_number }}</span></td>
                        <td class="hidden sm:table-cell">{{ $incident->first_name }} {{ $incident->surname }}</td>
                        <td class="hidden md:table-cell">{{ Str::limit($incident->facility?->name ?? 'N/A', 30) }}</td>
                        <td class="hidden lg:table-cell">{{ $incident->incidentType?->name ?? 'Unknown' }}</td>
                        <td>{{ $incident->created_at->format('d M Y, H:i') }}</td>
                        <td style="text-align: center;">
                            <span class="status-badge {{ $statusClass }}">
                                <i class="fa-solid fa-circle" style="font-size: 6px;"></i>
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('incidents.hsc_view', ['incident' => $incident]) }}" class="action-btn">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($totalCount > 1)
    <div class="table-footer">
        <p class="footer-text">
            Showing <span x-text="expanded ? '{{ $totalCount }}' : Math.min(5, {{ $totalCount }})"></span> of <span class="count-badge">{{ $totalCount }}</span> incidents
        </p>
    </div>
    @endif
</div>
@endif
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts" defer></script>
<script>
    // Defer chart initialization until DOM is ready and ApexCharts is loaded
    window.addEventListener('load', function() {
        let currentChart = null;
        let currentView = 'province';

        // Data from controller
        const provinceData = @json($injuryTypesByProvince);
        const districtData = @json($injuryTypesByDistrict);

        // Function to create/update chart
        function createChart(view) {
            const data = view === 'province' ? provinceData : districtData;
            const labels = data.map(item => view === 'province' ? item.province : item.district);
            const values = data.map(item => parseInt(item.count, 10));
            const totalCases = values.reduce((sum, val) => sum + val, 0);
            
            // Update total cases label
            document.getElementById('totalIncidentsLabel').textContent = totalCases + ' Total Cases';
            
            const isHorizontal = view === 'province';
            
            // Calculate dynamic height for horizontal bar chart based on number of bars
            let chartHeight = 350;
            if (isHorizontal) {
                chartHeight = Math.max(250, labels.length * 25 + 40);
            }
            
            // For horizontal charts, format data with x and y explicitly
            const chartData = isHorizontal ? 
                labels.map((label, index) => ({
                    x: label,
                    y: values[index]
                })) : values;
            
            const options = {
                series: [{
                    name: 'Number of Cases',
                    data: chartData.length > 0 ? chartData : [0]
                }],
                chart: {
                    type: 'bar',
                    height: chartHeight,
                    fontFamily: 'Inter, sans-serif',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    },
                    animations: {
                        enabled: true,
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        }
                    },
                    offsetY: -10
                },
                plotOptions: {
                    bar: {
                        horizontal: isHorizontal,
                        columnWidth: isHorizontal ? undefined : '70%',
                        barHeight: isHorizontal ? '65%' : undefined,
                        borderRadius: 6,
                        dataLabels: {
                            position: isHorizontal ? 'right' : 'top'
                        },
                        distributed: true
                    }
                },
                colors: ['#0d7a3d', '#0f8c47', '#119e51', '#13b05b', '#15c265', '#1dd46f', '#20e87f', '#23fa89', '#26ff93', '#29ffa0', '#2cff8a', '#2fff83', '#32ff7c', '#35ff75', '#38ff6e', '#3bff67', '#3eff60', '#41ff59', '#44ff52', '#47ff4b'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        shadeIntensity: 0.4,
                        gradientToColors: ['#1bb74d', '#1dd46f', '#20e87f', '#23fa89', '#26ff93', '#29ffa0', '#2cff8a', '#2fff83', '#32ff7c', '#35ff75', '#38ff6e', '#3bff67', '#3eff60', '#41ff59', '#44ff52', '#47ff4b', '#4aff44', '#4dff3d', '#50ff36', '#53ff2f'],
                        inverseColors: false,
                        opacityFrom: 0.95,
                        opacityTo: 0.85,
                        stops: [0, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: isHorizontal ? undefined : labels,
                    labels: {
                        rotate: isHorizontal ? 0 : -45,
                        rotateAlways: !isHorizontal,
                        style: {
                            fontSize: '11px',
                            fontWeight: '600',
                            colors: '#64748b'
                        },
                        formatter: function(val) {
                            return isHorizontal ? Math.floor(val) : val;
                        }
                    },
                    axisBorder: {
                        show: true,
                        color: '#e2e8f0'
                    },
                    axisTicks: {
                        show: true,
                        color: '#e2e8f0'
                    },
                    type: isHorizontal ? 'numeric' : 'category',
                    tickAmount: isHorizontal ? 'dataPoints' : undefined,
                    title: isHorizontal ? {
                        text: 'Number of Cases',
                        offsetY: -5,
                        style: {
                            fontSize: '12px',
                            fontWeight: 600,
                            color: '#475569'
                        }
                    } : undefined
                },
                yaxis: isHorizontal ? {
                    labels: {
                        style: {
                            fontSize: '12px',
                            fontWeight: 600,
                            colors: '#1e293b'
                        },
                        maxWidth: 180
                    }
                } : {
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: '500',
                            colors: '#64748b'
                        },
                        formatter: function(val) {
                            return Math.floor(val);
                        }
                    },
                    title: {
                        text: 'Number of Cases',
                        style: {
                            fontSize: '12px',
                            fontWeight: 600,
                            color: '#475569'
                        }
                    }
                },
                colors: ['#079b6d'],
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 3,
                    padding: {
                        bottom: -10
                    },
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    theme: 'dark',
                    y: {
                        formatter: function(val) {
                            return val + ' cases';
                        }
                    },
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif'
                    }
                },
                legend: {
                    show: false
                }
            };

            // Destroy previous chart if exists
            if (currentChart) {
                currentChart.destroy();
            }

            // Create new chart
            currentChart = new ApexCharts(document.querySelector("#incidentsTypesChart"), options);
            currentChart.render().then(() => {
                // Hide skeleton and show chart
                document.getElementById('chartSkeleton').style.display = 'none';
                document.getElementById('incidentsTypesChart').style.display = 'block';
            });
        }

        // Initialize with province view
        createChart('province');

        // Handle dropdown change
        document.getElementById('chartViewToggle').addEventListener('change', function(e) {
            currentView = e.target.value;
            createChart(currentView);
        });

    });
</script>
@endsection
