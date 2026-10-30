@extends('layouts.dashboard')

@section('page_title', 'HR Dashboard')

@section('header_title', 'HR Management')
@section('header_subtitle', 'Incident Review, Employee Oversight & HR Operations')

@section('sidebar_content')
<!-- OVERVIEW SECTION -->
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-muted uppercase tracking-widest">Overview</div>
<a href="{{ route('dashboards.hr') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-ink text-sm"></i>
    <span>Dashboard</span>
</a>

<!-- All Incidents - Standalone Link -->
<a href="{{ route('incidents.hr_all') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-triangle-exclamation w-5 text-center text-ink text-sm"></i>
    <span>All Incidents</span>
</a>

<!-- Pending Review - Standalone Link -->
<a href="{{ route('incidents.hr_pending_review') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-clock w-5 text-center text-ink text-sm"></i>
    <span>Pending Review</span>
</a>

<!-- Reports Section -->
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-file-chart-line w-5 text-center text-ink text-sm"></i>
            Reports
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        @if(auth()->user()->hasRole('district_hr'))
        <a href="{{ route('hr.incident_department') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-transparent hover:border-brand transition-all text-[12px]">
            <i class="fa-solid fa-building w-4 text-center text-ink"></i>
            <span>Incident by Department</span>
        </a>
        <a href="{{ route('hr.incident_facility') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-transparent hover:border-brand transition-all text-[12px]">
            <i class="fa-solid fa-hospital w-4 text-center text-ink"></i>
            <span>Incident by Facility</span>
        </a>
        @endif
    </div>
</div>

<!-- Leave Management -->
<a href="#" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-calendar-days w-5 text-center text-ink text-sm"></i>
    <span>Leave Management</span>
</a>

<!-- IT Support -->
<a href="{{ route('support.create_ticket') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-headset w-5 text-center text-ink text-sm"></i>
    <span>IT Support</span>
</a>

@endsection

