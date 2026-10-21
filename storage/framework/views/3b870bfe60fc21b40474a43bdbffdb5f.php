<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Pending Approval</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --brand: #1D4533;
            --brand-dark: #0f2818;
            --brand-light: #f0fdf4;
            --slate: #475569;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #0f172a;
        }

        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(29, 69, 51, 0.12), 0 2px 8px rgba(29, 69, 51, 0.08);
            max-width: 500px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
        }

        @media (max-width: 500px) {
            .container {
                padding: 40px 25px;
            }
        }

        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            background: var(--brand-light);
            color: var(--brand);
            box-shadow: 0 4px 16px rgba(29, 69, 51, 0.15);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 16px;
            color: #0f172a;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        p {
            font-size: 16px;
            color: var(--slate);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .btn {
            padding: 12px 32px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-primary {
            background: var(--brand);
            color: white;
            box-shadow: 0 4px 12px rgba(29, 69, 51, 0.2);
        }

        .btn-primary:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 69, 51, 0.3);
            text-decoration: none;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #d1d5db;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <i class="fa-solid fa-hourglass-end"></i>
        </div>
        
        <h1>Account Pending Approval</h1>
        
        <p>Your account is pending approval. Please contact IT for help.</p>
        
        <div>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-secondary">
                    <i class="fa-solid fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views/pending-user.blade.php ENDPATH**/ ?>