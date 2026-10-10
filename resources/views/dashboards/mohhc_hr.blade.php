@extends('layouts.dashboard')

@section('page_title', 'MoHCC HR Dashboard')

@section('header_title', 'MoHCC HR Officer')
@section('header_subtitle', 'Employee Management & HR Operations')

@section('sidebar_content')
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>
<a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand-light transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Dashboard</span>
</a>
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-users w-5 text-center text-slate-700 text-sm"></i>
            Employees
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-list w-4 text-center text-slate-700"></i>
            <span>Employee Records</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-user-plus w-4 text-center text-slate-700"></i>
            <span>New Hire Onboarding</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-briefcase w-4 text-center text-slate-700"></i>
            <span>Employment History</span>
        </a>
    </div>
</div>
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-heart-pulse w-5 text-center text-slate-700 text-sm"></i>
            Wellness Programs
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-file-medical w-4 text-center text-slate-700"></i>
            <span>Health Records</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-syringe w-4 text-center text-slate-700"></i>
            <span>Vaccination Records</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-person-hiking w-4 text-center text-slate-700"></i>
            <span>Wellness Initiatives</span>
        </a>
    </div>
</div>
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-calendar-xmark w-5 text-center text-slate-700 text-sm"></i>
            Leave Management
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-list w-4 text-center text-slate-700"></i>
            <span>Leave Applications</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-check-circle w-4 text-center text-slate-700"></i>
            <span>Approvals Pending</span>
        </a>
    </div>
</div>
<a href="{{ route('support.create_ticket') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-headset w-5 text-center text-slate-700 text-sm"></i>
    <span>IT Support</span>
</a>

<!-- My Support Tickets -->
<a href="{{ route('support.my_tickets') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-ticket w-5 text-center text-slate-700 text-sm"></i>
    <span>My Support Tickets</span>
</a>
<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-red-600 hover:bg-red-50 border-l-[3px] border-transparent hover:border-red-300 transition-all font-medium text-[13px]">
    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
    <span>Logout</span>
</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
@endsection

@section('dashboard_content')

<!-- KPI Cards -->
<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <h2 class="text-[13px] font-bold text-navy">Performance Metrics</h2>
        <div class="flex items-center gap-2">
            <button class="flex items-center gap-2 px-3.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                <i class="fa-regular fa-calendar text-slate-400"></i>Last 30 days
                <i class="fa-solid fa-chevron-down text-slate-400 ml-1 text-xs"></i>
            </button>
            @can('export_reports')
            <button class="flex items-center gap-2 px-3.5 py-1.5 bg-[#0d7a3d] text-white rounded-lg text-xs font-medium hover:bg-[#0a5f30] transition-colors shadow-sm">
                <i class="fa-solid fa-file-export text-xs"></i>Export Report
            </button>
            @endcan
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 md:gap-4">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-1 bg-brand"></div>
        <div class="p-4 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">Total Employees</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">1,247</p>
                <p class="text-[10px] text-slate-500 mt-2">34 this year</p>
            </div>
            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                <x-lucide-users class="w-7 h-7 text-blue-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-1 bg-brand"></div>
        <div class="p-4 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">Leave Requests</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">23</p>
                <p class="text-[10px] text-slate-500 mt-2">Pending approval</p>
            </div>
            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl flex items-center justify-center">
                <x-lucide-calendar-off class="w-7 h-7 text-amber-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-1 bg-brand"></div>
        <div class="p-4 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">Health Compliant</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">1,189</p>
                <p class="text-[10px] text-slate-500 mt-2">95%</p>
            </div>
            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl flex items-center justify-center">
                <x-lucide-shield-check class="w-7 h-7 text-emerald-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-1 bg-brand"></div>
        <div class="p-4 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">Active Roles</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">156</p>
                <p class="text-[10px] text-slate-500 mt-2">Positions filled</p>
            </div>
            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl flex items-center justify-center">
                <x-lucide-briefcase class="w-7 h-7 text-purple-600" stroke-width="1.5" />
            </div>
        </div>
    </div>
        </p>
    </div>

    <div class="bg-white rounded-xl border border-border p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all cursor-default">
        <div class="flex items-center gap-2 text-slate-500 mb-3">
            <i class="fa-solid fa-briefcase text-purple-500"></i>
            <span class="text-[13px] font-medium">Active Roles</span>
        </div>
        <p class="text-3xl font-bold text-purple-600 tracking-tight">156</p>
        <p class="text-[12px] font-semibold text-purple-600 mt-2 flex items-center gap-1">
            <i class="fa-solid fa-check-circle text-xs"></i>Positions
        </p>
    </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mt-6 md:mt-8">
    <div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6">
        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">Employees by Department</h3>
        <div class="relative" style="height: 250px;">
            <canvas id="departmentChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-border shadow-sm p-5">
        <h3 class="text-[13px] font-semibold text-navy mb-4">Health Status</h3>
        <div class="relative" style="height: 250px;">
            <canvas id="healthChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent HR Activities -->
<div class="bg-white rounded-xl border border-border shadow-sm p-5">
    <h3 class="text-[13px] font-semibold text-navy mb-4">Recent HR Activities</h3>
    <div class="space-y-3">
        <div class="flex items-start gap-3 pb-3 border-b border-border">
            <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-[12px] font-medium text-navy">New Hire Onboarded</p>
                <p class="text-[11px] text-slate-500">Jane Dlamini joined as Medical Officer</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ now()->subDays(1)->diffForHumans() }}</p>
            </div>
        </div>
        <div class="flex items-start gap-3 pb-3 border-b border-border">
            <div class="w-2 h-2 rounded-full bg-amber-600 mt-1.5 flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-[12px] font-medium text-navy">Leave Request Pending</p>
                <p class="text-[11px] text-slate-500">23 leave applications awaiting approval</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ now()->diffForHumans() }}</p>
            </div>
        </div>
        <div class="flex items-start gap-3">
            <div class="w-2 h-2 rounded-full bg-emerald-600 mt-1.5 flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-[12px] font-medium text-navy">Wellness Program</p>
                <p class="text-[11px] text-slate-500">Monthly health screening completed</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ now()->subDays(3)->diffForHumans() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Department Chart
        const deptChart = document.getElementById('departmentChart');
        if (deptChart) {
            new Chart(deptChart, {
                type: 'bar',
                data: {
                    labels: ['Medical', 'Nursing', 'Administration', 'Support', 'Management'],
                    datasets: [{
                        label: 'Employees',
                        data: [456, 389, 214, 156, 32],
                        backgroundColor: '#0d7a3d',
                        borderColor: '#0a5f30',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } }
                }
            });
        }

        // Health Chart
        const healthChart = document.getElementById('healthChart');
        if (healthChart) {
            new Chart(healthChart, {
                type: 'doughnut',
                data: {
                    labels: ['Compliant', 'Pending', 'At Risk'],
                    datasets: [{
                        data: [1189, 45, 13],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    });
</script>
@endsection