@section('dashboard_content')
<!-- KPI Cards -->
<div class="mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <h2 class="text-sm font-bold text-ink">Performance Metrics</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 bg-surface border border-line rounded-lg text-xs md:text-sm font-medium text-ink-soft hover:bg-canvas transition-colors shadow-sm w-full sm:w-auto">
                    <i class="fa-regular fa-calendar text-muted"></i>
                    <span id="dateRangeLabel">All time</span>
                    <i class="fa-solid fa-chevron-down text-muted text-xs md:text-sm"></i>
                </button>
                <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-40 md:w-48 bg-surface border border-line rounded-lg shadow-lg z-10">
                    <a href="{{ route('dashboards.hr', ['date_range' => '7days']) }}" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-ink-soft hover:bg-canvas border-b border-line {{ $dateRange === '7days' ? 'bg-canvas' : '' }}">
                        <i class="fa-solid fa-check text-brand {{ $dateRange === '7days' ? '' : 'opacity-0' }}"></i>
                        <span>Last 7 days</span>
                    </a>
                    <a href="{{ route('dashboards.hr', ['date_range' => '30days']) }}" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-ink-soft hover:bg-canvas border-b border-line {{ $dateRange === '30days' ? 'bg-canvas' : '' }}">
                        <i class="fa-solid fa-check text-brand {{ $dateRange === '30days' ? '' : 'opacity-0' }}"></i>
                        <span>Last 30 days</span>
                    </a>
                    <a href="{{ route('dashboards.hr', ['date_range' => '90days']) }}" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-ink-soft hover:bg-canvas border-b border-line {{ $dateRange === '90days' ? 'bg-canvas' : '' }}">
                        <i class="fa-solid fa-check text-brand {{ $dateRange === '90days' ? '' : 'opacity-0' }}"></i>
                        <span>Last 90 days</span>
                    </a>
                    <a href="{{ route('dashboards.hr', ['date_range' => 'all']) }}" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-ink-soft hover:bg-canvas {{ $dateRange === 'all' ? 'bg-canvas' : '' }}">
                        <i class="fa-solid fa-check text-brand {{ $dateRange === 'all' ? '' : 'opacity-0' }}"></i>
                        <span>All time</span>
                    </a>
                </div>
            </div>
            @can('export_reports')
            <button class="flex items-center justify-center gap-2 px-3 md:px-4 py-2 md:py-2.5 bg-brand text-surface rounded-lg text-xs md:text-sm font-medium hover:bg-brand-deep transition-colors shadow-sm w-full sm:w-auto">
                <i class="fa-solid fa-file-export text-xs md:text-sm"></i>
                <span class="hidden sm:inline">Export Report</span>
                <span class="sm:hidden">Export</span>
            </button>
            @endcan
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
    <!-- Total Cases -->
    <div class="bg-surface rounded-lg border border-line shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="total">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-muted uppercase tracking-wide">Total Cases</span>
                <p class="text-xl font-bold text-ink mt-0.5">{{ number_format($statistics['total_cases'] ?? 0) }}</p>
                <p class="text-xs text-muted mt-0.5">HR-relevant cases</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg flex items-center justify-center">
                <x-lucide-briefcase class="w-4 h-4 text-purple-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <!-- Pending HR Review -->
    <div class="bg-surface rounded-lg border border-line shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="pending">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-muted uppercase tracking-wide">Pending Review</span>
                <p class="text-xl font-bold text-ink mt-0.5">{{ number_format($statistics['pending_review'] ?? 0) }}</p>
                <p class="text-xs text-muted mt-0.5">Awaiting your action</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg flex items-center justify-center">
                <x-lucide-inbox class="w-4 h-4 text-amber-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <!-- In Workflow -->
    <div class="bg-surface rounded-lg border border-line shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="active">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-muted uppercase tracking-wide">In Workflow</span>
                <p class="text-xl font-bold text-ink mt-0.5">{{ number_format($statistics['active_incidents'] ?? 0) }}</p>
                <p class="text-xs text-muted mt-0.5">In progress</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                <x-lucide-arrow-right-circle class="w-4 h-4 text-blue-600" stroke-width="1.5" />
            </div>
        </div>
    </div>

    <!-- Resolved Cases -->
    <div class="bg-surface rounded-lg border border-line shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="resolved">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-muted uppercase tracking-wide">Resolved Cases</span>
                <p class="text-xl font-bold text-ink mt-0.5">{{ number_format($statistics['resolved_incidents'] ?? 0) }}</p>
                <p class="text-xs text-muted mt-0.5">
                    {{ $statistics['total_cases'] > 0 ? round(($statistics['resolved_incidents'] / $statistics['total_cases']) * 100, 1) : 0 }}% success
                </p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg flex items-center justify-center">
                <x-lucide-check-circle-2 class="w-4 h-4 text-emerald-600" stroke-width="1.5" />
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 gap-4 md:gap-6 mb-6">
    <div class="bg-surface rounded-lg border border-line shadow-sm p-4 md:p-6">
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <div class="flex items-center gap-3">
                <h3 class="text-sm md:text-base lg:text-lg font-semibold text-ink">Incidents by Type</h3>
                <!-- View Toggle Dropdown -->
                <select id="injuryTypeViewToggle" class="px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-[12px] font-medium text-slate-700 hover:border-brand focus:border-brand focus:ring-1 focus:ring-brand transition-colors cursor-pointer">
                    <option value="type">BY TYPE</option>
                    <option value="facility">BY FACILITY</option>
                </select>
            </div>
            <span class="bg-brand bg-opacity-10 text-brand text-[11px] font-bold px-3 py-1 rounded-full" id="totalInjuryCasesLabel">
                {{ count($incidentsByInjuryType) }} Types
            </span>
        </div>
        <div id="injuryTypesChart" style="height: 380px;"></div>
    </div>
</div>

<!-- Assigned Cases - Pending Review Card -->
@if($pendingReviewIncidents->count() > 0)
@php
    $totalCount = $pendingReviewIncidents->count();
