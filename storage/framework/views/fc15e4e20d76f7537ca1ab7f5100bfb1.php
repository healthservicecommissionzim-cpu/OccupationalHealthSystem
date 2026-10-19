<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title><?php echo e($user->name); ?> · User Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        /* ─── Variables – same as before ─── */
        :root {
            --ink: #14231c;
            --ink-soft: #52605a;
            --muted: #7f8b85;
            --line: #e6ebe8;
            --surface: #ffffff;
            --canvas: #f5f7f6;
            --brand: #1d4533;
            --brand-deep: #103022;
            --brand-soft: #eaf4ee;
            --success: #218653;
            --success-soft: #e8f7ef;
            --warning: #a86d0a;
            --warning-soft: #fff6df;
            --danger: #b33a42;
            --danger-soft: #fff0f1;
            --blue: #356bb3;
            --blue-soft: #edf4ff;
            --shadow: 0 18px 48px rgba(25, 46, 35, .07), 0 2px 8px rgba(25, 46, 35, .03);
            --radius-lg: 20px;
            --radius-md: 14px;
            --radius-sm: 10px;
            --ease: 180ms ease;
        }

        * { box-sizing: border-box; }
        html { background: var(--canvas); }
        body {
            margin: 0;
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 0%, rgba(212, 235, 220, .55), transparent 30rem),
                var(--canvas);
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.5;
        }
        a { color: inherit; }
        button, input, select, textarea { font: inherit; }

        .shell {
            width: min(1180px, calc(100% - 40px));
            margin: 0 auto;
            padding: 16px 0 32px;
        }

        /* ─── Re‑use all existing styles ─── */
        /* (The full CSS from the original is kept; I’m only showing the structural changes in the HTML. For brevity, I’ll include the full CSS again, but you can keep your current styles.) */
        .topbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
        .breadcrumb { display: flex; align-items: center; gap: 10px; color: var(--muted); font-size: .83rem; font-weight: 600; }
        .breadcrumb a { text-decoration: none; transition: color var(--ease); }
        .breadcrumb a:hover { color: var(--brand); }
        .breadcrumb i { font-size: .65rem; color: #b6c0ba; }
        .back-link { display: inline-flex; align-items: center; gap: 9px; padding: 10px 15px; border: 1px solid var(--line); border-radius: var(--radius-sm); background: rgba(255,255,255,.72); color: var(--ink-soft); text-decoration: none; font-size: .84rem; font-weight: 700; transition: all var(--ease); }
        .back-link:hover { border-color: #c9d8ce; color: var(--brand); background: var(--surface); transform: translateY(-1px); }

        .alert { display: flex; align-items: flex-start; gap: 12px; margin: 0 0 20px; padding: 14px 16px; border: 1px solid; border-radius: var(--radius-md); font-size: .88rem; font-weight: 600; }
        .alert i { margin-top: 2px; }
        .alert.success { border-color: #bde7cd; background: var(--success-soft); color: #17663f; }
        .alert.error { border-color: #f3c5c9; background: var(--danger-soft); color: #96343d; }

        .profile-hero { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 18px; margin-bottom: 16px; border: 1px solid #dce7df; border-radius: var(--radius-lg); background: linear-gradient(120deg, #ffffff 0%, #f8fcf9 100%); box-shadow: var(--shadow); }
        .identity { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .avatar { display: grid; place-items: center; flex: 0 0 48px; width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(145deg, var(--brand), #397557); color: white; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 800; box-shadow: 0 10px 20px rgba(29,69,51,.18); }
        .eyebrow { margin: 0 0 2px; color: var(--brand); font-size: .65rem; font-weight: 800; letter-spacing: .13em; text-transform: uppercase; }
        h1, h2, h3 { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1 { margin: 0; font-size: clamp(1.1rem, 2vw, 1.4rem); line-height: 1.15; letter-spacing: -.035em; }
        .hero-meta { margin: 3px 0 0; color: var(--ink-soft); font-size: .8rem; }
        .hero-status { text-align: right; }
        .hero-status-label { margin: 0 0 8px; color: var(--muted); font-size: .74rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; }

        .status { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 999px; font-size: .76rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
        .status::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
        .status.pending { color: var(--warning); background: var(--warning-soft); }
        .status.approved { color: var(--success); background: var(--success-soft); }
        .status.rejected { color: var(--danger); background: var(--danger-soft); }

        .layout { display: grid; grid-template-columns: minmax(0, 1.5fr) minmax(320px, 1fr); align-items: stretch; gap: 16px; }
        .main-column { display: flex; flex-direction: column; gap: 16px; }
        .side-column { display: flex; flex-direction: column; gap: 16px; }
        .card { overflow: hidden; border: 1px solid var(--line); border-radius: var(--radius-lg); background: var(--surface); box-shadow: var(--shadow); display: flex; flex-direction: column; }
        .card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 14px 16px 12px; border-bottom: 1px solid var(--line); }
        .card-heading { display: flex; align-items: flex-start; gap: 10px; }
        .section-icon { display: grid; place-items: center; flex: 0 0 28px; width: 28px; height: 28px; border-radius: 8px; background: var(--brand-soft); color: var(--brand); font-size: .75rem; }
        .card-header h2 { margin: 0; font-size: .9rem; letter-spacing: -.025em; }
        .card-header p { margin: 2px 0 0; color: var(--muted); font-size: .7rem; }
        .count { color: var(--muted); font-size: .78rem; font-weight: 700; white-space: nowrap; }
        .card-body { padding: 14px 16px; display: flex; flex-direction: column; }

        .details-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px 18px; flex: 1; }
        .detail { min-width: 0; }
        .detail-label { margin-bottom: 3px; color: var(--muted); font-size: .65rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .detail-value { overflow-wrap: anywhere; color: var(--ink); font-size: .8rem; font-weight: 600; }
        .detail-value.empty { color: #aab4ae; font-weight: 500; font-style: italic; }
        .detail-value i { margin-right: 7px; color: var(--brand); font-size: .82rem; }
        .divider { height: 1px; margin: 12px 0; background: var(--line); }

        .role-list, .history-list { display: grid; flex: 1; }
        .role-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--line); }
        .role-row:first-child { padding-top: 0; }
        .role-row:last-child { padding-bottom: 0; border-bottom: 0; }
        .role-name { display: flex; align-items: center; gap: 8px; min-width: 0; font-size: .8rem; font-weight: 600; }
        .role-name i { color: var(--brand); font-size: .7rem; }
        .role-code { padding: 4px 8px; border-radius: 6px; background: var(--blue-soft); color: var(--blue); font-size: .65rem; font-weight: 700; white-space: nowrap; }

        .timeline-item { position: relative; display: grid; grid-template-columns: 10px 1fr; gap: 10px; padding-bottom: 14px; }
        .timeline-item:last-child { padding-bottom: 0; }
        .timeline-item:not(:last-child)::before { content: ''; position: absolute; left: 4px; top: 10px; bottom: 0; width: 2px; background: var(--line); }
        .timeline-dot { position: relative; z-index: 1; width: 10px; height: 10px; margin-top: 3px; border: 2px solid var(--brand-soft); border-radius: 50%; background: var(--brand); }
        .timeline-title { margin: 0; font-size: .8rem; font-weight: 700; }
        .timeline-meta { margin: 2px 0 0; color: var(--muted); font-size: .7rem; }
        .rejection-item { margin-top: 6px; padding: 8px 10px; border-left: 3px solid var(--danger); border-radius: 0 var(--radius-sm) var(--radius-sm) 0; background: var(--danger-soft); color: #8e3038; font-size: .75rem; }

        .action-card { border-color: #d6e5da; }
        .action-card .card-header { background: #f6fbf7; }
        .form-group { margin-bottom: 12px; }
        label { display: block; margin-bottom: 5px; color: var(--ink-soft); font-size: .7rem; font-weight: 700; }
        select, textarea { width: 100%; border: 1px solid #d8e1dc; border-radius: var(--radius-sm); outline: none; background: #fbfdfb; color: var(--ink); transition: all var(--ease); font-size: .85rem; }
        select { appearance: none; padding: 8px 30px 8px 10px; background-image: linear-gradient(45deg, transparent 50%, var(--muted) 50%), linear-gradient(135deg, var(--muted) 50%, transparent 50%); background-position: calc(100% - 14px) 14px, calc(100% - 10px) 14px; background-size: 4px 4px, 4px 4px; background-repeat: no-repeat; }
        textarea { min-height: 80px; padding: 8px 10px; resize: vertical; }
        select:focus, textarea:focus { border-color: #6fa687; background: white; box-shadow: 0 0 0 3px rgba(111,166,135,.13); }
        .form-hint { margin: -6px 0 12px; color: var(--muted); font-size: .7rem; }
        .form-divider { height: 1px; margin: 12px 0; background: var(--line); }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; width: 100%; padding: 9px 14px; border: 0; border-radius: var(--radius-sm); cursor: pointer; font-size: .78rem; font-weight: 700; transition: all var(--ease); }
        .btn-primary { background: var(--brand); color: white; box-shadow: 0 7px 15px rgba(29,69,51,.16); }
        .btn-primary:hover { background: var(--brand-deep); transform: translateY(-1px); box-shadow: 0 10px 20px rgba(29,69,51,.22); }
        .btn-danger { background: var(--danger-soft); color: var(--danger); border: 1px solid #f3c5c9; }
        .btn-danger:hover { background: #ffe3e5; border-color: #e9a8ae; transform: translateY(-1px); }

        .empty-state { padding: 12px 0 2px; color: var(--muted); text-align: center; font-size: .75rem; }
        .empty-state i { display: block; margin-bottom: 6px; color: #c3cec7; font-size: 1.2rem; }
        .status-details .detail + .detail { margin-top: 12px; }
        .status-details { display: flex; flex-direction: column; height: 100%; }
        .status-details .approved-note { flex-shrink: 0; }
        .status-details .divider { flex-shrink: 0; }
        .status-details .details-grid { flex: 1; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px 18px; }
        .reason { padding: 8px 10px; border-left: 3px solid var(--danger); border-radius: 0 var(--radius-sm) var(--radius-sm) 0; background: var(--danger-soft); color: #8e3038; font-size: .8rem; font-weight: 600; }
        .approved-note { padding: 8px 10px; border-radius: var(--radius-sm); background: var(--success-soft); color: #17663f; font-size: .75rem; font-weight: 600; }

        /* Make sidebar cards fill available height */
        .side-column .card { flex: 1 1 auto; min-height: 0; }
        .main-column .card:first-child { min-height: 240px; }

        @media (max-width: 900px) { .layout { grid-template-columns: 1fr; } .side-column { grid-template-columns: repeat(2, minmax(0, 1fr)); align-items: start; } }
        @media (max-width: 620px) { .shell { width: min(100% - 20px, 560px); padding-top: 12px; } .topbar, .profile-hero { align-items: flex-start; flex-direction: column; } .topbar { gap: 10px; } .back-link { align-self: stretch; justify-content: center; font-size: .75rem; } .profile-hero { padding: 12px 14px; } .hero-status { width: 100%; text-align: left; } .details-grid, .side-column { grid-template-columns: 1fr; } .card-header, .card-body { padding: 12px; } .role-row { align-items: flex-start; flex-direction: column; gap: 6px; } }
    </style>
</head>
<body>
    <div class="shell">

        <!-- ─── Header: breadcrumb & back button ─── -->
        <header class="topbar" aria-label="Page navigation">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo e(route('user-approval.index')); ?>">Users</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span>Profile</span>
            </nav>
            <a href="<?php echo e(route('user-approval.index')); ?>" class="back-link">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                Back to users
            </a>
        </header>

        <!-- ─── Flash messages ─── -->
        <?php if(session('success')): ?>
            <div class="alert success" role="status">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                <div><?php echo e(session('success')); ?></div>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert error" role="alert">
                <i class="fa-solid fa-circle-xmark" aria-hidden="true"></i>
                <div><?php echo e(session('error')); ?></div>
            </div>
        <?php endif; ?>

        <!-- ─── User identity banner ─── -->
        <header class="profile-hero">
            <div class="identity">
                <div class="avatar" aria-hidden="true"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></div>
                <div>
                    <p class="eyebrow">Super admin · User profile</p>
                    <h1><?php echo e($user->name); ?></h1>
                    <p class="hero-meta"><?php echo e($user->email); ?> <span aria-hidden="true">·</span> Registered <?php echo e($user->created_at->format('M d, Y')); ?></p>
                </div>
            </div>
            <div class="hero-status">
                <p class="hero-status-label">Account status</p>
                <?php if($user->status === 'pending'): ?>
                    <span class="status pending">Pending review</span>
                <?php elseif($user->status === 'approved'): ?>
                    <span class="status approved">Approved</span>
                    <?php if($user): ?>
                        <div style="margin-top: 12px; display: flex; gap: 8px; flex-wrap: wrap;">
                            <form method="POST" action="<?php echo e(route('user-approval.reset-password', $user)); ?>" id="passwordResetForm" style="margin: 0;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-primary" style="width: auto; font-size: .75rem; padding: 6px 12px;"><i class="fa-solid fa-key" aria-hidden="true"></i>Reset password</button>
                            </form>
                            
                            <button type="button" class="btn btn-success" onclick="updateUserStatus('<?php echo e($user->id); ?>', 'active')" style="width: auto; font-size: .75rem; padding: 6px 12px; background-color: #10b981; border: none; cursor: pointer;">
                                <i class="fa-solid fa-check-circle"></i> Activate
                            </button>
                            
                            <button type="button" class="btn btn-warning" onclick="updateUserStatus('<?php echo e($user->id); ?>', 'inactive')" style="width: auto; font-size: .75rem; padding: 6px 12px; background-color: #f59e0b; border: none; cursor: pointer; color: white;">
                                <i class="fa-solid fa-ban"></i> Inactivate
                            </button>
                            
                            <button type="button" class="btn btn-danger" onclick="updateUserStatus('<?php echo e($user->id); ?>', 'suspended')" style="width: auto; font-size: .75rem; padding: 6px 12px; background-color: #ef4444; border: none; cursor: pointer; color: white;">
                                <i class="fa-solid fa-lock"></i> Suspend
                            </button>
                        </div>
                    <?php endif; ?>
                <?php elseif($user->status === 'rejected'): ?>
                    <span class="status rejected">Rejected</span>
                <?php endif; ?>
            </div>
        </header>

        <!-- ─── Main content ─── -->
        <div class="layout">
            <main class="main-column">

                <!-- Personal information -->
                <section class="card" aria-labelledby="personal-heading">
                    <div class="card-header">
                        <div class="card-heading">
                            <span class="section-icon"><i class="fa-solid fa-user" aria-hidden="true"></i></span>
                            <div>
                                <h2 id="personal-heading">Personal information</h2>
                                <p>Basic contact details for this account.</p>
                            </div>
                        </div>
                        <button id="editPersonalBtn" type="button" class="btn" style="width: auto; padding: 8px 14px; font-size: .75rem; background: var(--brand-soft); color: var(--brand); border: 1px solid #c9d8ce; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all var(--ease);"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>Edit</button>
                    </div>
                    <div class="card-body" id="viewPersonalInfo">
                        <dl class="details-grid">
                            <div class="detail">
                                <dt class="detail-label">Full name</dt>
                                <dd class="detail-value"><?php echo e($user->name); ?></dd>
                            </div>
                            <div class="detail">
                                <dt class="detail-label">Email address</dt>
                                <dd class="detail-value"><?php echo e($user->email); ?></dd>
                            </div>
                            <div class="detail">
                                <dt class="detail-label">Phone number</dt>
                                <dd class="detail-value <?php echo e($user->phone_number ? '' : 'empty'); ?>"><?php echo e($user->phone_number ?? 'Not provided'); ?></dd>
                            </div>
                            <div class="detail">
                                <dt class="detail-label">Date registered</dt>
                                <dd class="detail-value"><?php echo e($user->created_at->format('M d, Y · h:i A')); ?></dd>
                            </div>
                        </dl>
                    </div>
                    <div class="card-body" id="editPersonalInfo" style="display: none;">
                        <form id="editPersonalForm">
                            <div class="form-group">
                                <label for="edit_name">Full name</label>
                                <input type="text" id="edit_name" name="name" value="<?php echo e($user->name); ?>" required style="width: 100%; border: 1px solid #d8e1dc; border-radius: var(--radius-sm); outline: none; background: #fbfdfb; color: var(--ink); transition: all var(--ease); font-size: .85rem; padding: 8px 10px;">
                            </div>
                            <div class="form-group">
                                <label for="edit_email">Email address</label>
                                <input type="email" id="edit_email" name="email" value="<?php echo e($user->email); ?>" required style="width: 100%; border: 1px solid #d8e1dc; border-radius: var(--radius-sm); outline: none; background: #fbfdfb; color: var(--ink); transition: all var(--ease); font-size: .85rem; padding: 8px 10px;">
                            </div>
                            <div class="form-group">
                                <label for="edit_phone">Phone number</label>
                                <input type="tel" id="edit_phone" name="phone_number" value="<?php echo e($user->phone_number ?? ''); ?>" style="width: 100%; border: 1px solid #d8e1dc; border-radius: var(--radius-sm); outline: none; background: #fbfdfb; color: var(--ink); transition: all var(--ease); font-size: .85rem; padding: 8px 10px;">
                            </div>
                            <div style="display: flex; gap: 12px; margin-top: 16px;">
                                <button type="button" id="cancelEditPersonalBtn" class="btn" style="flex: 1; background: var(--line); color: var(--ink-soft); padding: 9px 14px;"><i class="fa-solid fa-xmark" aria-hidden="true"></i>Cancel</button>
                                <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fa-solid fa-check" aria-hidden="true"></i>Save changes</button>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Organization & placement -->
                <section class="card" aria-labelledby="org-heading">
                    <div class="card-header">
                        <div class="card-heading">
                            <span class="section-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                            <div>
                                <h2 id="org-heading">Organization &amp; placement</h2>
                                <p>Where this user belongs in the organization.</p>
                            </div>
                        </div>
                        <button id="editOrgBtn" type="button" class="btn" style="width: auto; padding: 8px 14px; font-size: .75rem; background: var(--brand-soft); color: var(--brand); border: 1px solid #c9d8ce; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all var(--ease);"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>Edit</button>
                    </div>
                    <div class="card-body" id="viewOrgInfo">
                        <dl class="details-grid">
                            <div class="detail">
                                <dt class="detail-label">Organization</dt>
                                <dd class="detail-value <?php echo e($user->organization ? '' : 'empty'); ?>"><?php echo e($user->organization?->name ?? 'Not assigned'); ?></dd>
                            </div>
                            <div class="detail">
                                <dt class="detail-label">Department</dt>
                                <dd class="detail-value <?php echo e($user->department ? '' : 'empty'); ?>"><?php echo e($user->department?->name ?? 'Not assigned'); ?></dd>
                            </div>
                            <div class="detail">
                                <dt class="detail-label">Designation</dt>
                                <dd class="detail-value <?php echo e($user->designation ? '' : 'empty'); ?>"><?php echo e($user->designation?->name ?? 'Not assigned'); ?></dd>
                            </div>
                            <div class="detail">
                                <dt class="detail-label">Facility type</dt>
                                <dd class="detail-value <?php echo e($user->facility_type_id ? '' : 'empty'); ?>"><?php echo e($user->facilityType?->name ?? 'Not assigned'); ?></dd>
                            </div>
                            <div class="detail">
                                <dt class="detail-label">Facility</dt>
                                <dd class="detail-value <?php echo e($user->facility ? '' : 'empty'); ?>"><?php echo e($user->facility?->name ?? 'Not assigned'); ?></dd>
                            </div>
                            <div class="detail">
                                <dt class="detail-label">Province</dt>
                                <dd class="detail-value <?php echo e($user->province ? '' : 'empty'); ?>"><?php echo e($user->province?->name ?? 'Not assigned'); ?></dd>
                            </div>
                            <div class="detail">
                                <dt class="detail-label">District</dt>
                                <dd class="detail-value <?php echo e($user->district ? '' : 'empty'); ?>"><?php echo e($user->district?->name ?? 'Not assigned'); ?></dd>
                            </div>
                        </dl>
                    </div>
                    <div class="card-body" id="editOrgInfo" style="display: none;">
                        <form id="editOrgForm">
                            <div class="form-group">
                                <label for="edit_organization">Organization</label>
                                <select id="edit_organization" name="organization_id" style="width: 100%; border: 1px solid #d8e1dc; border-radius: var(--radius-sm); outline: none; background: #fbfdfb; color: var(--ink); transition: all var(--ease); font-size: .85rem; padding: 8px 10px; appearance: none; background-image: linear-gradient(45deg, transparent 50%, var(--muted) 50%), linear-gradient(135deg, var(--muted) 50%, transparent 50%); background-position: calc(100% - 14px) 14px, calc(100% - 10px) 14px; background-size: 4px 4px, 4px 4px; background-repeat: no-repeat;">
                                    <option value="">Select organization</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_department">Department</label>
                                <select id="edit_department" name="department_id" style="width: 100%; border: 1px solid #d8e1dc; border-radius: var(--radius-sm); outline: none; background: #fbfdfb; color: var(--ink); transition: all var(--ease); font-size: .85rem; padding: 8px 10px; appearance: none; background-image: linear-gradient(45deg, transparent 50%, var(--muted) 50%), linear-gradient(135deg, var(--muted) 50%, transparent 50%); background-position: calc(100% - 14px) 14px, calc(100% - 10px) 14px; background-size: 4px 4px, 4px 4px; background-repeat: no-repeat;">
                                    <option value="">Select department</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_designation">Designation</label>
                                <select id="edit_designation" name="designation_id" style="width: 100%; border: 1px solid #d8e1dc; border-radius: var(--radius-sm); outline: none; background: #fbfdfb; color: var(--ink); transition: all var(--ease); font-size: .85rem; padding: 8px 10px; appearance: none; background-image: linear-gradient(45deg, transparent 50%, var(--muted) 50%), linear-gradient(135deg, var(--muted) 50%, transparent 50%); background-position: calc(100% - 14px) 14px, calc(100% - 10px) 14px; background-size: 4px 4px, 4px 4px; background-repeat: no-repeat;">
                                    <option value="">Select designation</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_province">Province</label>
                                <select id="edit_province" name="province_id" style="width: 100%; border: 1px solid #d8e1dc; border-radius: var(--radius-sm); outline: none; background: #fbfdfb; color: var(--ink); transition: all var(--ease); font-size: .85rem; padding: 8px 10px; appearance: none; background-image: linear-gradient(45deg, transparent 50%, var(--muted) 50%), linear-gradient(135deg, var(--muted) 50%, transparent 50%); background-position: calc(100% - 14px) 14px, calc(100% - 10px) 14px; background-size: 4px 4px, 4px 4px; background-repeat: no-repeat;">
                                    <option value="">Select province</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_district">District</label>
                                <select id="edit_district" name="district_id" style="width: 100%; border: 1px solid #d8e1dc; border-radius: var(--radius-sm); outline: none; background: #fbfdfb; color: var(--ink); transition: all var(--ease); font-size: .85rem; padding: 8px 10px; appearance: none; background-image: linear-gradient(45deg, transparent 50%, var(--muted) 50%), linear-gradient(135deg, var(--muted) 50%, transparent 50%); background-position: calc(100% - 14px) 14px, calc(100% - 10px) 14px; background-size: 4px 4px, 4px 4px; background-repeat: no-repeat;">
                                    <option value="">Select district</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_facility_type">Facility type</label>
                                <select id="edit_facility_type" name="facility_type_id" style="width: 100%; border: 1px solid #d8e1dc; border-radius: var(--radius-sm); outline: none; background: #fbfdfb; color: var(--ink); transition: all var(--ease); font-size: .85rem; padding: 8px 10px; appearance: none; background-image: linear-gradient(45deg, transparent 50%, var(--muted) 50%), linear-gradient(135deg, var(--muted) 50%, transparent 50%); background-position: calc(100% - 14px) 14px, calc(100% - 10px) 14px; background-size: 4px 4px, 4px 4px; background-repeat: no-repeat;">
                                    <option value="">Select facility type</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_facility">Facility</label>
                                <select id="edit_facility" name="facility_id" style="width: 100%; border: 1px solid #d8e1dc; border-radius: var(--radius-sm); outline: none; background: #fbfdfb; color: var(--ink); transition: all var(--ease); font-size: .85rem; padding: 8px 10px; appearance: none; background-image: linear-gradient(45deg, transparent 50%, var(--muted) 50%), linear-gradient(135deg, var(--muted) 50%, transparent 50%); background-position: calc(100% - 14px) 14px, calc(100% - 10px) 14px; background-size: 4px 4px, 4px 4px; background-repeat: no-repeat;">
                                    <option value="">Select facility</option>
                                </select>
                            </div>
                            <div style="display: flex; gap: 12px; margin-top: 16px;">
                                <button type="button" id="cancelEditOrgBtn" class="btn" style="flex: 1; background: var(--line); color: var(--ink-soft); padding: 9px 14px;"><i class="fa-solid fa-xmark" aria-hidden="true"></i>Cancel</button>
                                <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fa-solid fa-check" aria-hidden="true"></i>Save changes</button>
                            </div>
                        </form>
                    </div>
                </section>

            </main>

            <!-- ─── Sidebar: approval / rejection actions ─── -->
            <aside class="side-column">

                <?php if($user->status === 'pending'): ?>
                    <section class="card action-card" aria-labelledby="review-heading">
                        <div class="card-header">
                            <div class="card-heading">
                                <span class="section-icon"><i class="fa-solid fa-gavel" aria-hidden="true"></i></span>
                                <div>
                                    <h2 id="review-heading">Review application</h2>
                                    <p>Approve access or request changes.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo e(route('user-approval.approve', $user)); ?>" id="approvalForm">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label for="role_id">Role to assign</label>
                                    <select name="role_id" id="role_id" required>
                                        <option value="">Select a role</option>
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->id); ?>"><?php echo e($role->display_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check" aria-hidden="true"></i>Approve &amp; assign role</button>
                            </form>
                            <div class="form-divider"></div>
                            <form method="POST" action="<?php echo e(route('user-approval.reject', $user)); ?>" id="rejectForm">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label for="rejection_reason">Reason for rejection <span style="font-weight:500;color:var(--muted);">(optional)</span></label>
                                    <textarea name="rejection_reason" id="rejection_reason" maxlength="500" placeholder="Briefly explain why this application is being rejected."></textarea>
                                </div>
                                <p class="form-hint">The user may reapply after the configured waiting period.</p>
                                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-xmark" aria-hidden="true"></i>Reject application</button>
                            </form>
                        </div>
                    </section>
                <?php elseif($user->status === 'approved'): ?>
                    <!-- Combined: Approval details + Assigned roles & Activity history -->
                    <section class="card" aria-labelledby="status-heading">
                        <div class="card-header">
                            <div class="card-heading">
                                <span class="section-icon"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></span>
                                <div>
                                    <h2 id="status-heading">Status & roles</h2>
                                    <p>Approval details and assigned permissions.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body status-details">
                            <div class="approved-note">Access is active and the user can use the assigned permissions.</div>
                            <div class="divider"></div>
                            
                            <!-- Approval details -->
                            <h3 style="margin: 0 0 8px; font-size: .8rem; font-weight: 700; color: var(--ink-soft);">Approval details</h3>
                            <dl class="details-grid" style="margin-bottom: 12px;">
                                <div class="detail">
                                    <dt class="detail-label">Approved by</dt>
                                    <dd class="detail-value"><?php echo e($user->approvedByUser?->name ?? 'System'); ?></dd>
                                </div>
                                <div class="detail">
                                    <dt class="detail-label">Approved on</dt>
                                    <dd class="detail-value"><?php echo e($user->approved_at?->format('M d, Y · h:i A') ?? 'N/A'); ?></dd>
                                </div>
                            </dl>
                            
                            <div class="divider"></div>
                            
                            <!-- Assigned roles -->
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                <h3 style="margin: 0; font-size: .8rem; font-weight: 700; color: var(--ink-soft);">Assigned roles</h3>
                                <span class="count"><?php echo e($user->roles->count()); ?></span>
                            </div>
                            <?php if($user->roles->count() > 0): ?>
                                <ul class="role-list" style="list-style: none; padding: 0; margin: 0 0 12px;">
                                    <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="role-row">
                                            <span class="role-name"><i class="fa-solid fa-certificate" aria-hidden="true"></i><?php echo e($role->display_name); ?></span>
                                            <span class="role-code"><?php echo e($role->name); ?></span>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <div class="empty-state"><i class="fa-regular fa-folder-open" aria-hidden="true"></i>No roles assigned yet.</div>
                            <?php endif; ?>

                            <?php if($roleAssignments->count() > 0 || $rejectionLogs->count() > 0): ?>
                                <div class="divider"></div>
                                <h3 style="margin: 0 0 8px; font-size: .8rem; font-weight: 700; color: var(--ink-soft);">Activity history</h3>
                                <div class="history-list">
                                    <?php $__currentLoopData = $roleAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="timeline-item">
                                            <span class="timeline-dot" aria-hidden="true"></span>
                                            <div>
                                                <p class="timeline-title"><?php echo e($assignment->role->display_name); ?> assigned</p>
                                                <p class="timeline-meta">By <?php echo e($assignment->assignedByUser?->name ?? 'Unknown'); ?> · <?php echo e($assignment->assigned_at?->format('M d, Y · h:i A') ?? 'No date'); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $rejectionLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rejection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="timeline-item">
                                            <span class="timeline-dot" style="background: var(--danger); border-color: var(--danger-soft);" aria-hidden="true"></span>
                                            <div>
                                                <p class="timeline-title">Application rejected</p>
                                                <p class="timeline-meta">By <?php echo e($rejection->rejectedByUser?->name ?? 'Unknown'); ?> · <?php echo e($rejection->created_at->format('M d, Y · h:i A')); ?></p>
                                                <div class="rejection-item"><strong>Reason:</strong> <?php echo e($rejection->reason); ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php elseif($user->status === 'rejected'): ?>
                    <!-- Combined: Rejection details + Assigned roles & Activity history -->
                    <section class="card" aria-labelledby="status-heading">
                        <div class="card-header">
                            <div class="card-heading">
                                <span class="section-icon" style="background:var(--danger-soft);color:var(--danger);"><i class="fa-solid fa-ban" aria-hidden="true"></i></span>
                                <div>
                                    <h2 id="status-heading">Status & roles</h2>
                                    <p>Rejection details and assigned permissions.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body status-details">
                            <!-- Rejection details -->
                            <h3 style="margin: 0 0 8px; font-size: .8rem; font-weight: 700; color: var(--ink-soft);">Rejection details</h3>
                            <dl style="margin-bottom: 12px;">
                                <div class="detail">
                                    <dt class="detail-label">Reason</dt>
                                    <dd class="reason"><?php echo e($user->rejection_reason ?? 'No reason provided'); ?></dd>
                                </div>
                                <div class="detail">
                                    <dt class="detail-label">Rejected on</dt>
                                    <dd class="detail-value"><?php echo e($user->rejected_at?->format('M d, Y · h:i A') ?? 'N/A'); ?></dd>
                                </div>
                                <div class="detail">
                                    <dt class="detail-label">Can reapply on</dt>
                                    <dd class="detail-value"><?php echo e($user->can_reapply_at?->format('M d, Y · h:i A') ?? 'N/A'); ?></dd>
                                </div>
                            </dl>

                            <div class="divider"></div>

                            <!-- Assigned roles -->
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                <h3 style="margin: 0; font-size: .8rem; font-weight: 700; color: var(--ink-soft);">Assigned roles</h3>
                                <span class="count"><?php echo e($user->roles->count()); ?></span>
                            </div>
                            <?php if($user->roles->count() > 0): ?>
                                <ul class="role-list" style="list-style: none; padding: 0; margin: 0 0 12px;">
                                    <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="role-row">
                                            <span class="role-name"><i class="fa-solid fa-certificate" aria-hidden="true"></i><?php echo e($role->display_name); ?></span>
                                            <span class="role-code"><?php echo e($role->name); ?></span>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <div class="empty-state"><i class="fa-regular fa-folder-open" aria-hidden="true"></i>No roles assigned yet.</div>
                            <?php endif; ?>

                            <?php if($roleAssignments->count() > 0 || $rejectionLogs->count() > 0): ?>
                                <div class="divider"></div>
                                <h3 style="margin: 0 0 8px; font-size: .8rem; font-weight: 700; color: var(--ink-soft);">Activity history</h3>
                                <div class="history-list">
                                    <?php $__currentLoopData = $roleAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="timeline-item">
                                            <span class="timeline-dot" aria-hidden="true"></span>
                                            <div>
                                                <p class="timeline-title"><?php echo e($assignment->role->display_name); ?> assigned</p>
                                                <p class="timeline-meta">By <?php echo e($assignment->assignedByUser?->name ?? 'Unknown'); ?> · <?php echo e($assignment->assigned_at?->format('M d, Y · h:i A') ?? 'No date'); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $rejectionLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rejection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="timeline-item">
                                            <span class="timeline-dot" style="background: var(--danger); border-color: var(--danger-soft);" aria-hidden="true"></span>
                                            <div>
                                                <p class="timeline-title">Application rejected</p>
                                                <p class="timeline-meta">By <?php echo e($rejection->rejectedByUser?->name ?? 'Unknown'); ?> · <?php echo e($rejection->created_at->format('M d, Y · h:i A')); ?></p>
                                                <div class="rejection-item"><strong>Reason:</strong> <?php echo e($rejection->reason); ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>

            </aside>
        </div>

    </div>

    <!-- Success Modal -->
    <div id="successModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 20px; padding: 40px; max-width: 400px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="width: 80px; height: 80px; margin: 0 auto 20px; border-radius: 50%; background: #eaf4ee; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-circle-check" style="font-size: 2.5rem; color: #1d4533;"></i>
            </div>
            <h2 id="successTitle" style="margin: 0 0 10px; font-size: 1.3rem; color: var(--ink); font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Changes Saved</h2>
            <p id="successMessage" style="margin: 0 0 20px; color: var(--ink-soft); font-size: 0.95rem;"></p>
            <p id="countdownText" style="margin: 0; color: var(--muted); font-size: 0.85rem; font-weight: 600;">Reloading in <span id="countdown">2</span>s...</p>
        </div>
    </div>

    <!-- Password Reset Confirmation Modal -->
    <div id="passwordResetModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 20px; padding: 40px; max-width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="width: 80px; height: 80px; margin: 0 auto 20px; border-radius: 50%; background: #eaf4ee; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-key" style="font-size: 2.5rem; color: #1d4533;"></i>
            </div>
            <h2 style="margin: 0 0 10px; font-size: 1.3rem; color: var(--ink); font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; text-align: center;">Reset Password?</h2>
            <p id="resetConfirmMessage" style="margin: 0 0 24px; color: var(--ink-soft); font-size: 0.95rem; text-align: center;"></p>
            <div style="display: flex; gap: 12px;">
                <button id="cancelResetBtn" type="button" style="flex: 1; padding: 10px 16px; border: 1px solid var(--line); background: var(--surface); color: var(--ink-soft); border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    Cancel
                </button>
                <button id="confirmResetBtn" type="button" style="flex: 1; padding: 10px 16px; border: none; background: var(--brand); color: white; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    Reset Password
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approvalForm = document.getElementById('approvalForm');
            const rejectForm = document.getElementById('rejectForm');
            const passwordResetForm = document.getElementById('passwordResetForm');

            // ─── Edit Personal Information ───
            const editPersonalBtn = document.getElementById('editPersonalBtn');
            const cancelEditPersonalBtn = document.getElementById('cancelEditPersonalBtn');
            const editPersonalForm = document.getElementById('editPersonalForm');
            const viewPersonalInfo = document.getElementById('viewPersonalInfo');
            const editPersonalInfo = document.getElementById('editPersonalInfo');

            if (editPersonalBtn) {
                editPersonalBtn.addEventListener('click', () => {
                    viewPersonalInfo.style.display = 'none';
                    editPersonalInfo.style.display = 'block';
                });
            }

            if (cancelEditPersonalBtn) {
                cancelEditPersonalBtn.addEventListener('click', () => {
                    editPersonalInfo.style.display = 'none';
                    viewPersonalInfo.style.display = 'block';
                });
            }

            if (editPersonalForm) {
                editPersonalForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Saving...';

                    fetch("<?php echo e(route('user-approval.update-personal', $user)); ?>", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.success === true) {
                            // Show success modal
                            const modal = document.getElementById('successModal');
                            const successTitle = document.getElementById('successTitle');
                            const successMsg = document.getElementById('successMessage');
                            
                            successTitle.textContent = 'Personal Information Updated';
                            successMsg.textContent = 'Your changes have been saved successfully.';
                            modal.style.display = 'flex';

                            let seconds = 2;
                            const countdownSpan = document.getElementById('countdown');
                            const countdownInterval = setInterval(() => {
                                seconds--;
                                countdownSpan.textContent = seconds;
                                if (seconds <= 0) {
                                    clearInterval(countdownInterval);
                                    location.reload();
                                }
                            }, 1000);
                        } else {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                            alert('Error: ' + (data.message || 'Unknown error occurred'));
                        }
                    })
                    .catch(error => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                        alert('An error occurred. Please try again.');
                    });
                });
            }

            // ─── Edit Organization & Placement ───
            const editOrgBtn = document.getElementById('editOrgBtn');
            const cancelEditOrgBtn = document.getElementById('cancelEditOrgBtn');
            const editOrgForm = document.getElementById('editOrgForm');
            const viewOrgInfo = document.getElementById('viewOrgInfo');
            const editOrgInfo = document.getElementById('editOrgInfo');

            if (editOrgBtn) {
                editOrgBtn.addEventListener('click', () => {
                    viewOrgInfo.style.display = 'none';
                    editOrgInfo.style.display = 'block';
                    loadOrgOptions();
                });
            }

            if (cancelEditOrgBtn) {
                cancelEditOrgBtn.addEventListener('click', () => {
                    editOrgInfo.style.display = 'none';
                    viewOrgInfo.style.display = 'block';
                });
            }

            function loadOrgOptions() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch("<?php echo e(route('user-approval.get-org-options')); ?>", {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Populate organizations
                    const orgSelect = document.getElementById('edit_organization');
                    orgSelect.innerHTML = '<option value="">Select organization</option>';
                    data.organizations.forEach(org => {
                        const option = document.createElement('option');
                        option.value = org.id;
                        option.textContent = org.name;
                        if (org.id === <?php echo e($user->organization_id ?? 'null'); ?>) option.selected = true;
                        orgSelect.appendChild(option);
                    });

                    // Populate departments
                    const deptSelect = document.getElementById('edit_department');
                    deptSelect.innerHTML = '<option value="">Select department</option>';
                    data.departments.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.id;
                        option.textContent = dept.name;
                        if (dept.id === <?php echo e($user->department_id ?? 'null'); ?>) option.selected = true;
                        deptSelect.appendChild(option);
                    });

                    // Populate designations
                    const desigSelect = document.getElementById('edit_designation');
                    desigSelect.innerHTML = '<option value="">Select designation</option>';
                    data.designations.forEach(desig => {
                        const option = document.createElement('option');
                        option.value = desig.id;
                        option.textContent = desig.name;
                        if (desig.id === <?php echo e($user->designation_id ?? 'null'); ?>) option.selected = true;
                        desigSelect.appendChild(option);
                    });

                    // Populate provinces
                    const provSelect = document.getElementById('edit_province');
                    provSelect.innerHTML = '<option value="">Select province</option>';
                    data.provinces.forEach(prov => {
                        const option = document.createElement('option');
                        option.value = prov.id;
                        option.textContent = prov.name;
                        if (prov.id === <?php echo e($user->province_id ?? 'null'); ?>) option.selected = true;
                        provSelect.appendChild(option);
                    });

                    // Populate districts
                    const distSelect = document.getElementById('edit_district');
                    distSelect.innerHTML = '<option value="">Select district</option>';
                    data.districts.forEach(dist => {
                        const option = document.createElement('option');
                        option.value = dist.id;
                        option.textContent = dist.name;
                        if (dist.id === <?php echo e($user->district_id ?? 'null'); ?>) option.selected = true;
                        distSelect.appendChild(option);
                    });

                    // Populate facility types
                    const ftSelect = document.getElementById('edit_facility_type');
                    ftSelect.innerHTML = '<option value="">Select facility type</option>';
                    data.facility_types.forEach(ft => {
                        const option = document.createElement('option');
                        option.value = ft.id;
                        option.textContent = ft.name;
                        if (ft.id === <?php echo e($user->facility_type_id ?? 'null'); ?>) option.selected = true;
                        ftSelect.appendChild(option);
                    });

                    // Populate facilities
                    const facSelect = document.getElementById('edit_facility');
                    facSelect.innerHTML = '<option value="">Select facility</option>';
                    data.facilities.forEach(fac => {
                        const option = document.createElement('option');
                        option.value = fac.id;
                        option.textContent = fac.name;
                        if (fac.id === <?php echo e($user->facility_id ?? 'null'); ?>) option.selected = true;
                        facSelect.appendChild(option);
                    });
                });
            }

            if (editOrgForm) {
                editOrgForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Saving...';

                    fetch("<?php echo e(route('user-approval.update-org', $user)); ?>", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.success === true) {
                            // Show success modal
                            const modal = document.getElementById('successModal');
                            const successTitle = document.getElementById('successTitle');
                            const successMsg = document.getElementById('successMessage');
                            
                            successTitle.textContent = 'Organization Information Updated';
                            successMsg.textContent = 'Your changes have been saved successfully.';
                            modal.style.display = 'flex';

                            let seconds = 2;
                            const countdownSpan = document.getElementById('countdown');
                            const countdownInterval = setInterval(() => {
                                seconds--;
                                countdownSpan.textContent = seconds;
                                if (seconds <= 0) {
                                    clearInterval(countdownInterval);
                                    location.reload();
                                }
                            }, 1000);
                        } else {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                            alert('Error: ' + (data.message || 'Unknown error occurred'));
                        }
                    })
                    .catch(error => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                        alert('An error occurred. Please try again.');
                    });
                });
            }

            // Handle approval form
            if (approvalForm) {
                approvalForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const roleSelect = document.getElementById('role_id');
                    if (!roleSelect.value) {
                        alert('Please select a role');
                        return;
                    }

                    const formData = new FormData(this);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Disable button to prevent double submission
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Processing...';

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.success === true) {
                            const modal = document.getElementById('successModal');
                            const message = document.getElementById('successMessage');
                            message.textContent = `${data.user_name} has been approved and assigned the ${data.role_name} role.`;
                            modal.style.display = 'flex';

                            let seconds = 3;
                            const countdownSpan = document.getElementById('countdown');
                            const countdownInterval = setInterval(() => {
                                seconds--;
                                countdownSpan.textContent = seconds;
                                if (seconds <= 0) {
                                    clearInterval(countdownInterval);
                                    window.location.href = data.redirect_url;
                                }
                            }, 1000);
                        } else {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                            alert('Error: ' + (data.message || 'Unknown error occurred'));
                        }
                    })
                    .catch(error => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                        alert('An error occurred. Please try again.');
                    });
                });
            }

            // Handle reject form
            if (rejectForm) {
                rejectForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!confirm('Are you sure you want to reject this user?')) {
                        return;
                    }

                    const formData = new FormData(this);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Disable button to prevent double submission
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Processing...';

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.success === true) {
                            alert('User has been rejected successfully.');
                            window.location.href = data.redirect_url || '<?php echo e(route('user-approval.index')); ?>';
                        } else {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                            alert('Error: ' + (data.message || 'Unknown error occurred'));
                        }
                    })
                    .catch(error => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                        alert('An error occurred. Please try again.');
                    });
                });
            }

            // Handle password reset form
            if (passwordResetForm) {
                passwordResetForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Show confirmation modal instead of alert
                    const modal = document.getElementById('passwordResetModal');
                    const message = document.getElementById('resetConfirmMessage');
                    message.textContent = 'A temporary password will be set to "password123" and sent to <?php echo e($user->email); ?>. Are you sure?';
                    modal.style.display = 'flex';

                    // Handle confirm button
                    const confirmBtn = document.getElementById('confirmResetBtn');
                    const cancelBtn = document.getElementById('cancelResetBtn');

                    const handleConfirm = () => {
                        modal.style.display = 'none';
                        submitPasswordReset();
                        confirmBtn.removeEventListener('click', handleConfirm);
                        cancelBtn.removeEventListener('click', handleCancel);
                    };

                    const handleCancel = () => {
                        modal.style.display = 'none';
                        confirmBtn.removeEventListener('click', handleConfirm);
                        cancelBtn.removeEventListener('click', handleCancel);
                    };

                    confirmBtn.addEventListener('click', handleConfirm);
                    cancelBtn.addEventListener('click', handleCancel);

                    const submitPasswordReset = () => {
                        const formData = new FormData(passwordResetForm);
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        // Disable button to prevent double submission
                        const submitBtn = passwordResetForm.querySelector('button[type="submit"]');
                        const originalText = submitBtn.textContent;
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Processing...';

                        fetch(passwordResetForm.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.success === true) {
                                // Show success message
                                const successModal = document.getElementById('successModal');
                                const successTitle = document.getElementById('successTitle');
                                const successMsg = document.getElementById('successMessage');
                                const countdownText = document.getElementById('countdownText');
                                
                                successTitle.textContent = 'Password Reset Successfully';
                                successMsg.textContent = 'The password has been reset to "password123" and has been sent to ' + data.user_email + '.';
                                countdownText.style.display = 'none';
                                successModal.style.display = 'flex';

                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                submitBtn.disabled = false;
                                submitBtn.textContent = originalText;
                                alert('Error: ' + (data.message || 'Unknown error occurred'));
                            }
                        })
                        .catch(error => {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                            alert('An error occurred. Please try again.');
                        });
                    };
                });
            }
        });

        // Update user status function
        function updateUserStatus(userId, newStatus) {
            if (!confirm(`Are you sure you want to ${newStatus === 'active' ? 'activate' : newStatus === 'inactive' ? 'inactivate' : 'suspend'} this account?`)) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/super-admin/user-approval/${userId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Account ${newStatus} successfully!`);
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update status'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</body>
</html><?php /**PATH D:\OccupationalHealth System\resources\views/super-admin/users/show.blade.php ENDPATH**/ ?>