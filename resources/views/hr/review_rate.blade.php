<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Review Rate Report · HR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f4f9; padding: 2rem; min-height: 100vh; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: start; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .header p { font-size: 13px; color: #64748b; margin-top: 4px; }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; font-weight: 600; color: #64748b; text-decoration: none; transition: all 0.2s; }
        .back-btn:hover { background: #f8fafc; color: #1D4533; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-top { height: 4px; background: #1D4533; border-radius: 2px 2px 0 0; margin: -1.5rem -1.5rem 1rem -1.5rem; padding: 0; }
        .card h3 { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; }
        .card h3 i { color: #1D4533; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .field { display: flex; flex-direction: column; }
        .field-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-bottom: 4px; }
        .field-value { font-size: 12px; font-weight: 600; color: #0f172a; }
        .stat-box { padding: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; text-align: center; }
        .stat-number { font-size: 32px; font-weight: 700; color: #1D4533; }
        .stat-label { font-size: 12px; color: #64748b; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fa-solid fa-chart-line"></i> Review Rate Report</h1>
                <p>HR Incident Review Performance</p>
            </div>
            <a href="{{ route('dashboards.hr') }}" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-chart-pie"></i> REVIEW RATE METRICS</h3>
            <div class="grid-2">
                <div class="stat-box">
                    <div class="stat-number">{{ $statistics['review_rate'] }}%</div>
                    <div class="stat-label">Review Rate</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $statistics['reviewed_cases'] }}</div>
                    <div class="stat-label">Cases Reviewed</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $statistics['pending_review'] }}</div>
                    <div class="stat-label">Pending Review</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">{{ $statistics['total_cases'] }}</div>
                    <div class="stat-label">Total Cases</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
