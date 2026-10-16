@extends('layouts.dashboard')

@section('page_title', 'Master Super Admin Dashboard')

@section('header_title', 'Super Administrator')
@section('header_subtitle', 'National System Management & Oversight')

@section('sidebar_content')
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>
<a href="{{ route('dashboards.super_admin') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg bg-white text-brand border-l-[3px] border-brand shadow-sm transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-line w-5 text-center text-sm"></i>
    <span>Master Dashboard</span>
</a>

<a href="{{ route('incidents.hotspot_mapping') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-map-location-dot w-5 text-center text-slate-700 text-sm"></i>
    <span>Incident Hotspot Mapping</span>
</a>

<a href="{{ route('incidents.all') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-briefcase w-5 text-center text-slate-700 text-sm"></i>
    <span>All Incidents</span>
</a>

<a href="{{ route('user-approval.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-users w-5 text-center text-slate-700 text-sm"></i>
    <span>All Users</span>
</a>

<a href="{{ route('support.tickets.index') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-headset w-5 text-center text-slate-700 text-sm"></i>
    <span>Support Tickets</span>
</a>

<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-chart-bar w-5 text-center text-slate-700 text-sm"></i>
            Reports & Analytics
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('reports.national') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-file-chart-line w-4 text-center text-slate-700"></i>
            <span>National Reports</span>
        </a>
        <a href="{{ route('reports.provincial_summary') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-map-marked-alt w-4 text-center text-slate-700"></i>
            <span>Provincial Summary</span>
        </a>
        <a href="{{ route('reports.monthly_statistics') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-calendar-days w-4 text-center text-slate-700"></i>
            <span>Monthly Statistics</span>
        </a>
        <a href="{{ route('reports.export_data') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-file-export w-4 text-center text-slate-700"></i>
            <span>Export Data</span>
        </a>
    </div>
</div>

<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-cog w-5 text-center text-slate-700 text-sm"></i>
            System Configuration
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('system.facilities') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-hospital w-4 text-center text-slate-700"></i>
            <span>All Facilities ({{ $systemConfig['total_facilities'] ?? 0 }})</span>
        </a>
        <a href="{{ route('system.departments') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-building w-4 text-center text-slate-700"></i>
            <span>Departments</span>
        </a>
        <a href="{{ route('system.designations') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-id-badge w-4 text-center text-slate-700"></i>
            <span>Designations</span>
        </a>
        <a href="{{ route('system.organizations') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-sitemap w-4 text-center text-slate-700"></i>
            <span>Organizations</span>
        </a>
        <a href="{{ route('system.provinces_districts') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-map w-4 text-center text-slate-700"></i>
            <span>Provinces & Districts</span>
        </a>
        <a href="{{ route('system.role_permissions') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-lock w-4 text-center text-emerald-600"></i>
            <span class="font-semibold">Access Control</span>
        </a>
        <a href="{{ route('system.thresholds') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-sliders w-4 text-center text-slate-700"></i>
            <span>Threshold Variables</span>
        </a>
        <a href="{{ route('system.dashboard_mappings.index') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-arrow-right-to-bracket w-4 text-center text-slate-700"></i>
            <span>Dashboard Routing</span>
        </a>
        <div class="border-t border-slate-200 my-2 mx-4"></div>
        <a href="{{ route('system.database_backup') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-database w-4 text-center text-blue-600"></i>
            <span class="font-semibold">Database Backup</span>
        </a>
        <a href="{{ route('system.documentation') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-book w-4 text-center text-purple-600"></i>
            <span class="font-semibold">System Documentation</span>
        </a>
    </div>
</div>

<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-comment-dots w-5 text-center text-slate-700 text-sm"></i>
            Communications
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-bullhorn w-4 text-center text-slate-700"></i>
            <span>Broadcast Notifications</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-sms w-4 text-center text-slate-700"></i>
            <span>SMS Settings</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-envelope w-4 text-center text-slate-700"></i>
            <span>Email Templates</span>
        </a>
    </div>
</div>

<a href="{{ route('user-approval.audit_logs') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-shield w-5 text-center text-slate-700 text-sm"></i>
    <span>Audit Logs</span>
</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
@endsection

