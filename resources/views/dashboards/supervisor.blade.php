@extends('layouts.dashboard')

@section('page_title', 'Supervisor Dashboard')

@section('header_title', 'Supervisor Dashboard')
@section('header_subtitle', 'Incident Oversight & Management')

@section('before_styles')
@endsection

@section('sidebar_content')
<!-- OVERVIEW SECTION -->
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-muted uppercase tracking-widest">Overview</div>
<a href="{{ route('dashboards.supervisor') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-ink text-sm"></i>
    <span>Dashboard</span>
</a>

@can('view_incidents')
<a href="{{ route('supervisor.all_incidents') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-triangle-exclamation w-5 text-center text-ink text-sm"></i>
    <span>All Incidents</span>
</a>
@endcan

<a href="{{ route('supervisor.return_to_work_tracking') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-person-hiking w-5 text-center text-ink text-sm"></i>
    <span>Return-to-Work Tracking</span>
</a>

<a href="{{ route('supervisor.compliance_metrics') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-clipboard-check w-5 text-center text-ink text-sm"></i>
    <span>Compliance Metrics</span>
</a>

<a href="{{ route('supervisor.monthly_summary') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-ink-soft hover:bg-brand-soft border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-calendar w-5 text-center text-ink text-sm"></i>
    <span>Monthly Summary</span>
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
        <h2 class="text-sm font-bold text-ink flex items-center gap-2">
            <i class="fa-solid fa-chart-line text-primary"></i>
            Performance Metrics
        </h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 bg-surface border border-line rounded-lg text-xs md:text-sm font-medium text-ink-soft hover:bg-canvas transition-colors shadow-sm w-full sm:w-auto">
                    <i class="fa-regular fa-calendar text-muted"></i>
                    <span>
                        @if($dateRange === '7days')
                            Last 7 days
                        @elseif($dateRange === '90days')
                            Last 90 days
                        @elseif($dateRange === 'all')
                            All time
                        @else
                            Last 30 days
                        @endif
                    </span>
                    <i class="fa-solid fa-chevron-down text-muted text-xs md:text-sm"></i>
                </button>
                <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-40 md:w-48 bg-surface border border-line rounded-lg shadow-lg z-10">
                    <a href="{{ route('dashboards.supervisor', ['date_range' => '7days']) }}" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-ink-soft hover:bg-canvas border-b border-line {{ $dateRange === '7days' ? 'bg-canvas' : '' }}">
                        <i class="fa-solid fa-check text-brand {{ $dateRange === '7days' ? '' : 'opacity-0' }}"></i>
                        <span>Last 7 days</span>
                    </a>
                    <a href="{{ route('dashboards.supervisor', ['date_range' => '30days']) }}" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-ink-soft hover:bg-canvas border-b border-line {{ $dateRange === '30days' ? 'bg-canvas' : '' }}">
                        <i class="fa-solid fa-check text-brand {{ $dateRange === '30days' ? '' : 'opacity-0' }}"></i>
                        <span>Last 30 days</span>
                    </a>
                    <a href="{{ route('dashboards.supervisor', ['date_range' => '90days']) }}" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-ink-soft hover:bg-canvas border-b border-line {{ $dateRange === '90days' ? 'bg-canvas' : '' }}">
                        <i class="fa-solid fa-check text-brand {{ $dateRange === '90days' ? '' : 'opacity-0' }}"></i>
                        <span>Last 90 days</span>
                    </a>
                    <a href="{{ route('dashboards.supervisor', ['date_range' => 'all']) }}" class="flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm text-ink-soft hover:bg-canvas {{ $dateRange === 'all' ? 'bg-canvas' : '' }}">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
        <!-- Total Incidents Card -->
        <div class="bg-surface rounded-lg border border-line shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="total">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-muted uppercase tracking-wide">Total Incidents</span>
                    <p class="text-xl font-bold text-ink mt-0.5">{{ number_format($statistics['total_incidents']) }}</p>
                    <p class="text-xs text-muted mt-0.5">All assigned cases</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg flex items-center justify-center">
                    <x-lucide-inbox class="w-4 h-4 text-amber-600" stroke-width="1.5" />
                </div>
            </div>
        </div>

        <!-- Pending Verification Card -->
        <div class="bg-surface rounded-lg border border-line shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="pending">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-muted uppercase tracking-wide">Pending Review</span>
                    <p class="text-xl font-bold text-ink mt-0.5">{{ number_format($statistics['pending_verification']) }}</p>
                    <p class="text-xs text-muted mt-0.5">Awaiting action</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                    <x-lucide-alert-circle class="w-4 h-4 text-blue-600" stroke-width="1.5" />
                </div>
            </div>
        </div>

        <!-- Verified & Escalated Card -->
        <div class="bg-surface rounded-lg border border-line shadow-sm hover:shadow-md transition-all overflow-hidden" data-stat="verified">
            <div class="h-0.5 bg-brand"></div>
            <div class="p-2.5 flex items-start justify-between gap-2">
                <div class="flex-1">
                    <span class="text-xs font-semibold text-muted uppercase tracking-wide">Verified & Escalated</span>
                    <p class="text-xl font-bold text-ink mt-0.5">{{ number_format($statistics['verified_escalated']) }}</p>
                    <p class="text-xs text-muted mt-0.5">Processed cases</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg flex items-center justify-center">
                    <x-lucide-check-circle-2 class="w-4 h-4 text-emerald-600" stroke-width="1.5" />
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
    <div class="lg:col-span-2 bg-surface rounded-lg border border-line shadow-sm p-4 md:p-6">
        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-ink mb-4 md:mb-6">Incidents by Type</h3>
        <div id="incidentTypeChart" style="height: 220px;"></div>
    </div>
    <div class="bg-surface rounded-lg border border-line shadow-sm p-4 md:p-6">
        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-ink mb-4 md:mb-6">First Aid Recording Rate</h3>
        <div id="firstAidChart" style="height: 220px;"></div>
    </div>
