@extends('layouts.dashboard')

@section('page_title', 'PMD Dashboard')

@section('header_title', 'Provincial Medical Director')
@section('header_subtitle', 'Provincial Operations & Oversight')

@section('sidebar_content')
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>
<a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand-light transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Dashboard</span>
</a>
<a href="{{ route('pmd.pending_cases') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
    <i class="fa-solid fa-list-check w-5 text-center text-slate-700 text-sm"></i>
    <span>All Pending Cases</span>
</a>
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-chart-line w-5 text-center text-slate-700 text-sm"></i>
            Operations
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-building w-4 text-center text-slate-700"></i>
            <span>Districts Overview</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-chart-bar w-4 text-center text-slate-700"></i>
            <span>Provincial Reports</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-users w-4 text-center text-slate-700"></i>
            <span>DMO Approvals</span>
        </a>
    </div>
</div>
<a href="{{ route('support.create_ticket') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
    <i class="fa-solid fa-headset w-5 text-center text-slate-700 text-sm"></i>
    <span>IT Support</span>
</a>

<!-- My Support Tickets -->
<a href="{{ route('support.my_tickets') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-[#1bb74d] transition-all font-medium text-[13px]">
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
        <h2 class="text-lg md:text-xl lg:text-2xl font-bold text-navy">Performance Metrics</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
            <!-- Date Range Picker -->
            <div class="relative" x-data="{ datePickerOpen: false, selectedRange: '{{ $selectedRange }}' }">
                <button @click="datePickerOpen = !datePickerOpen" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 bg-white border border-slate-200 rounded-lg text-xs md:text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors shadow-sm w-full sm:w-auto">
                    <i class="fa-regular fa-calendar text-slate-400"></i>
                    <span x-text="selectedRange === 'all_time' ? 'All Time' : selectedRange === '30' ? 'Last 30 Days' : selectedRange === '7' ? 'Last 7 Days' : 'Last 90 Days'" class="truncate"></span>
                    <i class="fa-solid fa-chevron-down text-slate-400 text-xs md:text-sm"></i>
                </button>
                
                <div x-show="datePickerOpen" 
                     @click.outside="datePickerOpen = false"
                     class="absolute right-0 mt-2 w-40 md:w-48 bg-white rounded-lg border border-border shadow-lg z-50">
                    <div class="p-3 space-y-2">
                        <button @click="window.location.href = '{{ route('dashboards.pmd') }}?range=all_time'" 
                                :class="selectedRange === 'all_time' ? 'bg-brand text-white' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'"
                                class="w-full text-left px-3 py-2 md:py-2.5 rounded text-xs md:text-sm font-medium transition-colors">
                            All Time
                        </button>
                        <button @click="window.location.href = '{{ route('dashboards.pmd') }}?range=90'"
                                :class="selectedRange === '90' ? 'bg-brand text-white' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'"
                                class="w-full text-left px-3 py-2 md:py-2.5 rounded text-xs md:text-sm font-medium transition-colors">
                            Last 90 Days
                        </button>
                        <button @click="window.location.href = '{{ route('dashboards.pmd') }}?range=30'"
                                :class="selectedRange === '30' ? 'bg-brand text-white' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'"
                                class="w-full text-left px-3 py-2 md:py-2.5 rounded text-xs md:text-sm font-medium transition-colors">
                            Last 30 Days
                        </button>
                        <button @click="window.location.href = '{{ route('dashboards.pmd') }}?range=7'"
                                :class="selectedRange === '7' ? 'bg-brand text-white' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'"
                                class="w-full text-left px-3 py-2 md:py-2.5 rounded text-xs md:text-sm font-medium transition-colors">
                            Last 7 Days
                        </button>
                    </div>
                </div>
            </div>

            @can('export_reports')
            <button class="flex items-center justify-center gap-2 px-3 md:px-4 py-2 md:py-2.5 bg-[#0d7a3d] text-white rounded-lg text-xs md:text-sm font-medium hover:bg-[#0a5f30] transition-colors shadow-sm w-full sm:w-auto">
                <i class="fa-solid fa-file-export text-xs md:text-sm"></i>
                <span class="hidden sm:inline">Export Report</span>
                <span class="sm:hidden">Export</span>
            </button>
            @endcan
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 md:h-1 bg-brand"></div>
        <div class="p-4 md:p-5 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-xs md:text-sm font-semibold text-slate-600 uppercase tracking-wide">Districts</span>
                <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-1 md:mt-2">{{ $incidentsByDistrict->count() }}</p>
                <p class="text-xs md:text-sm text-slate-500 mt-2">Active Districts</p>
            </div>
            <div class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                <x-lucide-building-2 class="w-6 h-6 md:w-7 md:h-7 text-blue-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="pending">
        <div class="h-0.5 md:h-1 bg-brand"></div>
        <div class="p-4 md:p-5 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-xs md:text-sm font-semibold text-slate-600 uppercase tracking-wide">Pending Review</span>
                <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-1 md:mt-2">{{ $statistics['pending_review'] ?? 0 }}</p>
                <p class="text-xs md:text-sm text-slate-500 mt-2">Awaiting Action</p>
            </div>
            <div class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg flex items-center justify-center">
                <x-lucide-alert-circle class="w-6 h-6 md:w-7 md:h-7 text-amber-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="total">
        <div class="h-0.5 md:h-1 bg-brand"></div>
        <div class="p-4 md:p-5 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-xs md:text-sm font-semibold text-slate-600 uppercase tracking-wide">Total Cases</span>
                <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-1 md:mt-2">{{ $statistics['total_incidents'] ?? 0 }}</p>
                <p class="text-xs md:text-sm text-slate-500 mt-2">All Time</p>
            </div>
            <div class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg flex items-center justify-center">
                <x-lucide-briefcase class="w-6 h-6 md:w-7 md:h-7 text-emerald-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="reviewed">
        <div class="h-0.5 md:h-1 bg-brand"></div>
        <div class="p-4 md:p-5 flex items-start justify-between gap-4">
            <div class="flex-1">
                <span class="text-xs md:text-sm font-semibold text-slate-600 uppercase tracking-wide">Reviewed</span>
                <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-1 md:mt-2">{{ $statistics['reviewed'] ?? 0 }}</p>
                <p class="text-xs md:text-sm text-slate-500 mt-2">
                    @if(($statistics['total_incidents'] ?? 0) > 0)
                        {{ round((($statistics['reviewed'] ?? 0) / $statistics['total_incidents']) * 100) }}% Complete
                    @else
                        0% Complete
                    @endif
                </p>
            </div>
            <div class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg flex items-center justify-center">
                <x-lucide-check-circle-2 class="w-6 h-6 md:w-7 md:h-7 text-purple-600" stroke-width="1.5" />
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mt-6 md:mt-8">
    <div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 md:mb-6">
            <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy">Cases Distribution</h3>
            <div class="flex gap-2 flex-wrap" id="chartToggle">
                <button data-view="district" class="px-2 md:px-3 py-1.5 md:py-2 rounded text-xs md:text-sm font-medium bg-brand text-white transition-colors toggle-btn active">
                    <i class="fa-solid fa-map-location-dot text-xs mr-1"></i><span class="hidden sm:inline">By District</span><span class="sm:hidden">District</span>
                </button>
                <button data-view="facility" class="px-2 md:px-3 py-1.5 md:py-2 rounded text-xs md:text-sm font-medium bg-slate-200 text-slate-700 hover:bg-slate-300 transition-colors toggle-btn">
                    <i class="fa-solid fa-building text-xs mr-1"></i><span class="hidden sm:inline">By Facility</span><span class="sm:hidden">Facility</span>
                </button>
            </div>
        </div>
        <div id="distributionChart" style="height: 300px;"></div>
    </div>
    <div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6">
        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">Incident Type Distribution</h3>
        <div id="incidentTypeChart" style="height: 300px;"></div>
    </div>