@section('dashboard_content')
<!-- Header -->
<!-- COMPONENT 1: Macro KPI Banner - National Incident Statistics -->
<div class="mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-brand"></i> NATIONAL INCIDENT STATISTICS
        </h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <button class="flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-[11px] font-medium text-slate-600 hover:bg-slate-50 transition-colors shadow-sm w-full sm:w-auto">
                <i class="fa-regular fa-calendar text-slate-400 text-[10px]"></i>All Time
                <i class="fa-solid fa-chevron-down text-slate-400 text-[10px]"></i>
            </button>
            <button class="flex items-center justify-center gap-1.5 px-2.5 py-1.5 bg-brand text-white rounded-lg text-[11px] font-medium hover:bg-brand-dark transition-colors shadow-sm w-full sm:w-auto">
                <i class="fa-solid fa-file-export text-[10px]"></i>
                <span class="hidden sm:inline">Export National Report</span>
                <span class="sm:hidden">Export</span>
            </button>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
        <!-- Total Cases -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="total">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Total Cases</span>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['total_cases']) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">All time</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-briefcase text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="users">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Total Users</span>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format($userStatistics['total_count']) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $userStatistics['pending_count'] }} pending</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-users text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Active Cases -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="active">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Active Cases</span>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['active_workflows']) }}</p>
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
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['resolved_incidents']) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $incidentStatistics['total_incidents'] > 0 ? round(($incidentStatistics['resolved_incidents'] / $incidentStatistics['total_incidents']) * 100, 1) : 0 }}% rate
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
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format($incidentStatistics['rejected_incidents']) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Not approved</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-red-50 to-red-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark text-red-600"></i>
                </div>
            </div>
        </div>

        <!-- Open Support Tickets -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="support">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Open Tickets</span>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ number_format(\App\Models\SupportTicket::whereIn('status', ['open', 'in_progress'])->count()) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Need attention</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-headset text-orange-600 text-base"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- COMPONENT 3: Injury Types by Province/District Chart -->
<div class="mb-6 bg-white rounded-lg border border-slate-200 shadow-sm p-5">
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
        <span class="bg-brand bg-opacity-10 text-brand text-[11px] font-bold px-3 py-1 rounded-full" id="totalCasesLabel">
            {{ $injuryTypesByProvince->sum('count') }} Total Cases
        </span>
    </div>
    <div id="injuryTypesChart" style="height: 380px;"></div>
</div>



