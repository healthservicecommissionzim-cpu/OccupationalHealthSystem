<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HSC-OHS | Submission Successful</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300..700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --brand: #1D4533;
            --white: #ffffff;
            --slate-900: #0f172a;
            --slate-500: #64748b;
            --green-500: #22c55e;
            --green-50: #f0fdf4;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--green-50) 0%, #f0f2f5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .container {
            background: var(--white);
            border-radius: 16px;
            padding: 3rem 2rem;
            box-shadow: 0 20px 60px -12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .icon {
            width: 80px;
            height: 80px;
            background: var(--green-50);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--green-500);
            font-size: 2.5rem;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 1rem;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--brand);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(29, 69, 51, 0.3);
        }

        .btn-primary:hover {
            background: #0f2818;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(29, 69, 51, 0.4);
        }

        @media (max-width: 640px) {
            .container {
                padding: 2rem 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .icon {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }

            .actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <i class="fa-solid fa-check"></i>
        </div>
        <h1>Submitted Successfully</h1>
        <div class="actions">
            <a href="<?php echo e(route('welcome')); ?>" class="btn btn-primary">
                <i class="fa-solid fa-home"></i> Back to Home
            </a>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\first_aid\success.blade.php ENDPATH**/ ?>