</div>

<!-- Assigned Cases - Pending Review Card -->
@if($recentIncidents->count() > 0)
@php
    $totalCount = $recentIncidents->count();
@endphp
<style>
    /* Match all incidents page styling */
    .dashboard-incidents-table { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; }
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
    .dashboard-incidents-table .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; text-decoration: none; border-radius: 6px; font-size: 10px; font-weight: 650; transition: all 0.2s; }
    .dashboard-incidents-table .action-btn:hover { background: #067c58; transform: translateY(-1px); }
    .dashboard-incidents-table .table-footer { padding: 16px 22px; background: white; border-top: 1px solid #e4e9ee; text-align: center; }
    .dashboard-incidents-table .footer-text { font-size: 11px; color: #8a95a5; font-weight: 600; }
    .dashboard-incidents-table .count-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; background: #e9f8f3; color: #067c58; font-weight: 700; margin: 0 4px; }
</style>

<div class="dashboard-incidents-table mb-6" x-data="{ expanded: false }">
    <!-- Header Section -->
    <div class="table-header">
        <div class="table-title">
            <i class="fa-solid fa-table-list"></i>
            &nbsp; Assigned Cases - Pending Review
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
                @foreach($recentIncidents as $incident)
                    @php
                        $displayCount = $loop->iteration;
                        $status = $incident->status ?? 'PENDING_SUPERVISOR_VERIFICATION';
                        $statusLabel = '';
                        $statusClass = '';
                        
                        switch($status) {
                            case 'VERIFIED_SUPERVISOR':
                                $statusLabel = 'Verified';
                                $statusClass = 'verified';
                                break;
                            case 'ESCALATED_TO_HR':
                                $statusLabel = 'Escalated';
                                $statusClass = 'escalated';
                                break;
                            default:
                                $statusLabel = 'Pending';
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
                            <a href="{{ route('incidents.supervisor_view', ['incident' => $incident]) }}" class="action-btn">
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
            Showing <span x-text="expanded ? '{{ $totalCount }}' : Math.min(5, {{ $totalCount }})"></span> of <span class="count-badge">{{ $totalCount }}</span> pending cases
        </p>
    </div>
    @endif
</div>
@endif

@endsection

@section('scripts')
<script>
    // Chart rendering code
    document.addEventListener('DOMContentLoaded', function() {
        // Prepare data for incident types chart
        const incidentTypeArray = @json($incidentsByType);
        console.log('Raw Incident Type Array:', incidentTypeArray);
        
        const typeLabels = incidentTypeArray.map(item => item.name || 'Unknown');
        const typeData = incidentTypeArray.map(item => parseInt(item.count, 10));
        
        console.log('Type Labels:', typeLabels);
        console.log('Type Data:', typeData);

        // Incident Types Bar Chart (Horizontal)
        if (document.getElementById('incidentTypeChart') && typeLabels.length > 0) {
            // Calculate dynamic height based on number of bars
            const chartHeight = Math.max(200, typeLabels.length * 20 + 30);
            
            // For horizontal bar charts, we need to use x,y format
            const formattedData = typeLabels.map((label, index) => ({
                x: label,
                y: typeData[index]
            }));
            
            const incidentTypeOptions = {
                series: [{
                    name: 'Incidents',
                    data: formattedData
                }],
                chart: {
                    type: 'bar',
                    height: chartHeight,
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
                        horizontal: true,
                        borderRadius: 6,
                        barHeight: '70%',
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
                    type: 'numeric',
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: 500
                        },
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: 500
                        }
                    }
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
                        type: 'horizontal',
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
                            return val + ' incidents';
                        }
                    }
                }
            };

            const incidentTypeChart = new ApexCharts(document.querySelector("#incidentTypeChart"), incidentTypeOptions);
            incidentTypeChart.render();
        }

        // Prepare data for first aid recording rate
        const firstAidRecordedPercentage = {{ $firstAidStats['recorded_percentage'] }};
        const firstAidNotRecordedPercentage = 100 - firstAidRecordedPercentage;

        // First Aid Recording Rate Gauge Chart
        if (document.getElementById('firstAidChart')) {
            const firstAidOptions = {
                series: [firstAidRecordedPercentage],
                chart: {
                    type: 'radialBar',
                    height: 220,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '60%'
                        },
                        dataLabels: {
                            name: {
                                show: true,
                                fontSize: '13px',
                                fontWeight: 600,
                                color: '#64748b',
                                offsetY: -10
                            },
                            value: {
                                show: true,
                                fontSize: '20px',
                                fontWeight: 700,
                                color: '#0f172a',
                                offsetY: 5,
                                formatter: function(val) {
                                    return Math.round(val) + '%';
                                }
                            },
                            total: {
                                show: true,
                                label: 'Recording Rate',
                                fontSize: '12px',
                                fontWeight: 600,
                                color: '#64748b'
                            }
                        },
                        track: {
                            background: '#e2e8f0',
                            strokeWidth: '8px'
                        },
                        startAngle: -90,
                        endAngle: 90
                    }
                },
                colors: ['#0d7a3d'],
                labels: ['First Aid Recorded'],
                stroke: {
                    lineCap: 'round'
                }
            };

            const firstAidChart = new ApexCharts(document.querySelector("#firstAidChart"), firstAidOptions);
            firstAidChart.render();
        }
    });
