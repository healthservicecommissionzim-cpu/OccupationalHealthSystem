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
            <span>All Cases</span>
        </a>
        <a href="{{ route('hsc.cases.by-status') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-filter w-4 text-center text-slate-700"></i>
            <span>Cases by Status</span>
        </a>
        <a href="{{ route('hsc.cases.by-facility') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-hospital w-4 text-center text-slate-700"></i>
            <span>Cases by Facility</span>
        </a>
        <a href="{{ route('hsc.cases.by-date') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-calendar w-4 text-center text-slate-700"></i>
            <span>Cases by Date Range</span>
        </a>
        <a href="{{ route('hsc.cases.search') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-magnifying-glass w-4 text-center text-slate-700"></i>
            <span>Search Cases</span>
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
        <a href="{{ route('hsc.facility.performance') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-building w-4 text-center text-slate-700"></i>
            <span>Facility Performance</span>
        </a>
        <a href="{{ route('hsc.incident.trends') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-chart-area w-4 text-center text-slate-700"></i>
            <span>Incident Trends</span>
        </a>
        <a href="{{ route('hsc.risk.assessment') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-exclamation-triangle w-4 text-center text-orange-600"></i>
            <span>Risk Assessment</span>
        </a>
        <a href="{{ route('hsc.workflow.health') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-heartbeat w-4 text-center text-slate-700"></i>
            <span>Workflow Health</span>
        </a>
    </div>
</div>

<!-- Quality Assurance Section -->
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-shield-halved w-5 text-center text-slate-700 text-sm"></i>
            Quality Assurance
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('hsc.audit.cases') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-magnifying-glass-chart w-4 text-center text-slate-700"></i>
            <span>Case Audits</span>
        </a>
        <a href="{{ route('hsc.audit.documents') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-file-lines w-4 text-center text-slate-700"></i>
            <span>Document Verification</span>
        </a>
        <a href="{{ route('hsc.audit.timeline') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-hourglass-end w-4 text-center text-slate-700"></i>
            <span>Timeline Compliance</span>
        </a>
        <a href="{{ route('hsc.audit.procedures') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-gavel w-4 text-center text-slate-700"></i>
            <span>Procedure Adherence</span>
        </a>
    </div>
</div>

<!-- Reports & Evidence Section -->
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-file-pdf w-5 text-center text-slate-700 text-sm"></i>
            Reports & Evidence
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('hsc.reports.generate') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-file-export w-4 text-center text-slate-700"></i>
            <span>Generate Reports</span>
        </a>
        <a href="{{ route('hsc.reports.export') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-download w-4 text-center text-slate-700"></i>
            <span>Export Data</span>
        </a>
        <a href="{{ route('hsc.reports.statistics') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-chart-bar w-4 text-center text-slate-700"></i>
            <span>Statistical Summary</span>
        </a>
        <a href="{{ route('hsc.reports.comparative') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-code-branch w-4 text-center text-slate-700"></i>
            <span>Comparative Analysis</span>
        </a>
    </div>
</div>

<!-- System Oversight Section -->
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-sliders w-5 text-center text-slate-700 text-sm"></i>
            System Oversight
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('hsc.system.user-activity') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-user-secret w-4 text-center text-slate-700"></i>
            <span>User Activity Log</span>
        </a>
        <a href="{{ route('hsc.system.escalations') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-arrow-up-right-dots w-4 text-center text-red-600"></i>
            <span>Escalation Tracking</span>
        </a>
        <a href="{{ route('hsc.system.notifications') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-bell w-4 text-center text-slate-700"></i>
            <span>Notification History</span>
        </a>
        <a href="{{ route('hsc.system.performance') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-gauge w-4 text-center text-slate-700"></i>
            <span>Performance Metrics</span>
        </a>
    </div>
</div>

<!-- Support Section -->
<a href="{{ route('support.create_ticket') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-headset w-5 text-center text-slate-700 text-sm"></i>
    <span>IT Support</span>
</a>

<a href="{{ route('support.my_tickets') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-ticket w-5 text-center text-slate-700 text-sm"></i>
    <span>My Support Tickets</span>
</a>


@endsection