@endphp
<div class="bg-surface rounded-lg border border-line shadow-sm mb-6 overflow-hidden" x-data="{ expanded: false }">
    <!-- Header Section -->
    <div class="p-4 md:p-6 border-b border-line flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-brand-soft flex items-center justify-center">
                <i class="fa-solid fa-list-check text-brand text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm md:text-base font-semibold text-ink">
                    Assigned Cases - Pending Review
                </h3>
            </div>
        </div>
        <button @click="expanded = !expanded" class="px-3 py-1.5 bg-brand text-surface rounded-lg text-xs font-semibold hover:bg-brand-deep transition-colors whitespace-nowrap">
            <span x-show="!expanded">View All</span>
            <span x-show="expanded">Show Less</span>
        </button>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-line bg-canvas">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider">Reference</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider hidden sm:table-cell">Employee</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider hidden md:table-cell">Facility</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider hidden xl:table-cell">Type</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider">Date</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider hidden lg:table-cell">Status</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingReviewIncidents as $incident)
                    @php
                        $displayCount = $loop->iteration;
                        $status = $incident->status ?? 'PENDING_HR_REVIEW';
                        $statusLabel = '';
                        $statusClass = '';
                        
                        switch($status) {
                            case 'PENDING_HR_REVIEW':
                            case 'ESCALATED_TO_HR':
                                $statusLabel = 'Pending';
                                $statusClass = 'bg-transparent text-orange-600';
                                break;
                            case 'HR_REVIEW_IN_PROGRESS':
                                $statusLabel = 'In Progress';
                                $statusClass = 'bg-blue-50 text-blue-700';
                                break;
                            case 'APPROVED_BY_HR':
                                $statusLabel = 'Approved';
                                $statusClass = 'bg-emerald-50 text-emerald-700';
                                break;
                            default:
                                $statusLabel = 'Pending';
                                $statusClass = 'bg-transparent text-orange-600';
                        }
                    @endphp
                    <tr x-show="expanded || {{ $displayCount }} <= 5" class="border-b border-line hover:bg-canvas transition-colors">
                        <td class="py-3 px-4 text-xs md:text-sm font-semibold text-brand">{{ $incident->reference_number }}</td>
                        <td class="py-3 px-4 text-xs md:text-sm text-ink-soft hidden sm:table-cell">{{ $incident->first_name }} {{ $incident->surname }}</td>
                        <td class="py-3 px-4 text-xs md:text-sm text-ink-soft hidden md:table-cell truncate">{{ $incident->facility?->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-xs md:text-sm text-ink-soft hidden xl:table-cell">{{ $incident->incidentType?->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-xs md:text-sm text-muted">{{ $incident->created_at->format('M d, Y') }}</td>
                        <td class="py-3 px-4 text-center hidden lg:table-cell">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                <i class="fa-solid fa-circle" style="font-size: 5px;"></i>
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('incidents.hr_dossier', ['incident' => $incident]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand text-surface rounded text-xs font-semibold hover:bg-brand-deep transition-colors">
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
    <div class="px-4 md:px-6 py-3 text-center border-t border-line bg-canvas">
        <p class="text-xs md:text-sm text-muted font-medium">
            Showing <span x-text="expanded ? '{{ $totalCount }}' : Math.min(5, {{ $totalCount }})"></span> of <span class="inline-block px-2.5 py-0.5 rounded-full bg-brand-soft text-brand font-semibold">{{ $totalCount }}</span> cases
        </p>
    </div>
    @endif
</div>
@else
<div class="bg-surface rounded-lg border border-line shadow-sm p-4 md:p-6 mb-6">
    <div class="text-center py-8 md:py-12">
        <i class="fa-solid fa-inbox text-line text-4xl md:text-5xl mb-4"></i>
        <p class="text-sm md:text-base text-muted">No incidents pending review</p>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    let facilityChartInstance = null;
    let injuryChartInstance = null;
    let departmentChartInstance = null;
    let currentChartType = 'facility';

    function createInjuryChart() {
        const injuryData = @json($incidentsByInjuryType ?? []);
        
        // Split long labels into 2 lines
        const injuryLabels = Object.keys(injuryData).map(label => {
            if (label.length > 15) {
                const words = label.split(' ');
                const mid = Math.ceil(words.length / 2);
                const line1 = words.slice(0, mid).join(' ');
                const line2 = words.slice(mid).join(' ');
                return line1 + '\n' + line2;
            }
            return label;
        });
        const injuryValues = Object.values(injuryData);
        
        // Much larger height to accommodate wrapped labels
        const calculatedHeight = 500;
        
        // Calculate max value and round up to nearest integer
        const maxValue = Math.max(...injuryValues, 0);
        const maxRounded = Math.ceil(maxValue) || 5;
        
        // Generate gradient colors for bars - all starting with same green
        const colors = injuryLabels.map((_, index) => {
            return '#10b981'; // Bright green for all bars
        });
        
        const injuryChartOptions = {
            series: [{
                name: 'Cases',
                data: injuryValues
            }],
            chart: {
                type: 'bar',
                height: Math.max(300, Math.min(injuryLabels.length * 30, 380)),
                sparkline: {
                    enabled: false
                },
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 600
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4,
                    barHeight: '75%',
                    distributed: true,
                    dataLabels: {
                        position: 'right'
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: colors,
            xaxis: {
                categories: injuryLabels,
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500,
                        colors: '#64748b'
                    },
                    formatter: function(val) {
                        return Math.floor(val);
                    }
                },
                min: 0,
                max: maxRounded,
                tickAmount: Math.min(maxRounded, 10),
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500,
                        colors: '#64748b'
                    },
                    maxHeight: 100
                },
                opposite: false
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 2,
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                padding: {
                    left: 4,
                    right: 4,
                    top: 8,
                    bottom: 2
                }
            },
            fill: {
                type: 'solid',
                opacity: 1
            },
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                },
                active: {
                    filter: {
                        type: 'none'
                    }
                }
            },
            tooltip: {
                enabled: true,
                theme: 'light',
                style: {
                    fontSize: '12px'
                },
                y: {
                    formatter: function(val) {
                        return Math.floor(val) + ' case' + (Math.floor(val) !== 1 ? 's' : '');
                    }
                },
                marker: {
                    show: false
                }
            },
            legend: {
                show: false
            }
        };
        
        return new ApexCharts(document.querySelector("#facilityChart"), injuryChartOptions);
    }

    function createDepartmentChart() {
        const departmentData = @json($incidentsByDepartment ?? []);
        const departmentLabels = Object.keys(departmentData).map(label => 
            label.length > 25 ? label.substring(0, 25) + '...' : label
        );
        const departmentValues = Object.values(departmentData);
        
        // Calculate height based on number of items - responsive but compact
        const itemCount = departmentValues.length;
        const calculatedHeight = Math.max(250, itemCount * 20 + 50);
        
        // Calculate max value and round up to nearest even number
        const maxValue = Math.max(...departmentValues, 0);
        const maxEven = Math.ceil(maxValue / 2) * 2 || 2;
        
        const departmentChartOptions = {
            series: [{
                name: 'Cases',
                data: departmentValues
            }],
            chart: {
                type: 'bar',
                height: calculatedHeight,
                toolbar: {
                    show: true,
                    tools: {
                        download: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 6,
                    columnWidth: '45%',
                    distributed: false,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: false,
                offsetX: 30,
                style: {
                    fontSize: '12px',
                    fontWeight: 600,
                    colors: ['#1D4533']
                }
            },
            colors: ['#0d7a3d'],
            xaxis: {
                categories: departmentLabels,
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500
                    },
                    formatter: function(val) {
                        return Math.floor(val);
                    }
                },
                min: 0,
                max: maxEven,
                tickAmount: maxEven
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 3,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'vertical',
                    shadeIntensity: 0.3,
                    gradientToColors: ['#1bb74d'],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return val + ' cases';
                    }
                }
            }
        };
        
        return new ApexCharts(document.querySelector("#facilityChart"), departmentChartOptions);
    }

    function updateChart(type) {
        if (facilityChartInstance) {
            facilityChartInstance.destroy();
            facilityChartInstance = null;
        }
        if (injuryChartInstance) {
            injuryChartInstance.destroy();
            injuryChartInstance = null;
        }
        if (departmentChartInstance) {
            departmentChartInstance.destroy();
            departmentChartInstance = null;
        }

        updateTotalAndLabel(type);

        if (type === 'injury') {
            injuryChartInstance = createInjuryChart();
            injuryChartInstance.render();
        } else if (type === 'department') {
            departmentChartInstance = createDepartmentChart();
            departmentChartInstance.render();
        }
    }

        document.addEventListener('DOMContentLoaded', function() {
        // Initialize with injury chart only
        const injuryData = @json($incidentsByInjuryType ?? []);
        
        // Incident Status Donut Chart
        const statusData = @json($incidentsByStatus);
        const statusLabels = Object.keys(statusData).map(status => 
            status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
        );
        const statusValues = Object.values(statusData);
        
        const statusChartOptions = {
            series: statusValues,
            chart: {
                type: 'donut',
                height: 200,
                toolbar: {
                    show: true,
                    tools: {
                        download: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 1000
                }
            },
            labels: statusLabels,
            colors: ['#0d7a3d', '#1bb74d', '#0f8650', '#16a34a', '#22c55e', '#84cc16', '#a3e635', '#bfdbfe'],
            dataLabels: {
                enabled: false,
                formatter: function(val, opts) {
                    return opts.w.config.series[opts.seriesIndex];
                },
                style: {
                    fontSize: '14px',
                    fontWeight: 700,
                    colors: ['#fff']
                },
                dropShadow: {
                    enabled: true,
                    top: 1,
                    left: 1,
                    blur: 1,
                    opacity: 0.5
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '14px',
                                fontWeight: 600,
                                color: '#334155'
                            },
                            value: {
                                show: true,
                                fontSize: '24px',
                                fontWeight: 700,
                                color: '#0f172a',
                                formatter: function(val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Cases',
                                fontSize: '13px',
                                fontWeight: 600,
                                color: '#64748b',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '12px',
                fontWeight: 500,
                labels: {
                    colors: '#334155'
                },
                markers: {
                    width: 10,
                    height: 10,
                    radius: 2
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                }
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['#fff']
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return val + ' cases';
                    }
                }
            }
        };
        
        // Initialize injury chart as default
        injuryChartInstance = createInjuryChart();
        injuryChartInstance.render();

        // Handle window resize to redraw chart when sidebar expands/collapses
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                if (injuryChartInstance) {
                    injuryChartInstance.reflow();
                }
            }, 250);
        });

        // Highlight the current date range
        const urlParams = new URLSearchParams(window.location.search);
        const dateRange = urlParams.get('date_range') || 'all';
        
        // Map date range to button element and label
        const dateRangeMap = {
            '7days': { btn: 'range7days', label: 'Last 7 days' },
            '30days': { btn: 'range30days', label: 'Last 30 days' },
            '90days': { btn: 'range90days', label: 'Last 90 days' },
            'all': { btn: 'rangeAll', label: 'All time' }
        };
        
        const currentRange = dateRangeMap[dateRange] || dateRangeMap['all'];
        document.getElementById('dateRangeLabel').textContent = currentRange.label;
        
        // Highlight selected button
        Object.values(dateRangeMap).forEach(item => {
            document.getElementById(item.btn).classList.remove('bg-slate-50');
            document.getElementById(item.btn).querySelector('i').classList.add('opacity-0');
        });
        
        document.getElementById(currentRange.btn).classList.add('bg-slate-50');
        document.getElementById(currentRange.btn).querySelector('i').classList.remove('opacity-0');
    });
