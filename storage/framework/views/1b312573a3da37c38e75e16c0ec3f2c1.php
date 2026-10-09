<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Compliance Report · HR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f4f9; padding: 2rem; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { margin-bottom: 2rem; display: flex; justify-content: space-between; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .back-btn { padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; text-decoration: none; color: #64748b; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-top { height: 4px; background: #1D4533; margin: -1.5rem -1.5rem 1rem -1.5rem; }
        .card h3 { font-size: 13px; font-weight: 700; margin-bottom: 1rem; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .stat-box { padding: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; text-align: center; }
        .stat-number { font-size: 32px; font-weight: 700; color: #1D4533; }
        .stat-label { font-size: 12px; color: #64748b; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div><h1><i class="fa-solid fa-file-check"></i> Compliance Report</h1></div>
            <a href="<?php echo e(route('dashboards.hr')); ?>" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>
        <div class="card">
            <div class="card-top"></div>
            <h3>Compliance Summary</h3>
            <div class="grid-2">
                <div class="stat-box">
                    <div class="stat-number"><?php echo e($statistics['compliant']); ?></div>
                    <div class="stat-label">Compliant</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?php echo e($statistics['partial_compliant']); ?></div>
                    <div class="stat-label">Partial</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?php echo e($statistics['noncompliant']); ?></div>
                    <div class="stat-label">Non-Compliant</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number"><?php echo e($statistics['total_cases']); ?></div>
                    <div class="stat-label">Total Cases</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\hr\compliance_report.blade.php ENDPATH**/ ?>