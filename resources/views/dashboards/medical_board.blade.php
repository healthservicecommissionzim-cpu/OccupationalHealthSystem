@extends('layouts.dashboard')

@section('page_title', 'Medical Board Dashboard')

@section('header_title', 'Medical Board Member')
@section('header_subtitle', 'Case Review & Board Decisions')

@section('sidebar_content')
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>
<a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand-light transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Dashboard</span>
</a>
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-gavel w-5 text-center text-slate-700 text-sm"></i>
            Board Reviews
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-inbox w-4 text-center text-slate-700"></i>
            <span>Cases for Review</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-thumbs-up w-4 text-center text-slate-700"></i>
            <span>Approved Cases</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-clock w-4 text-center text-slate-700"></i>
            <span>Pending Board Vote</span>
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
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <h2 class="text-[13px] font-bold text-navy">Performance Metrics</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
            <button class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 bg-white border border-slate-200 rounded-lg text-xs md:text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors shadow-sm w-full sm:w-auto">
                <i class="fa-regular fa-calendar text-slate-400"></i>Last 30 days
                <i class="fa-solid fa-chevron-down text-slate-400 text-xs md:text-sm"></i>
            </button>
            @can('export_reports')
            <button class="flex items-center justify-center gap-2 px-3 md:px-4 py-2 md:py-2.5 bg-[#0d7a3d] text-white rounded-lg text-xs md:text-sm font-medium hover:bg-[#0a5f30] transition-colors shadow-sm w-full sm:w-auto">
                <i class="fa-solid fa-file-export text-xs md:text-sm"></i>
                <span class="hidden sm:inline">Export Report</span>
                <span class="sm:hidden">Export</span>
            </button>
            @endcan
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 md:gap-4">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Cases for Review</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5">15</p>
                <p class="text-xs text-slate-500 mt-0.5">Pending decision</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg flex items-center justify-center">
                <x-lucide-inbox class="w-4 h-4 text-amber-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Approved</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5">48</p>
                <p class="text-xs text-slate-500 mt-0.5">This month</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg flex items-center justify-center">
                <x-lucide-check-circle-2 class="w-4 h-4 text-emerald-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Declined</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5">5</p>
                <p class="text-xs text-slate-500 mt-0.5">This month</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-red-50 to-red-100 rounded-lg flex items-center justify-center">
                <x-lucide-x-circle class="w-4 h-4 text-red-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Board Members</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5">7</p>
                <p class="text-xs text-slate-500 mt-0.5">Active</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg flex items-center justify-center">
                <x-lucide-users class="w-4 h-4 text-purple-600" stroke-width="1.5" />
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mt-6 md:mt-8">
    <div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6">
        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">Board Decisions</h3>
        <div class="relative" style="height: 250px;">
            <canvas id="decisionsChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6">
        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">Case Categories</h3>
        <div class="relative" style="height: 250px;">
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Board Cases -->
<div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6 mt-6 md:mt-8">
    <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">Recent Board Cases</h3>
    <div class="space-y-3">
        <div class="flex items-start gap-3 pb-3 border-b border-border">
            <div class="w-2 h-2 rounded-full bg-amber-600 mt-1.5 flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-[12px] font-medium text-navy">BOARD-2026-045</p>
                <p class="text-[11px] text-slate-500">Occupational compensation case - Pending board vote</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ now()->subDays(2)->diffForHumans() }}</p>
            </div>
        </div>
        <div class="flex items-start gap-3 pb-3 border-b border-border">
            <div class="w-2 h-2 rounded-full bg-emerald-600 mt-1.5 flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-[12px] font-medium text-navy">BOARD-2026-044</p>
                <p class="text-[11px] text-slate-500">Compensation approved - Amount authorized</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ now()->subDays(4)->diffForHumans() }}</p>
            </div>
        </div>
        <div class="flex items-start gap-3">
            <div class="w-2 h-2 rounded-full bg-red-600 mt-1.5 flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-[12px] font-medium text-navy">BOARD-2026-043</p>
                <p class="text-[11px] text-slate-500">Insufficient evidence - Claim declined</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ now()->subDays(6)->diffForHumans() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Decisions Chart
        const decisionsChart = document.getElementById('decisionsChart');
        if (decisionsChart) {
            new Chart(decisionsChart, {
                type: 'bar',
                data: {
                    labels: ['Approved', 'Declined', 'Pending'],
                    datasets: [{
                        label: 'Cases',
                        data: [48, 5, 15],
                        backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
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

        // Categories Chart
        const categoriesChart = document.getElementById('categoriesChart');
        if (categoriesChart) {
            new Chart(categoriesChart, {
                type: 'doughnut',
                data: {
                    labels: ['Injury', 'Illness', 'Occupational Disease', 'Other'],
                    datasets: [{
                        data: [35, 22, 8, 3],
                        backgroundColor: ['#0d7a3d', '#3b82f6', '#8b5cf6', '#f59e0b'],
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