</script>

<!-- Real-Time Dashboard Updates -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateRange = new URLSearchParams(window.location.search).get('date_range') || 'all';
    const apiUrl = "{{ route('api.hr_dashboard_data') }}?date_range=" + dateRange;
    const pollInterval = 30000; // 30 seconds
    let pollIntervalId = null;
    let injuryChartInstance = null;
    let currentInjuryView = 'type';

    // Add CSS for x-axis labels
    const style = document.createElement('style');
    style.textContent = `
        .apexcharts-xaxis-label {
            transform: none !important;
            text-anchor: middle !important;
        }
    `;
    document.head.appendChild(style);

    // Data from controller
    const injuryTypeData = @json($incidentsByInjuryType ?? []);
    const injuryTypeByFacilityData = @json($incidentsByInjuryTypeAndFacility ?? []);

    // Function to create/update injury chart
    function createInjuryChart(view) {
        let labels, values;
        const isHorizontal = view === 'type';

        if (view === 'type') {
            // Group by injury type
            labels = Object.keys(injuryTypeData);
            values = Object.values(injuryTypeData);
        } else {
            // Group by facility
            const facilityCounts = {};
            injuryTypeByFacilityData.forEach(item => {
                if (!facilityCounts[item.facility]) {
                    facilityCounts[item.facility] = 0;
                }
                facilityCounts[item.facility] += item.count;
            });
            labels = Object.keys(facilityCounts);
            values = Object.values(facilityCounts);
        }

        const totalCases = values.reduce((sum, val) => sum + val, 0);
        
        // Update total cases label
        const label = view === 'type' ? `${labels.length} Types` : `${labels.length} Facilities`;
        document.getElementById('totalInjuryCasesLabel').textContent = label;

        // Split long labels into 2 lines for better readability (for horizontal bars)
        const wrappedLabels = isHorizontal ? labels.map(label => {
            if (label.length > 15) {
                const words = label.split(' ');
                const mid = Math.ceil(words.length / 2);
                const line1 = words.slice(0, mid).join(' ');
                const line2 = words.slice(mid).join(' ');
                return line1 + '\n' + line2;
            }
            return label;
        }) : labels;

        const calculatedHeight = isHorizontal ? Math.max(300, Math.min(labels.length * 30, 380)) : 380;

        const options = {
            series: [{
                name: 'Cases',
                data: values
            }],
            chart: {
                type: 'bar',
                height: calculatedHeight,
                sparkline: {
                    enabled: false
                },
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 600
                }
            },
            plotOptions: {
                bar: {
                    horizontal: isHorizontal,
                    borderRadius: isHorizontal ? 4 : 6,
                    barHeight: isHorizontal ? '75%' : undefined,
                    columnWidth: isHorizontal ? undefined : '65%',
                    distributed: true,
                    dataLabels: {
                        position: isHorizontal ? 'right' : 'top'
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#10b981'],
            xaxis: isHorizontal ? {
                categories: wrappedLabels,
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500,
                        colors: '#64748b'
                    },
                    formatter: function(val) {
                        return Math.floor(val);
                    }
                },
                tickAmount: 5,
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            } : {
                categories: wrappedLabels,
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500,
                        colors: '#64748b'
                    },
                    rotate: labels.length > 8 ? -45 : 0,
                    rotateAlways: false
                },
                axisBorder: {
                    show: true,
                    color: '#e2e8f0'
                }
            },
            yaxis: isHorizontal ? {
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500,
                        colors: '#64748b'
                    },
                    maxHeight: 100
                }
            } : {
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500,
                        colors: '#64748b'
                    },
                    formatter: function(val) {
                        return Math.floor(val);
                    }
                },
                tickAmount: 5
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 2,
                yaxis: {
                    lines: {
                        show: isHorizontal
                    }
                },
                xaxis: {
                    lines: {
                        show: !isHorizontal
                    }
                },
                padding: {
                    left: 4,
                    right: 4,
                    top: 8,
                    bottom: 2
                }
            },
            fill: {
                type: 'solid',
                opacity: 1
            },
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                },
                active: {
                    filter: {
                        type: 'none'
                    }
                }
            },
            tooltip: {
                enabled: true,
                theme: 'light',
                style: {
                    fontSize: '12px'
                },
                y: {
                    formatter: function(val) {
                        return Math.floor(val) + ' case' + (Math.floor(val) !== 1 ? 's' : '');
                    }
                },
                marker: {
                    show: false
                }
            },
            legend: {
                show: false
            }
        };

        if (injuryChartInstance) {
            injuryChartInstance.destroy();
        }

        injuryChartInstance = new ApexCharts(document.querySelector("#injuryTypesChart"), options);
        injuryChartInstance.render();
    }

    // Initialize injury chart
    createInjuryChart('type');

    // Handle dropdown change for injury types
    const injuryToggle = document.getElementById('injuryTypeViewToggle');
    if (injuryToggle) {
        injuryToggle.addEventListener('change', function(e) {
            currentInjuryView = e.target.value;
            createInjuryChart(currentInjuryView);
        });
    }

    // Function to update dashboard with new data
    function updateDashboard(data) {
        if (!data.success) return;

        const stats = data.statistics;
        
        // Update KPI cards
        const cardSelectors = [
            { selector: '[data-stat="pending"]', value: stats.pending_review },
            { selector: '[data-stat="active"]', value: stats.active_incidents },
            { selector: '[data-stat="resolved"]', value: stats.resolved_incidents },
            { selector: '[data-stat="rejected"]', value: stats.rejected_incidents },
            { selector: '[data-stat="total"]', value: stats.total_cases },
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
        const lastUpdated = document.querySelector('#lastUpdatedHR');
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
            console.error('Error fetching HR dashboard data:', error);
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