@section('dashboard_content')
<!-- Header -->
<!-- COMPONENT 1: Macro KPI Banner - HSC Safety Conditions Monitoring -->
<div class="mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-brand"></i> SAFETY CONDITIONS OVERVIEW
        </h2>
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
        <!-- Total Cases -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="total">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Total Cases</span>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['total_cases'] ?? 0) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">All time</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-briefcase text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Active Cases -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="active">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Active Cases</span>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['active_workflows'] ?? 0) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">In workflow</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-spinner text-amber-600"></i>
                </div>
            </div>
        </div>

        <!-- Resolved Cases -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="resolved">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Resolved Cases</span>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['resolved_incidents'] ?? 0) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ ($incidentStatistics['total_incidents'] ?? 0) > 0 ? round((($incidentStatistics['resolved_incidents'] ?? 0) / ($incidentStatistics['total_incidents'] ?? 1)) * 100, 1) : 0 }}% rate
                    </p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                </div>
            </div>
        </div>

        <!-- Rejected Cases -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="rejected">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Rejected Cases</span>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['rejected_incidents'] ?? 0) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Not approved</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-red-50 to-red-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark text-red-600"></i>
                </div>
            </div>
        </div>

        <!-- Hazards Identified -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="hazards">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Hazards Identified</span>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">14</p>
                    <p class="text-xs text-slate-500 mt-0.5">Requiring action</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-orange-600"></i>
                </div>
            </div>
        </div>

        <!-- Compliance Status -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="compliance">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Compliance Rate</span>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">82%</p>
                    <p class="text-xs text-slate-500 mt-0.5">This month</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-trending-up text-purple-600"></i>
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
<div class="bg-white rounded-lg border border-slate-200 shadow-sm mb-6 overflow-hidden mt-6 md:mt-8" x-data="{ expanded: false }">
    <!-- Header Section -->
    <div class="p-4 md:p-6 border-b border-slate-200 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-brand bg-opacity-10 flex items-center justify-center">
                <i class="fa-solid fa-list-check text-brand text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm md:text-base font-semibold text-navy">
                    Recent Incidents
                </h3>
            </div>
        </div>
        <button @click="expanded = !expanded" class="px-3 py-1.5 bg-brand text-white rounded-lg text-xs font-semibold hover:bg-brand-dark transition-colors whitespace-nowrap">
            <span x-show="!expanded">View All</span>
            <span x-show="expanded">Show Less</span>
        </button>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Reference</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider hidden sm:table-cell">Employee</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider hidden md:table-cell">Facility</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider hidden lg:table-cell">Type</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Date</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Action</th>
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
                                $statusLabel = 'Awaiting Supervisor Verification';
                                $statusClass = 'bg-amber-50 text-amber-700';
                                break;
                            case 'VERIFIED_SUPERVISOR':
                                $statusLabel = 'Verified by Supervisor';
                                $statusClass = 'bg-blue-50 text-blue-700';
                                break;
                            case 'REJECTED_SUPERVISOR':
                                $statusLabel = 'Rejected by Supervisor';
                                $statusClass = 'bg-red-50 text-red-700';
                                break;
                            case 'ESCALATED_TO_HR':
                                $statusLabel = 'Escalated to HR';
                                $statusClass = 'bg-purple-50 text-purple-700';
                                break;
                            case 'PENDING_HR_REVIEW':
                            case 'HR_REVIEW_IN_PROGRESS':
                                $statusLabel = 'Awaiting HR Review';
                                $statusClass = 'bg-indigo-50 text-indigo-700';
                                break;
                            case 'APPROVED_BY_HR':
                            case 'HR_APPROVED':
                                $statusLabel = 'Approved by HR';
                                $statusClass = 'bg-teal-50 text-teal-700';
                                break;
                            case 'REJECTED_BY_HR':
                            case 'HR_REJECTED':
                                $statusLabel = 'Rejected by HR';
                                $statusClass = 'bg-red-50 text-red-700';
                                break;
                            case 'ESCALATED_TO_PMD':
                            case 'PENDING_PMD_REVIEW':
                                $statusLabel = 'Awaiting PMD Verification';
                                $statusClass = 'bg-orange-50 text-orange-700';
                                break;
                            case 'PMD_REVIEW_IN_PROGRESS':
                                $statusLabel = 'PMD Review in Progress';
                                $statusClass = 'bg-yellow-50 text-yellow-700';
                                break;
                            case 'APPROVED_BY_PMD':
                            case 'PMD_APPROVED':
                                $statusLabel = 'Approved by PMD';
                                $statusClass = 'bg-emerald-50 text-emerald-700';
                                break;
                            case 'REJECTED_BY_PMD':
                            case 'PMD_REJECTED':
                                $statusLabel = 'Rejected by PMD';
                                $statusClass = 'bg-red-50 text-red-700';
                                break;
                            case 'PENDING_ACTION':
                                $statusLabel = 'Pending Action';
                                $statusClass = 'bg-amber-50 text-amber-700';
                                break;
                            case 'RESOLVED':
                            case 'CLOSED':
                                $statusLabel = 'Resolved';
                                $statusClass = 'bg-emerald-50 text-emerald-700';
                                break;
                            case 'REJECTED':
                                $statusLabel = 'Rejected';
                                $statusClass = 'bg-red-50 text-red-700';
                                break;
                            default:
                                $statusLabel = ucwords(str_replace('_', ' ', strtolower($status)));
                                $statusClass = 'bg-gray-50 text-gray-700';
                        }
                    @endphp
                    <tr x-show="expanded || {{ $displayCount }} <= 5" class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-4 text-xs md:text-sm font-semibold text-brand">{{ $incident->reference_number }}</td>
                        <td class="py-3 px-4 text-xs md:text-sm text-slate-700 hidden sm:table-cell">{{ $incident->first_name }} {{ $incident->surname }}</td>
                        <td class="py-3 px-4 text-xs md:text-sm text-slate-700 hidden md:table-cell truncate">{{ $incident->facility?->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-xs md:text-sm text-slate-700 hidden lg:table-cell">{{ $incident->incidentType?->name ?? 'Unknown' }}</td>
                        <td class="py-3 px-4 text-xs md:text-sm text-slate-500">{{ $incident->created_at->format('M d, Y') }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                <i class="fa-solid fa-circle" style="font-size: 5px;"></i>
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('incidents.hsc_view', ['incident' => $incident]) }}" class="inline-flex items-center gap-1 px-2 py-1 bg-brand text-white rounded text-xs font-semibold hover:bg-brand-dark transition-colors">
                                <i class="fa-solid fa-eye text-xs"></i>
                                <span class="hidden sm:inline">Review</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($totalCount > 1)
    <div class="px-4 md:px-6 py-3 text-center border-t border-slate-200 bg-slate-50">
        <p class="text-xs md:text-sm text-slate-500 font-medium">
            Showing <span x-text="expanded ? '{{ $totalCount }}' : Math.min(5, {{ $totalCount }})"></span> of <span class="inline-block px-2.5 py-0.5 rounded-full bg-brand bg-opacity-10 text-brand font-semibold">{{ $totalCount }}</span> incidents
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
            
            console.log('Creating chart for view:', view);
            console.log('Labels:', labels);
            console.log('Values:', values);
            console.log('Total Cases:', totalCases);
            
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
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: isHorizontal,
                        columnWidth: isHorizontal ? undefined : '70%',
                        barHeight: '65%',
                        borderRadius: 6,
                        dataLabels: {
                            position: isHorizontal ? 'right' : 'top'
                        },
                        distributed: true
                    }
                },
                dataLabels: {
                    enabled: false
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
                xaxis: isHorizontal ? {
                    type: 'numeric',
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: 600,
                            colors: '#475569'
                        },
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    },
                    tickAmount: 'dataPoints',
                    axisBorder: {
                        show: true,
                        color: '#e2e8f0'
                    },
                    title: {
                        text: 'Number of Cases',
                        style: {
                            fontSize: '12px',
                            fontWeight: 600,
                            color: '#475569'
                        }
                    }
                } : {
                    categories: labels.length > 0 ? labels : ['No Data'],
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: 600,
                            colors: '#64748b'
                        },
                        rotate: -45,
                        rotateAlways: false
                    },
                    axisBorder: {
                        show: true,
                        color: '#e2e8f0'
                    },
                    title: {
                        text: view === 'district' ? 'District' : 'Province',
                        style: {
                            fontSize: '12px',
                            fontWeight: 600,
                            color: '#475569'
                        }
                    }
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
                            fontWeight: 600,
                            colors: '#475569'
                        },
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    },
                    tickAmount: 5,
                    title: {
                        text: 'Number of Cases',
                        style: {
                            fontSize: '12px',
                            fontWeight: 600,
                            color: '#475569'
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 3,
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
                    theme: 'dark',
                    y: {
                        formatter: function(val) {
                            return val + ' cases';
                        }
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