</div>

<!-- Recent Cases Table -->
<div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6 mt-6 md:mt-8">
    <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">Recent Cases Escalated to PMD</h3>
    @if($recentIncidents->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-xs md:text-sm">
                <thead>
                    <tr class="border-b border-border text-slate-500">
                        <th class="text-left py-3 px-2 md:px-4 font-semibold">Reference</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden sm:table-cell">Employee</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden md:table-cell">Facility</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden lg:table-cell">District</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold">Date</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden sm:table-cell">Status</th>
                        <th class="text-center py-3 px-2 md:px-4 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentIncidents as $incident)
                        <tr class="border-b border-border hover:bg-slate-50">
                            <td class="py-3 px-2 md:px-4">
                                <span class="font-medium text-brand text-xs md:text-sm">{{ $incident->reference_number }}</span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden sm:table-cell">
                                <span class="text-slate-700 text-xs md:text-sm">{{ $incident->first_name }} {{ $incident->surname }}</span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden md:table-cell">
                                <span class="text-slate-600 text-xs md:text-sm">{{ $incident->facility->name ?? 'N/A' }}</span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden lg:table-cell">
                                <span class="text-slate-600 text-xs md:text-sm">{{ $incident->facility->district->name ?? 'N/A' }}</span>
                            </td>
                            <td class="py-3 px-2 md:px-4">
                                <span class="text-slate-500 text-xs md:text-sm">{{ $incident->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden sm:table-cell">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    <i class="fa-solid fa-clock text-xs mr-1"></i>
                                    <span class="hidden md:inline">Pending Review</span><span class="md:hidden">Pending</span>
                                </span>
                            </td>
                            <td class="py-3 px-2 md:px-4 text-center">
                                <a href="{{ route('pmd.review_case', $incident) }}" class="inline-flex items-center gap-1 px-2 md:px-3 py-1 md:py-1.5 bg-brand text-white rounded text-xs md:text-sm font-medium hover:bg-brand-dark transition-colors">
                                    <i class="fa-solid fa-eye text-xs md:text-sm"></i>
                                    <span class="hidden sm:inline">Review</span><span class="sm:hidden">View</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8 md:py-12 text-slate-400">
            <i class="fa-solid fa-inbox text-4xl md:text-5xl mb-4"></i>
            <p class="text-sm md:text-base">No cases to display</p>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    let distributionChart = null;

    function renderDistributionChart(view) {
        // Destroy existing chart if it exists
        if (distributionChart) {
            distributionChart.destroy();
        }

        const districtData = @json($incidentsByDistrict);
        const facilityData = @json($incidentsByFacility);
        
        console.log('District Data:', districtData);
        console.log('Facility Data:', facilityData);
        
        let chartData, chartLabels, isHorizontal;
        
        if (view === 'district') {
            chartLabels = Object.keys(districtData);
            chartData = Object.values(districtData);
            isHorizontal = false;
        } else {
            chartLabels = Object.keys(facilityData);
            chartData = Object.values(facilityData);
            isHorizontal = true;
        }

        // Ensure labels are strings
        chartLabels = chartLabels.map(label => String(label));
        
        console.log('Chart Labels:', chartLabels);
        console.log('Chart Data:', chartData);
        console.log('Is Horizontal:', isHorizontal);

        if (chartLabels.length === 0) {
            document.getElementById('distributionChart').innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 250px; color: #999;">No data available</div>';
            return;
        }

        let xaxisConfig = {};
        let yaxisConfig = {};

        if (isHorizontal) {
            // Horizontal bars: numbers on X-axis, facility names on Y-axis
            xaxisConfig = {
                type: 'numeric',
                title: { text: 'Number of Cases' },
                labels: {
                    style: { fontSize: '11px', fontWeight: 500 },
                    formatter: function(val) { return Number.isInteger(val) ? val : ''; }
                }
            };
            yaxisConfig = {
                type: 'category',
                categories: chartLabels,
                labels: { 
                    style: { fontSize: '11px', fontWeight: 500 },
                    maxWidth: 200
                }
            };
        } else {
            // Vertical bars: district names on X-axis, numbers on Y-axis
            xaxisConfig = {
                type: 'category',
                categories: chartLabels,
                labels: {
                    style: { fontSize: '11px', fontWeight: 500 },
                    rotate: -45,
                    rotateAlways: chartLabels.length > 5
                }
            };
            yaxisConfig = {
                type: 'numeric',
                title: { text: 'Number of Cases' },
                labels: {
                    style: { fontSize: '11px', fontWeight: 500 },
                    formatter: function(val) { return Number.isInteger(val) ? val : ''; }
                }
            };
        }

        const options = {
            series: [{
                name: 'Cases',
                data: chartData
            }],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: { show: true, tools: { download: true } },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            plotOptions: {
                bar: {
                    horizontal: isHorizontal,
                    borderRadius: 6,
                    barHeight: isHorizontal ? '70%' : undefined,
                    columnWidth: isHorizontal ? undefined : '60%'
                }
            },
            dataLabels: {
                enabled: true,
                offsetX: isHorizontal ? 30 : 0,
                offsetY: isHorizontal ? 0 : -20,
                style: { fontSize: '12px', fontWeight: 600, colors: ['#1D4533'] }
            },
            colors: ['#0d7a3d'],
            xaxis: xaxisConfig,
            yaxis: yaxisConfig,
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 3,
                xaxis: { lines: { show: true } }
            },
            tooltip: {
                theme: 'light',
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    const label = w.globals.labels[dataPointIndex];
                    const value = series[seriesIndex][dataPointIndex];
                    return '<div class="px-3 py-2" style="background: white; border: 1px solid #e2e8f0; border-radius: 4px;">' +
                           '<div style="font-weight: 600; margin-bottom: 4px; font-size: 12px;">' + label + '</div>' +
                           '<div style="font-size: 11px;">' + value + ' case(s)</div>' +
                           '</div>';
                }
            }
        };

        distributionChart = new ApexCharts(document.querySelector("#distributionChart"), options);
        distributionChart.render();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize distribution chart on load
        renderDistributionChart('district');

        // Handle toggle buttons
        document.querySelectorAll('#chartToggle .toggle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('#chartToggle .toggle-btn').forEach(b => {
                    b.classList.remove('active', 'bg-brand', 'text-white');
                    b.classList.add('bg-slate-200', 'text-slate-700');
                });
                
                this.classList.add('active', 'bg-brand', 'text-white');
                this.classList.remove('bg-slate-200', 'text-slate-700');
                
                const view = this.getAttribute('data-view');
                renderDistributionChart(view);
            });
        });

        // Incident Type Distribution - Pie Chart
        const incidentTypeData = @json($incidentsByType);
        const typeLabels = Object.keys(incidentTypeData);
        const typeValues = Object.values(incidentTypeData);
        
        if (document.getElementById('incidentTypeChart') && typeLabels.length > 0) {
            const typeOptions = {
                series: typeValues,
                chart: {
                    type: 'donut',
                    height: 250
                },
                labels: typeLabels,
                colors: ['#0d7a3d', '#1a9d5d', '#2eb873', '#4dc28f', '#6aca9f', '#87d2af', '#a4dbbf', '#c1e4cf'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                name: {
                                    fontSize: '11px'
                                },
                                value: {
                                    fontSize: '12px',
                                    fontWeight: 600
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '11px',
                        fontWeight: 600
                    }
                },
                legend: {
                    position: 'bottom',
                    fontSize: '11px',
                    fontFamily: 'inherit'
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: function(val) {
                            return val + ' case(s)';
                        }
                    }
                }
            };

            const incidentTypeChart = new ApexCharts(document.querySelector("#incidentTypeChart"), typeOptions);
            incidentTypeChart.render();
        }
    });
</script>

<!-- Real-Time Dashboard Updates -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateRange = new URLSearchParams(window.location.search).get('date_range') || 'all';
    const apiUrl = "{{ route('api.pmd_dashboard_data') }}?date_range=" + dateRange;
    const pollInterval = 30000; // 30 seconds
    let pollIntervalId = null;

    // Function to update dashboard with new data
    function updateDashboard(data) {
        if (!data.success) return;

        const stats = data.statistics;
        
        // Update KPI cards
        const cardSelectors = [
            { selector: '[data-stat="pending"]', value: stats.pending_cases },
            { selector: '[data-stat="total"]', value: stats.total_cases },
            { selector: '[data-stat="reviewed"]', value: stats.reviewed_cases },
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
        const lastUpdated = document.querySelector('#lastUpdatedPMD');
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
            console.error('Error fetching PMD dashboard data:', error);
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
