<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Incident Trends · HSC Conditions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 1600px; margin: auto; padding: 35px 25px 70px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; background: #f8fafb; }
        .card-title { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 13px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .card-body { padding: 22px; }
        
        /* Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal.active { display: block; }
        .modal-content { background-color: #fefefe; margin: 3% auto; padding: 0; border-radius: 14px; width: 90%; max-width: 1200px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .modal-header { padding: 20px 25px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; background: #f8fafb; border-radius: 14px 14px 0 0; }
        .modal-title { font-size: 16px; font-weight: 750; color: #101a31; }
        .close { color: #8a95a5; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1; }
        .close:hover { color: #dc2626; }
        .modal-body { padding: 25px; max-height: 70vh; overflow-y: auto; }
        
        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; position: sticky; top: 0; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .ref-number { font-weight: 700; color: #079b6d; font-size: 12px; }
        .clickable-chart { cursor: pointer; }
        .chart-hint { font-size: 11px; color: #718096; text-align: center; margin-top: 10px; }
        .chart-hint i { color: #079b6d; }
        
        /* Responsive */
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            .modal-content { width: 95%; margin: 5% auto; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-chart-line"></i></div>
                <div>
                    <h1 class="title">Incident Trends Analysis</h1>
                    <div class="sub">Analyze patterns and trends in occupational health incidents</div>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="back">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Incidents by Type Chart -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-chart-bar"></i>
                    Incidents by Type (Last 90 Days)
                </div>
            </div>
            <div class="card-body">
                <div id="typeChart" class="clickable-chart"></div>
                <div class="chart-hint">
                    <i class="fa-solid fa-hand-pointer"></i> Click on any bar to view detailed incident records
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Incident Details -->
    <div id="incidentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Incident Details</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <table id="incidentTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>REFERENCE</th>
                            <th>DATE</th>
                            <th>EMPLOYEE</th>
                            <th>FACILITY</th>
                            <th>TYPE</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody id="incidentTableBody">
                        <!-- Content loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Prepare data from Laravel
        const trendsByType = @json($trendsByType);
        const incidentsByType = @json($incidentsByType);
        
        // Chart colors - gradient greens
        const colors = ['#0d7a3d', '#0f8c47', '#11a052', '#13b45d', '#1bb74d', '#23cc5a', '#2be067', '#33f574', '#3bff7f', '#43ff8a', '#4bff95', '#53ffa0', '#5bffab', '#63ffb6', '#6bffc1', '#73ffcc', '#7bffd7', '#83ffe2', '#8bffed', '#53ff2f'];
        
        // Prepare data for horizontal bar chart
        const chartLabels = trendsByType.map(item => item.incident_type?.name || 'Unknown');
        const chartValues = trendsByType.map(item => item.count);
        const chartData = chartLabels.map((label, index) => ({
            x: label,
            y: chartValues[index]
        }));
        
        // Create Horizontal Bar Chart
        const typeChartOptions = {
            series: [{
                name: 'Incidents',
                data: chartData
            }],
            chart: {
                type: 'bar',
                height: Math.max(400, trendsByType.length * 50),
                toolbar: { show: true },
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        const dataPoint = trendsByType[config.dataPointIndex];
                        showIncidentDetails(dataPoint.incident_type?.name || 'Unknown', dataPoint.incident_type_id);
                    }
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    distributed: true,
                    horizontal: true,
                    barHeight: '70%'
                }
            },
            colors: colors,
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                style: {
                    fontSize: '11px',
                    fontWeight: 'bold',
                    colors: ['#fff']
                },
                formatter: function (val) {
                    return val;
                },
                offsetX: 10
            },
            grid: {
                borderColor: '#e4e9ee',
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                },
                padding: {
                    top: 0,
                    right: 20,
                    bottom: 0,
                    left: 10
                }
            },
            xaxis: {
                type: 'numeric',
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 600,
                        colors: '#718096'
                    },
                    formatter: function(val) {
                        return Math.floor(val);
                    }
                },
                axisBorder: {
                    show: true,
                    color: '#e4e9ee'
                },
                axisTicks: {
                    show: true,
                    color: '#e4e9ee'
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 600,
                        colors: '#4e596c'
                    },
                    maxWidth: 200
                }
            },
            title: {
                text: 'Click any bar to view detailed records',
                align: 'center',
                style: {
                    fontSize: '12px',
                    color: '#718096',
                    fontWeight: 600
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return val + " incidents"
                    }
                },
                style: {
                    fontSize: '12px'
                }
            },
            legend: {
                show: false
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.2,
                    gradientToColors: undefined,
                    inverseColors: false,
                    opacityFrom: 0.95,
                    opacityTo: 0.85,
                    stops: [0, 100]
                }
            }
        };

        const typeChart = new ApexCharts(document.querySelector("#typeChart"), typeChartOptions);
        typeChart.render();

        // Modal Functions
        const modal = document.getElementById('incidentModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalTableBody = document.getElementById('incidentTableBody');
        const closeBtn = document.getElementsByClassName('close')[0];

        closeBtn.onclick = function() {
            modal.classList.remove('active');
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.classList.remove('active');
            }
        }

        function showIncidentDetails(typeName, typeId) {
            modalTitle.textContent = `${typeName} - Detailed Records`;
            
            // Filter incidents by type
            const incidents = incidentsByType[typeId] || [];
            
            if (incidents.length === 0) {
                modalTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #8a95a5;">No incidents found</td></tr>';
            } else {
                modalTableBody.innerHTML = incidents.map((incident, index) => `
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: #8a95a5;">${index + 1}</td>
                        <td><span class="ref-number">${incident.reference_number}</span></td>
                        <td>${new Date(incident.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
                        <td>${incident.first_name} ${incident.surname}</td>
                        <td>${incident.facility?.name || 'N/A'}</td>
                        <td>${incident.incident_type?.name || 'Unknown'}</td>
                        <td>${formatStatus(incident.status)}</td>
                    </tr>
                `).join('');
            }
            
            modal.classList.add('active');
        }

        function formatStatus(status) {
            const statusMap = {
                'PENDING_SUPERVISOR_VERIFICATION': 'Pending Verification',
                'VERIFIED_SUPERVISOR': 'Supervisor Verified',
                'ESCALATED_TO_HR': 'Escalated to HR',
                'RESOLVED': 'Resolved',
                'CLOSED': 'Closed'
            };
            return statusMap[status] || status?.replace(/_/g, ' ') || 'Pending';
        }
    </script>
</body>
</html>
