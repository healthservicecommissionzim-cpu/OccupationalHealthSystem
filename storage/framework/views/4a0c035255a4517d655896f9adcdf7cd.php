<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>Role Permissions · System Configuration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: Inter, 'Segoe UI', Arial, sans-serif; background: #f5f7fa; padding: 35px 25px 70px; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; }
        
        /* Header */
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; gap: 15px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 800; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; }
        
        /* Search and Filter */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; min-width: 250px; }
        .search-box:focus-within { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 12px; color: #263149; }
        .search-box input::placeholder { color: #8a95a5; }
        .search-box i { color: #8a95a5; font-size: 13px; }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .role-name { font-weight: 700; color: #079b6d; font-size: 12px; }
        .count-badge { display: inline-flex; align-items: center; justify-content: center; background: #079b6d; color: white; padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 750; }
        
        .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; text-decoration: none; border-radius: 6px; font-size: 10px; font-weight: 650; transition: all 0.2s; border: none; cursor: pointer; }
        .action-btn:hover { background: #067c58; transform: translateY(-1px); }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Tabs */
        .tab-bar { display: flex; flex-wrap: wrap; background: white; border-bottom: 2px solid #e4e9ee; padding: 0; gap: 0; margin-bottom: 22px; border-radius: 14px 14px 0 0; }
        .tab-btn { padding: 14px 16px; font-size: 12px; font-weight: 600; color: #8a95a5; background: transparent; border: none; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 6px; font-family: Inter, sans-serif; }
        .tab-btn:hover { color: #079b6d; }
        .tab-btn.active { color: #079b6d; border-bottom-color: #079b6d; }
        .tab-btn i { font-size: 14px; }
        
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        /* Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); align-items: flex-start; justify-content: center; overflow-y: auto; backdrop-filter: blur(4px); padding: 40px 20px; }
        .modal.show { display: flex; }
        .modal-content { background: white; margin: auto; border-radius: 14px; box-shadow: 0 25px 80px rgba(0,0,0,.4); width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; }
        .modal-header { background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #079b6d; }
        .modal-header h2 { margin: 0; font-size: 18px; font-weight: 750; display: flex; align-items: center; gap: 10px; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; font-size: 20px; cursor: pointer; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-body { padding: 24px; }
        
        .role-info { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 14px; margin-bottom: 18px; border-left: 4px solid #079b6d; }
        .role-info label { font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 6px; letter-spacing: 0.5px; }
        .role-info p { margin: 0; font-size: 14px; font-weight: 700; color: #101a31; }
        
        .permissions-title { font-size: 11px; font-weight: 700; color: #8a95a5; text-transform: uppercase; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.5px; }
        .permission-checklist { display: flex; flex-direction: column; gap: 8px; max-height: 350px; overflow-y: auto; }
        .permission-item { display: flex; align-items: center; gap: 10px; padding: 10px; border-radius: 8px; border: 1px solid #e4e9ee; transition: all 0.2s; }
        .permission-item:hover { background: #f8fafb; border-color: #079b6d; }
        .permission-item input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; accent-color: #079b6d; }
        .permission-item label { flex: 1; cursor: pointer; font-size: 13px; color: #4e596c; font-weight: 500; margin: 0; }
        
        .modal-footer { display: flex; gap: 10px; padding: 16px 24px; border-top: 1px solid #e4e9ee; background: #f8fafb; }
        .modal-btn { flex: 1; padding: 10px 16px; border: none; border-radius: 8px; font-size: 12px; font-weight: 650; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .modal-btn-save { background: #079b6d; color: white; }
        .modal-btn-save:hover { background: #067c58; }
        .modal-btn-cancel { background: #e4e9ee; color: #4e596c; }
        .modal-btn-cancel:hover { background: #cbd5e1; }
        
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; display: none; align-items: center; gap: 10px; font-size: 12px; font-weight: 600; opacity: 0; transition: opacity 0.3s ease; }
        .alert.show { opacity: 1; animation: slideDown 0.3s ease; display: flex; }
        .alert.success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
        .alert.error { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
        
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 1; transform: translateY(0); } to { opacity: 0; transform: translateY(-10px); } }
        
        @media (max-width: 900px) {
            .page-header { flex-direction: column; align-items: flex-start; }
            .search-box { min-width: 100%; }
        }
        @media (max-width: 650px) {
            body { padding: 22px 15px; }
            .title { font-size: 20px; }
            .tab-btn { padding: 12px 10px; font-size: 11px; }
            .tab-btn span { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-lock"></i></div>
                <div>
                    <h1 class="title">Role Permissions</h1>
                    <div class="sub">Manage role-permission assignments</div>
                </div>
            </div>
            <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <div class="alert" id="successAlert">
            <i class="fa-solid fa-check-circle"></i>
            <span id="successMessage"></span>
        </div>

        <div class="alert error" id="errorAlert">
            <i class="fa-solid fa-exclamation-circle"></i>
            <span id="errorMessage"></span>
        </div>

        <!-- Card with Tabs -->
        <div class="card">
            <div class="tab-bar" role="tablist">
                <button class="tab-btn active" data-tab="role-permissions" role="tab" onclick="switchTab('role-permissions', this)">
                    <i class="fa-solid fa-link"></i> Role Permission Mapping
                </button>
                <button class="tab-btn" data-tab="roles" role="tab" onclick="switchTab('roles', this)">
                    <i class="fa-solid fa-user-tag"></i> Roles
                </button>
                <button class="tab-btn" data-tab="permissions" role="tab" onclick="switchTab('permissions', this)">
                    <i class="fa-solid fa-shield-halved"></i> Permissions
                </button>
            </div>

            <div class="tab-content active" data-tab="role-permissions">
                <div style="padding: 18px 22px;">
                    <div class="chead" style="padding: 0; border: none; margin-bottom: 16px;">
                        <div class="ctitle"><i class="fa-solid fa-table-list"></i> Role-Permission Mappings</div>
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="roleSearch" placeholder="Search roles by name..." />
                        </div>
                    </div>

                    <?php if($roles->count() > 0): ?>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 50px; text-align: center;">#</th>
                                        <th>Role Name</th>
                                        <th style="width: 120px; text-align: center;">Permissions</th>
                                        <th style="width: 100px; text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="role-row" data-role-id="<?php echo e($role->id); ?>" data-role-name="<?php echo e($role->name); ?>">
                                            <td style="text-align: center; font-weight: 600; color: #8a95a5;"><?php echo e($loop->iteration); ?></td>
                                            <td><span class="role-name"><?php echo e($role->display_name ?? $role->name); ?></span></td>
                                            <td style="text-align: center;">
                                                <span class="count-badge" id="permCount<?php echo e($role->id); ?>">
                                                    <?php echo e(count($rolePermissions[$role->id]['permissions'] ?? [])); ?>

                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="action-btn" onclick="openModal(<?php echo e($role->id); ?>, '<?php echo e($role->display_name ?? $role->name); ?>')">
                                                    <i class="fa-solid fa-edit"></i> Manage
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4">
                                                <div class="empty-state">
                                                    <i class="fa-solid fa-inbox"></i>
                                                    <p>No roles found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fa-solid fa-inbox"></i>
                            <p>No roles found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="tab-content" data-tab="roles" style="padding: 18px 22px;">
                <iframe src="<?php echo e(route('system.roles')); ?>" style="width: 100%; height: 600px; border: none; border-radius: 12px;"></iframe>
            </div>

            <div class="tab-content" data-tab="permissions" style="padding: 18px 22px;">
                <iframe src="<?php echo e(route('system.permissions')); ?>" style="width: 100%; height: 600px; border: none; border-radius: 12px;"></iframe>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="permissionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <i class="fa-solid fa-lock"></i>
                    <span id="modalRoleName">Role</span> Permissions
                </h2>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="role-info">
                    <label>Role Name</label>
                    <p id="modalRoleNameDisplay">-</p>
                </div>

                <div class="permissions-title">
                    <i class="fa-solid fa-shield-halved"></i>
                    Assign Permissions
                </div>

                <div class="permission-checklist" id="permissionChecklist"></div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-save" onclick="savePermissions()">
                    <i class="fa-solid fa-check"></i> Save Changes
                </button>
                <button class="modal-btn modal-btn-cancel" onclick="closeModal()">
                    <i class="fa-solid fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        const rolePermissionsMap = <?php echo json_encode($rolePermissions, 15, 512) ?>;
        const permissionsData = <?php echo json_encode($permissions, 15, 512) ?>;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentRoleId = null;

        function switchTab(tabName, btn) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            
            const tabElements = document.querySelectorAll(`[data-tab="${tabName}"]`);
            tabElements.forEach(el => {
                if (el.classList.contains('tab-btn')) el.classList.add('active');
                else el.classList.add('active');
            });
            
            btn.classList.add('active');
        }

        document.getElementById('roleSearch').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.role-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const roleName = row.getAttribute('data-role-name').toLowerCase();
                if (searchTerm === '' || roleName.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function openModal(roleId, roleName) {
            currentRoleId = roleId;

            document.getElementById('modalRoleName').textContent = roleName;
            document.getElementById('modalRoleNameDisplay').textContent = roleName;

            const checklist = document.getElementById('permissionChecklist');
            checklist.innerHTML = '';

            const assignedPerms = rolePermissionsMap[roleId].permissions || [];

            permissionsData.forEach(permission => {
                const isChecked = assignedPerms.includes(permission.id);
                const div = document.createElement('div');
                div.className = 'permission-item';
                div.innerHTML = `
                    <input 
                        type="checkbox" 
                        id="perm_${permission.id}" 
                        value="${permission.id}"
                        class="permission-checkbox"
                        ${isChecked ? 'checked' : ''}
                    />
                    <label for="perm_${permission.id}">
                        ${permission.name}
                    </label>
                `;
                checklist.appendChild(div);
            });

            document.getElementById('permissionModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('permissionModal').classList.remove('show');
            currentRoleId = null;
        }

        function savePermissions() {
            const checkboxes = document.querySelectorAll('#permissionChecklist .permission-checkbox:checked');
            const permissionIds = Array.from(checkboxes).map(cb => parseInt(cb.value));

            fetch('/system/role-permissions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    role_id: currentRoleId,
                    permission_ids: permissionIds
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showModalSuccess(data.message);
                    const permCountElement = document.getElementById(`permCount${currentRoleId}`);
                    if (permCountElement) {
                        permCountElement.textContent = permissionIds.length;
                    }
                    setTimeout(() => closeModal(), 2000);
                } else {
                    showModalError(data.message || 'Failed to save permissions');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showModalError(error.message || 'An error occurred while saving permissions');
            });
        }

        function showModalSuccess(message) {
            const msgDiv = document.createElement('div');
            msgDiv.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: #d1fae5;
                color: #065f46;
                border-left: 4px solid #10b981;
                padding: 1.5rem;
                border-radius: 8px;
                font-weight: 600;
                z-index: 10001;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                min-width: 300px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                animation: slideDown 0.3s ease;
                font-family: Inter, sans-serif;
                font-size: 13px;
            `;
            msgDiv.innerHTML = `
                <i class="fa-solid fa-check-circle"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(msgDiv);
            
            setTimeout(() => {
                msgDiv.style.animation = 'slideUp 0.3s ease';
                setTimeout(() => msgDiv.remove(), 300);
            }, 2000);
        }

        function showModalError(message) {
            const msgDiv = document.createElement('div');
            msgDiv.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: #fee2e2;
                color: #991b1b;
                border-left: 4px solid #ef4444;
                padding: 1.5rem;
                border-radius: 8px;
                font-weight: 600;
                z-index: 10001;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                min-width: 300px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                animation: slideDown 0.3s ease;
                font-family: Inter, sans-serif;
                font-size: 13px;
            `;
            msgDiv.innerHTML = `
                <i class="fa-solid fa-exclamation-circle"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(msgDiv);
            
            setTimeout(() => {
                msgDiv.style.animation = 'slideUp 0.3s ease';
                setTimeout(() => msgDiv.remove(), 300);
            }, 4000);
        }

        window.addEventListener('click', function(event) {
            const modal = document.getElementById('permissionModal');
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\system\role_permissions.blade.php ENDPATH**/ ?>