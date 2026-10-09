<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Pending Approval</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            text-align: center;
        }

        .status-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }

        .status-icon.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-icon.rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #333;
        }

        .status-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-size: 14px;
            line-height: 1.5;
        }

        .alert.warning {
            background: #fef3c7;
            border-left: 4px solid #d97706;
            color: #92400e;
        }

        .alert.error {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            color: #7f1d1d;
        }

        .timeline {
            background: #f9fafb;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }

        .timeline-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-marker {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
        }

        .timeline-marker.complete {
            background: #d1fae5;
            color: #065f46;
        }

        .timeline-content h3 {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
        }

        .timeline-content p {
            font-size: 13px;
            color: #6b7280;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            flex: 1;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .contact-info {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if(session('error') || (auth()->check() && auth()->user()->isRejected())): ?>
            <div class="status-icon rejected">⛔</div>
            <h1>Application Rejected</h1>
            <div class="alert error"><?php echo e(session('error', 'Your application has been reviewed and rejected.')); ?></div>
            <p class="status-text">Your application has been reviewed and rejected.</p>
            <?php if(auth()->check()): ?>
                <?php
                    $daysRemaining = auth()->user()->getDaysUntilRetry();
                    $canReapply = auth()->user()->canRetryApplication();
                ?>
                <?php if($canReapply): ?>
                    <p class="status-text" style="color: #059669; font-weight: 500;">You can now reapply for an account.</p>
                <?php else: ?>
                    <?php if($daysRemaining !== null): ?>
                        <p class="status-text">You can reapply in <strong><?php echo e($daysRemaining); ?> day(s)</strong>.</p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <div class="status-icon pending">⏳</div>
            <h1>Account Pending Approval</h1>
            <div class="alert warning"><?php echo e(session('warning', 'Your account is pending approval.')); ?></div>
        <?php endif; ?>

        <p class="status-text">
            Thank you for registering! Your account is under review by our Super Admin team.
        </p>

        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-marker complete">✓</div>
                <div class="timeline-content">
                    <h3>Account Created</h3>
                    <p>Your account has been successfully registered.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-marker">2</div>
                <div class="timeline-content">
                    <h3>Under Review</h3>
                    <p>Our team is reviewing your application and details.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-marker">3</div>
                <div class="timeline-content">
                    <h3>Role Assignment</h3>
                    <p>You will be assigned an appropriate role and permissions.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-marker">4</div>
                <div class="timeline-content">
                    <h3>Approval Complete</h3>
                    <p>You can log in and access the system.</p>
                </div>
            </div>
        </div>

        <p class="status-text">
            <strong>Expected Timeline:</strong> Typically within 24-48 business hours.
        </p>

        <div class="button-group">
            <?php if(auth()->check() && auth()->user()->isRejected() && auth()->user()->canRetryApplication()): ?>
                <form method="POST" action="<?php echo e(route('retry-application')); ?>" style="flex: 1;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin: 0;">
                        Reapply Now
                    </button>
                </form>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="flex: 1;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-secondary" style="width: 100%; margin: 0;">
                    Logout
                </button>
            </form>
        </div>

        <div class="contact-info">
            If you have questions, please contact support at support@example.com
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\pending-user.blade.php ENDPATH**/ ?>