<!-- COMPONENT 4: System Configurations Engine -->
<div class="mb-6" id="config-section">
    <h2 class="text-[14px] font-bold text-navy mb-3 flex items-center gap-2">
        <i class="fa-solid fa-cogs text-brand"></i> SYSTEM CONFIGURATION ENGINE
    </h2>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Facilities Directory -->
        <div class="bg-white rounded-xl border border-border shadow-sm p-4 hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-hospital text-blue-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[12px] font-bold text-navy">Health Facilities Directory</h3>
                    <p class="text-[10px] text-slate-500">National registry management</p>
                </div>
            </div>
            <div class="space-y-1.5 mb-3">
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                    <span class="text-[10px] text-slate-600">Total Facilities</span>
                    <span class="text-[11px] font-bold text-brand">{{ $systemConfig['total_facilities'] }}</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                    <span class="text-[10px] text-slate-600">Provinces</span>
                    <span class="text-[11px] font-bold text-brand">{{ $systemConfig['total_provinces'] }}</span>
                </div>
                <div class="flex justify-between items-center py-1.5">
                    <span class="text-[10px] text-slate-600">Districts</span>
                    <span class="text-[11px] font-bold text-brand">{{ $systemConfig['total_districts'] }}</span>
                </div>
            </div>
            <button class="w-full py-1.5 bg-blue-600 text-white rounded-lg text-[10px] font-bold hover:bg-blue-700 transition-colors" onclick="window.location.href='{{ route('system.facilities') }}'">
                <i class="fa-solid fa-pen-to-square mr-1"></i> Manage
            </button>
        </div>

        <!-- Roles & Permissions -->
        <div class="bg-white rounded-xl border border-border shadow-sm p-4 hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-user-shield text-purple-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[12px] font-bold text-navy">Role-Permissions Mapping</h3>
                    <p class="text-[10px] text-slate-500">Access control configuration</p>
                </div>
            </div>
            <div class="space-y-1.5 mb-3">
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                    <span class="text-[10px] text-slate-600">System Roles</span>
                    <span class="text-[11px] font-bold text-brand">{{ $systemConfig['total_roles'] }}</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                    <span class="text-[10px] text-slate-600">Permissions</span>
                    <span class="text-[11px] font-bold text-brand">{{ $systemConfig['total_permissions'] }}</span>
                </div>
                <div class="flex justify-between items-center py-1.5">
                    <span class="text-[10px] text-slate-600">Active Users</span>
                    <span class="text-[11px] font-bold text-brand">{{ $userStatistics['approved_count'] }}</span>
                </div>
            </div>
            <button class="w-full py-1.5 bg-purple-600 text-white rounded-lg text-[10px] font-bold hover:bg-purple-700 transition-colors" onclick="window.location.href='{{ route('system.roles') }}'">
                <i class="fa-solid fa-shield-halved mr-1"></i> Manage Access
            </button>
        </div>

        <!-- Threshold Variables -->
        <div class="bg-white rounded-xl border border-border shadow-sm p-4 hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-sliders text-amber-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[12px] font-bold text-navy">Threshold Variables</h3>
                    <p class="text-[10px] text-slate-500">System limits & constraints</p>
                </div>
            </div>
            <div class="space-y-1.5 mb-3">
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                    <span class="text-[10px] text-slate-600">Leave Limit (HSB 3/2017)</span>
                    <span class="text-[11px] font-bold text-red-700">90 days</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                    <span class="text-[10px] text-slate-600">Statutory Risk Threshold</span>
                    <span class="text-[11px] font-bold text-red-700">9 months</span>
                </div>
                <div class="flex justify-between items-center py-1.5">
                    <span class="text-[10px] text-slate-600">Auto-Escalation Timer</span>
                    <span class="text-[11px] font-bold text-amber-700">30 days</span>
                </div>
            </div>
            <button class="w-full py-1.5 bg-amber-600 text-white rounded-lg text-[10px] font-bold hover:bg-amber-700 transition-colors" onclick="window.location.href='{{ route('system.thresholds') }}'">
                <i class="fa-solid fa-wrench mr-1"></i> Configure
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
        <!-- Dashboard Routing -->
        <div class="bg-white rounded-xl border border-border shadow-sm p-4 hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-arrow-right-to-bracket text-teal-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[12px] font-bold text-navy">Dashboard Routing</h3>
                    <p class="text-[10px] text-slate-500">Role-to-dashboard mappings</p>
                </div>
            </div>
            <div class="space-y-1.5 mb-3">
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                    <span class="text-[10px] text-slate-600">Active Mappings</span>
                    <span class="text-[11px] font-bold text-brand">{{ \App\Models\RoleDashboardMapping::active()->count() }}</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                    <span class="text-[10px] text-slate-600">Mapped Roles</span>
                    <span class="text-[11px] font-bold text-brand">{{ \App\Models\RoleDashboardMapping::distinct('role_id')->count('role_id') }}</span>
                </div>
                <div class="flex justify-between items-center py-1.5">
                    <span class="text-[10px] text-slate-600">Last Updated</span>
                    <span class="text-[11px] font-bold text-slate-700">{{ \App\Models\RoleDashboardMapping::latest()->first()?->updated_at?->format('M d') ?? 'N/A' }}</span>
                </div>
            </div>
            <button class="w-full py-1.5 bg-teal-600 text-white rounded-lg text-[10px] font-bold hover:bg-teal-700 transition-colors" onclick="window.location.href='{{ route('system.dashboard_mappings.index') }}'">
                <i class="fa-solid fa-sliders mr-1"></i> Configure
            </button>
        </div>
    </div>
</div>