</script>
@endsection



<!-- Real-Time Dashboard Updates -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateRange = '{{ $dateRange }}';
    const apiUrl = "{{ route('api.supervisor_dashboard_data') }}?date_range=" + dateRange;
    const pollInterval = 30000; // 30 seconds
    let pollIntervalId = null;

    // Function to update dashboard with new data
    function updateDashboard(data) {
        if (!data.success) return;

        // Update statistics cards
        const stats = data.statistics;
        const cardSelectors = [
            { selector: '[data-stat="total"]', value: stats.total_incidents },
            { selector: '[data-stat="pending"]', value: stats.pending_verification },
            { selector: '[data-stat="verified"]', value: stats.verified_escalated },
            { selector: '[data-stat="rejected"]', value: stats.rejected },
            { selector: '[data-stat="in_progress"]', value: stats.in_progress },
            { selector: '[data-stat="resolved"]', value: stats.resolved },
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

        // Update first aid stats
        const firstAidPercent = data.first_aid_stats.recorded_percentage;
        const firstAidElement = document.querySelector('[data-stat="first-aid"]');
        if (firstAidElement) {
            const percentElement = firstAidElement.querySelector('p:first-of-type');
            if (percentElement) {
                percentElement.textContent = firstAidPercent + '%';
            }
        }

        // Update compliance stats
        const complianceElement = document.querySelector('[data-stat="compliant"]');
        if (complianceElement) {
            const percentElement = complianceElement.querySelector('p:first-of-type');
            if (percentElement) {
                const compliancePercent = data.compliance_stats.compliant > 0 
                    ? Math.round((data.compliance_stats.compliant / (data.compliance_stats.compliant + data.compliance_stats.partial_compliant + data.compliance_stats.noncompliant)) * 100)
                    : 0;
                percentElement.textContent = compliancePercent + '%';
            }
        }

        // Update notifications badge
        const notificationsBadge = document.querySelector('[data-notifications-count]');
        if (notificationsBadge) {
            notificationsBadge.textContent = data.unread_notifications;
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
            console.error('Error fetching dashboard data:', error);
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
            fetchDashboardData(); // Fetch fresh data immediately
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', stopPolling);
});
</script>
