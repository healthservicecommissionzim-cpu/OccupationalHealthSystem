<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Activity Logs · Security & Audit</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%); padding: 2.5rem 0; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; padding: 0 1rem; }
        .header { margin-bottom: 2rem; }
        .header h1 { 
            font-size: 32px; 
            font-weight: 800; 
            color: #0f172a; 
            display: flex; 
            align-items: center; 
            gap: 0.75rem; 
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }
        .header p { font-size: 14px; color: #64748b; font-weight: 500; }
        .stats-inline { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); 
            gap: 1rem; 
            margin-bottom: 2rem;
        }
        .stat-inline { 
            background: white; 
            padding: 1.25rem; 
            border-radius: 12px; 
            text-align: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border-top: 4px solid;
            transition: all 0.3s ease;
        }
        .stat-inline:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        .stat-inline p { font-size: 11px; color: #64748b; font-weight: 600; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-inline strong { font-size: 28px; font-weight: 800; }
        .stat-total { border-top-color: #0284c7; }
        .stat-total strong { color: #0284c7; }
        .stat-active { border-top-color: #10b981; }
        .stat-active strong { color: #10b981; }
        .stat-login { border-top-color: #f59e0b; }
        .stat-login strong { color: #f59e0b; }
        .stat-update { border-top-color: #8b5cf6; }
        .stat-update strong { color: #8b5cf6; }
        .filter-wrapper { background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); overflow: hidden; margin-bottom: 2rem; }
        .filter-header { 
            padding: 1.5rem; 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .filter-header h3 { 
            font-size: 16px; 
            font-weight: 700; 
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .filter-content { padding: 1.5rem; }
        .filter-group { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 1rem; 
            margin-bottom: 1rem;
        }
        .filter-item label { 
            display: block; 
            font-size: 13px; 
            font-weight: 700; 
            margin-bottom: 0.75rem;
            color: #2d3748;
        }
        .filter-item input, .filter-item select { 
            width: 100%; 
            padding: 0.875rem 1rem; 
            border: 2px solid #e2e8f0; 
            border-radius: 8px; 
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: #fafbfc;
        }
        .filter-item input:focus, .filter-item select:focus { 
            outline: none; 
            border-color: #1D4533; 
            background: #fff;
            box-shadow: 0 0 0 4px rgba(29, 69, 51, 0.1);
        }
        .filter-item input:hover, .filter-item select:hover {
            border-color: #cbd5e1;
        }
        .btn-primary { 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white; 
            border: none; 
            padding: 0.875rem 1.75rem; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 14px; 
            font-weight: 700;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: -0.3px;
        }
        .btn-primary:hover { 
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(29, 69, 51, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .table-wrapper { background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); overflow: hidden; margin-bottom: 2rem; }
        .table-header { 
            padding: 1rem 1.25rem; 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .table-header h3 { 
            font-size: 15px; 
            font-weight: 700; 
            color: white; 
            display: flex; 
            align-items: center; 
            gap: 0.75rem;
            margin: 0;
        }
        .search-box {
            flex: 1;
            max-width: 350px;
        }
        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            background: rgba(255, 255, 255, 0.95);
            color: #2d3748;
            transition: all 0.3s ease;
        }
        .search-box input::placeholder {
            color: #a0aec0;
        }
        .search-box input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        }
        .table-container { overflow-x: auto; }
        table { width: 100%; font-size: 13px; border-collapse: collapse; }
        thead { background: #f7fafc; }
        th { 
            text-align: left; 
            padding: 0.75rem 1.25rem; 
            font-weight: 700; 
            color: #2d3748; 
            font-size: 12px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }
        td { 
            padding: 0.875rem 1.25rem; 
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
        }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f7fafc; }
        tbody tr:last-child td { border-bottom: none; }
        .timestamp { font-weight: 600; color: #1a202c; }
        .user-name { font-weight: 600; color: #1D4533; }
        .activity-type { 
            display: inline-block;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            background: #ede9fe;
            color: #6d28d9;
        }
        .status-badge { 
            display: inline-block; 
            padding: 0.4rem 0.75rem; 
            border-radius: 6px; 
            font-size: 11px; 
            font-weight: 600;
        }
        .status-success { background: #d1f2eb; color: #0f766e; }
        .status-warning { background: #fef3c7; color: #92400e; }
        .status-danger { background: #fee2e2; color: #991b1b; }
        .view-link {
            color: #1D4533;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .view-link:hover {
            color: #163a2a;
            text-decoration: underline;
        }
        .empty-state { 
            text-align: center; 
            padding: 3rem 1.5rem;
        }
        .empty-state i { 
            font-size: 48px; 
            color: #cbd5e1; 
            margin-bottom: 1rem; 
        }
        .empty-state p { 
            font-size: 16px; 
            color: #64748b; 
            font-weight: 600; 
        }
        .pagination-wrapper { 
            padding: 1.5rem; 
            text-align: center; 
            background: #f7fafc; 
            border-top: 1px solid #e2e8f0;
        }
        .pagination { 
            display: flex; 
            justify-content: center; 
            gap: 0.5rem; 
            flex-wrap: wrap;
        }
        .pagination button { 
            padding: 0.5rem 0.75rem; 
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .pagination button:hover {
            border-color: #1D4533;
            color: #1D4533;
        }
        .pagination button.active { 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white; 
            border-color: #1D4533; 
        }
        .info-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            padding: 1.5rem;
            border-left: 4px solid #0284c7;
        }
        .info-section h3 {
            font-size: 14px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .info-section p {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            body { padding: 1.5rem 0; }
            .header h1 { font-size: 24px; }
            .stats-inline { grid-template-columns: repeat(2, 1fr); }
            .filter-group { grid-template-columns: 1fr; }
            .table-header { flex-direction: column; align-items: flex-start; }
            .search-box { max-width: 100%; }
            th, td { padding: 0.75rem; font-size: 12px; }
            .table-container { font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="table-wrapper">
            <div class="table-header">
                <h3><i class="fa-solid fa-list"></i> Recent Activities</h3>
                <div class="search-box">
                    <input type="text" placeholder="Quick search...">
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Activity Type</th>
                            <th>Resource</th>
                            <th>IP Address</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="timestamp"><?php echo e($activity['timestamp']->format('M d, Y H:i')); ?></td>
                                <td class="user-name"><?php echo e($activity['name']); ?></td>
                                <td><span class="activity-type"><?php echo e($activity['activity_type']); ?></span></td>
                                <td><?php echo e($activity['resource']); ?></td>
                                <td><code style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 11px;"><?php echo e($activity['ip_address']); ?></code></td>
                                <td><span class="status-badge status-success"><?php echo e($activity['status']); ?></span></td>
                                <td><a class="view-link" href="#" onclick="openUserActivityModal('<?php echo e($activity['name']); ?>'); return false;">View Details</a></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No activity logs available</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                <div class="pagination">
                    <button class="active">1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Detail Modal -->
    <div id="activityModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; padding: 1rem;">
        <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; display: none;" id="modalOverlay" onclick="closeActivityModal()"></div>
        <div style="background: white; border-radius: 12px; max-width: 900px; margin: 2rem auto; max-height: 85vh; overflow-y: auto; position: relative; z-index: 1000; box-shadow: 0 20px 48px rgba(0,0,0,0.2);">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: white;">
                <h3 style="font-size: 18px; font-weight: 700; color: #2d3748;">Security Audit - <span id="modalUserName" style="color: #1D4533;"></span></h3>
                <button onclick="closeActivityModal()" style="background: none; border: none; font-size: 20px; color: #64748b; cursor: pointer; padding: 0;">&times;</button>
            </div>
            <div style="padding: 1.5rem;">
                <div id="userActivityList" style="display: grid; gap: 1rem;"></div>
            </div>
        </div>
    </div>

    <script>
        // Store all activities for filtering
        const allActivities = <?php echo json_encode($activities, 15, 512) ?>;

        // Live search functionality
        document.querySelector('.search-box input').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableBody = document.querySelector('tbody');
            const rows = tableBody.querySelectorAll('tr');
            
            rows.forEach(row => {
                if (row.querySelector('.empty-state')) return;
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Show all activities for a specific user
        function openUserActivityModal(userName) {
            const userActivities = allActivities.filter(activity => activity.name === userName);
            
            document.getElementById('modalUserName').textContent = userName;
            const listContainer = document.getElementById('userActivityList');
            listContainer.innerHTML = '';
            
            if (userActivities.length === 0) {
                listContainer.innerHTML = '<p style="text-align: center; color: #64748b;">No activities found for this user.</p>';
            } else {
                userActivities.forEach((activity, index) => {
                    const card = document.createElement('div');
                    card.style.cssText = 'border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; background: #f8fafc;';
                    
                    const timestamp = activity.timestamp.split('T').join(' ').substring(0, 16);
                    
                    card.innerHTML = `
                        <div style="margin-bottom: 1.5rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                                <div>
                                    <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Timestamp</label>
                                    <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin-top: 0.5rem;">${timestamp}</p>
                                </div>
                                <div>
                                    <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Activity Type</label>
                                    <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin-top: 0.5rem;">${activity.activity_type}</p>
                                </div>
                                <div>
                                    <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Resource</label>
                                    <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin-top: 0.5rem;">${activity.resource}</p>
                                </div>
                                <div>
                                    <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Status</label>
                                    <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin-top: 0.5rem;">
                                        <span style="background: #d1f2eb; color: #0f766e; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 11px; font-weight: 600;">${activity.status}</span>
                                    </p>
                                </div>
                            </div>

                            <div style="border-top: 2px solid #e2e8f0; padding-top: 1.5rem;">
                                <h4 style="font-size: 14px; font-weight: 700; color: #2d3748; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                                    <i class="fa-solid fa-globe"></i> Network & Location
                                </h4>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">IP Address</label>
                                        <p style="font-size: 12px; color: #2d3748; font-weight: 600; margin-top: 0.5rem; font-family: monospace; background: white; padding: 0.5rem; border-radius: 4px;">${activity.ip_address}</p>
                                    </div>
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Location</label>
                                        <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin-top: 0.5rem;">${activity.location || 'Unknown'}</p>
                                    </div>
                                </div>
                            </div>

                            <div style="border-top: 2px solid #e2e8f0; padding-top: 1.5rem; margin-top: 1.5rem;">
                                <h4 style="font-size: 14px; font-weight: 700; color: #2d3748; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                                    <i class="fa-solid fa-laptop"></i> Device Information
                                </h4>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Device Name</label>
                                        <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin-top: 0.5rem;">${activity.device}</p>
                                    </div>
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Device Type</label>
                                        <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin-top: 0.5rem;">
                                            <i class="fa-solid fa-${activity.device_type === 'Mobile' ? 'mobile' : 'desktop'}"></i> ${activity.device_type}
                                        </p>
                                    </div>
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Operating System</label>
                                        <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin-top: 0.5rem;">${activity.os}</p>
                                    </div>
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Screen Resolution</label>
                                        <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin-top: 0.5rem;">${activity.screen_resolution}</p>
                                    </div>
                                </div>
                            </div>

                            <div style="border-top: 2px solid #e2e8f0; padding-top: 1.5rem; margin-top: 1.5rem;">
                                <h4 style="font-size: 14px; font-weight: 700; color: #2d3748; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                                    <i class="fa-solid fa-browser"></i> Browser Information
                                </h4>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Browser</label>
                                        <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin-top: 0.5rem;">${activity.browser}</p>
                                    </div>
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Browser Version</label>
                                        <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin-top: 0.5rem;">${activity.browser_version}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    listContainer.appendChild(card);
                });
            }
            
            document.getElementById('activityModal').style.display = 'block';
        }

        function closeActivityModal() {
            document.getElementById('activityModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('activityModal');
            if (event.target === modal) {
                closeActivityModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeActivityModal();
            }
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\security\user_activity_logs.blade.php ENDPATH**/ ?>