<!-- System Health & Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <!-- Workflow Analytics Chart -->
    <div class="bg-white rounded-xl border border-border shadow-sm p-5">
        <h3 class="text-[13px] font-semibold text-navy mb-4">Nationwide Workflow Distribution</h3>
        <div id="workflowChart"></div>
    </div>

    <!-- Recent Incident Trend -->
    <div class="bg-white rounded-xl border border-border shadow-sm p-5">
        <h3 class="text-[13px] font-semibold text-navy mb-4">30-Day Incident Trend</h3>
        <div id="trendChart"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
            document.getElementById('totalCasesLabel').textContent = totalCases + ' Total Cases';
            
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
                        text: view === 'district' ? 'District' : 'Category',
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
            currentChart = new ApexCharts(document.querySelector("#injuryTypesChart"), options);
            currentChart.render();
        }

        // Initialize with province view
        createChart('province');

        // Handle dropdown change
        document.getElementById('chartViewToggle').addEventListener('change', function(e) {
            currentView = e.target.value;
            createChart(currentView);
        });

        // Workflow Distribution Bar Chart
        const workflowChartOptions = {
            series: [{
                name: 'Cases in Stage',
                data: [
                    {{ $workflowDistribution['PENDING_SUPERVISOR_VERIFICATION'] }},
                    {{ $workflowDistribution['HR_REVIEW'] }},
                    {{ $workflowDistribution['CLINICAL_EXECUTIVE_REVIEW'] }},
                    {{ $workflowDistribution['PENDING_PROVINCIAL_APPROVAL'] }},
                    {{ $workflowDistribution['PENDING_MEDICAL_BOARD'] }},
                    {{ $workflowDistribution['MINISTRY_LEVEL'] }}
                ]
            }],
            chart: {
                type: 'bar',
                height: 300,
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
                    horizontal: false,
                    columnWidth: '70%',
                    borderRadius: 6,
                    dataLabels: {
                        position: 'top'
                    },
                    distributed: true
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold',
                    colors: ['#1e293b']
                }
            },
            xaxis: {
                categories: ['Supervisor', 'HR Review', 'Clinical Exec', 'Provincial', 'Medical Board', 'Ministry'],
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
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 600,
                        colors: '#475569'
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
            colors: ['#0d7a3d', '#0f8c47', '#119e51', '#13b05b', '#15c265', '#1dd46f'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.4,
                    gradientToColors: ['#1bb74d', '#1dd46f', '#20e87f', '#23fa89', '#26ff93', '#29ffa0'],
                    inverseColors: false,
                    opacityFrom: 0.95,
                    opacityTo: 0.85,
                    stops: [0, 100]
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
        
        const workflowChart = new ApexCharts(document.querySelector("#workflowChart"), workflowChartOptions);
        workflowChart.render();

        // 30-Day Trend Line Chart
        const recentData = @json($recentIncidents);
        const labels = recentData.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const values = recentData.map(d => d.count);
        
        const trendChartOptions = {
            series: [{
                name: 'New Cases',
                data: values.length > 0 ? values : [0]
            }],
            chart: {
                type: 'area',
                height: 300,
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
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#0d7a3d']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.6,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
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
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 600,
                        colors: '#475569'
                    },
                    formatter: function(val) {
                        return Math.floor(val);
                    }
                },
                title: {
                    text: 'New Cases',
                    style: {
                        fontSize: '12px',
                        fontWeight: 600,
                        color: '#475569'
                    }
                }
            },
            colors: ['#0d7a3d'],
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
                        return val + ' new cases';
                    }
                }
            },
            markers: {
                size: 4,
                colors: ['#0d7a3d'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 6
                }
            }
        };
        
        const trendChart = new ApexCharts(document.querySelector("#trendChart"), trendChartOptions);
        trendChart.render();
    });
</script>

<!-- Real-Time Dashboard Updates -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateRange = new URLSearchParams(window.location.search).get('date_range') || 'all';
    const apiUrl = "{{ route('api.super_admin_dashboard_data') }}?date_range=" + dateRange;
    const pollInterval = 30000; // 30 seconds
    let pollIntervalId = null;

    // Function to update dashboard with new data
    function updateDashboard(data) {
        if (!data.success) return;

        const stats = data.statistics;
        
        // Update KPI cards
        const cardSelectors = [
            { selector: '[data-stat="total"]', value: stats.total_incidents },
            { selector: '[data-stat="active"]', value: stats.active_workflows },
            { selector: '[data-stat="resolved"]', value: stats.resolved_incidents },
            { selector: '[data-stat="rejected"]', value: stats.rejected_incidents },
        ];

        cardSelectors.forEach(({ selector, value }) => {
            const element = document.querySelector(selector);
            if (element) {
                const numberElement = element.querySelector('p:first-of-type') || element.querySelector('p');
                if (numberElement) {
                    numberElement.textContent = value.toLocaleString();
                }
            }
        });

        // Update timestamp
        const lastUpdated = document.querySelector('#lastUpdatedSuperAdmin');
        if (lastUpdated) {
            lastUpdated.innerHTML = '<i class="fa-solid fa-sync-alt text-[10px] animate-spin"></i> Last updated: ' + data.timestamp;
        }
    }

    // Function to fetch fresh data
    function fetchDashboardData() {
        fetch(apiUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            updateDashboard(data);
        })
        .catch(error => {
            console.error('Error fetching Super Admin dashboard data:', error);
        });
    }

    // Start polling
    function startPolling() {
        pollIntervalId = setInterval(fetchDashboardData, pollInterval);
    }

    // Stop polling
    function stopPolling() {
        if (pollIntervalId) {
            clearInterval(pollIntervalId);
            pollIntervalId = null;
        }
    }

    // Initial fetch
    fetchDashboardData();

    // Start polling when page is visible
    startPolling();

    // Pause polling when page is hidden, resume when visible
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopPolling();
        } else {
            startPolling();
            fetchDashboardData();
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', stopPolling);
});
</script>
@endsection
