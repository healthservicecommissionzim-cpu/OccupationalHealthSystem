<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>Access Control Management · System Configuration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: Tahoma, sans-serif; background: offwhite; padding: 2.5rem 0; min-height: 100vh; }
        .container { max-width: 1000px; margin: 0 auto; padding: 0 1rem; }
        .header { margin-bottom: 1rem; }
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
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: white;
            color: #1D4533;
            text-decoration: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            border: 2px solid #1D4533;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .back-button:hover {
            background: #1D4533;
            color: white;
            transform: translateX(-2px);
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .stats-inline { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 0.5rem; 
            margin-bottom: 1.5rem;
        }
        .stat-inline { 
            background: white; 
            padding: 0.75rem; 
            border-radius: 8px; 
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border-top: 3px solid #1D4533;
        }
        .stat-inline p { 
            font-size: 9px; 
            color: #64748b; 
            font-weight: 600; 
            margin-bottom: 0.3rem; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        .stat-inline strong { 
            font-size: 18px; 
            font-weight: 700; 
            color: #1D4533;
        }
        .tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
            overflow-x: auto;
        }
        .tab-btn {
            padding: 0.75rem 1.25rem;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }
        .tab-btn:hover {
            color: #1D4533;
        }
        .tab-btn.active {
            color: #1D4533;
            border-bottom-color: #1D4533;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .table-wrapper { background: white; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); overflow: hidden; }
        .table-header { 
            padding: 1.5rem; 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .table-header h2 { 
            font-size: 16px; 
            font-weight: 700; 
            color: white; 
            display: flex; 
            align-items: center; 
            gap: 0.75rem;
            margin: 0;
        }
        .table-container { overflow-x: auto; }
        table { width: 100%; font-size: 13px; border-collapse: collapse; }
        thead { background: #f7fafc; }
        th { 
            text-align: left; 
            padding: 0.75rem; 
            font-weight: 700; 
            color: #2d3748; 
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }
        td { 
            padding: 0.75rem; 
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
        }
        tbody tr:hover { background: #f7fafc; }
        .role-name { 
            font-weight: 600; 
            color: #1D4533;
        }
        .count-badge {
            background: #1D4533;
            color: white;
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.4rem 0.8rem;
            background: #1D4533;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            background: #0f2818;
            transform: translateY(-1px);
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
        .search-box {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 1.5rem;
        }
        .search-box:focus-within {
            border-color: #1D4533;
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
        }
        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 13px;
            color: #2d3748;
        }
        .search-box input::placeholder {
            color: #a0aec0;
        }
        .search-box i {
            color: #64748b;
            font-size: 14px;
        }
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            display: none;
            align-items: center;
            gap: 0.75rem;
            font-size: 13px;
            font-weight: 600;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .alert.show {
            opacity: 1;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideUp {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-10px); }
        }
        .alert.success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        .alert.error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal.show { display: flex; }
        .modal-content {
            background-color: white;
            margin: auto;
            padding: 0;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-header {
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .role-info {
            background: #f7fafc;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #1D4533;
        }
        .role-info label {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            display: block;
            margin-bottom: 0.3rem;
        }
        .role-info p {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #1D4533;
        }
        .permissions-title {
            font-size: 12px;
            font-weight: 700;
            color: #2d3748;
            text-transform: uppercase;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .permission-checklist {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-height: 350px;
            overflow-y: auto;
        }
        .permission-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .permission-item:hover {
            background: #f7fafc;
            border-color: #1D4533;
        }
        .permission-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #1D4533;
        }
        .permission-item label {
            flex: 1;
            cursor: pointer;
            font-size: 13px;
            color: #2d3748;
            font-weight: 500;
            margin: 0;
        }
        .modal-footer {
            display: flex;
            gap: 0.75rem;
            padding: 1.5rem;
            border-top: 1px solid #e2e8f0;
            background: #f7fafc;
        }
        .modal-btn {
            flex: 1;
            padding: 0.6rem 1rem;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
        .modal-btn-save {
            background: #1D4533;
            color: white;
        }
        .modal-btn-save:hover {
            background: #0f2818;
        }
        .modal-btn-cancel {
            background: #e2e8f0;
            color: #64748b;
        }
        .modal-btn-cancel:hover {
            background: #cbd5e1;
        }
        @media (max-width: 768px) {
            body { padding: 1.5rem 0; }
            .header h1 { font-size: 24px; }
            .header-top { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .stats-inline { grid-template-columns: repeat(2, 1fr); }
            .tabs { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-top">
            </div>
        </div>

        <div class="alert" id="successAlert">
            <i class="fa-solid fa-check-circle"></i>
            <span id="successMessage"></span>
        </div>

        <div class="alert error" id="errorAlert">
            <i class="fa-solid fa-exclamation-circle"></i>
            <span id="errorMessage"></span>
        </div>

        <!-- Tabs with Back Button -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 2px solid #e2e8f0;">
            <div class="tabs" style="border-bottom: none; margin-bottom: 0;">
                <button class="tab-btn active" onclick="switchTab('role-permissions', this)">
                    <i class="fa-solid fa-link"></i> Role Permission Mapping
                </button>
                <button class="tab-btn" onclick="switchTab('roles', this)">
                    <i class="fa-solid fa-user-tag"></i> Roles
                </button>
                <button class="tab-btn" onclick="switchTab('permissions', this)">
                    <i class="fa-solid fa-shield-halved"></i> Permissions
                </button>
            </div>
            <a href="javascript:history.back()" class="back-button" style="margin: 0;">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Role Permissions Tab -->
        <div id="role-permissions" class="tab-content active">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="roleSearch" placeholder="Search roles by name..." />
            </div>

            <?php if($roles->count() > 0): ?>
                <div class="table-wrapper">
                    <div class="table-header">
                        <h2><i class="fa-solid fa-table-list"></i> Role-Permission Mapping [<span id="roleCount"><?php echo e($roles->count()); ?></span>]</h2>
                    </div>
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
                                        <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
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
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fa-solid fa-inbox"></i>
                    <p>No roles found</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Roles Tab -->
        <div id="roles" class="tab-content">
            <iframe src="<?php echo e(route('system.roles')); ?>" style="width: 100%; height: 600px; border: none; border-radius: 12px;"></iframe>
        </div>

        <!-- Permissions Tab -->
        <div id="permissions" class="tab-content">
            <iframe src="<?php echo e(route('system.permissions')); ?>" style="width: 100%; height: 600px; border: none; border-radius: 12px;"></iframe>
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
            
            document.getElementById(tabName).classList.add('active');
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

            document.getElementById('roleCount').textContent = visibleCount;
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
                    updateConfiguredCount();
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
            const modal = document.getElementById('permissionModal');
            const modalContent = modal.querySelector('.modal-content');
            
            const msgDiv = document.createElement('div');
            msgDiv.className = 'modal-message success';
            msgDiv.innerHTML = `
                <i class="fa-solid fa-check-circle"></i>
                <span>${message}</span>
            `;
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
            `;
            document.body.appendChild(msgDiv);
            
            setTimeout(() => {
                msgDiv.style.animation = 'slideUp 0.3s ease';
                setTimeout(() => msgDiv.remove(), 300);
            }, 2000);
        }

        function showModalError(message) {
            const modal = document.getElementById('permissionModal');
            const modalContent = modal.querySelector('.modal-content');
            
            const msgDiv = document.createElement('div');
            msgDiv.className = 'modal-message error';
            msgDiv.innerHTML = `
                <i class="fa-solid fa-exclamation-circle"></i>
                <span>${message}</span>
            `;
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
            `;
            document.body.appendChild(msgDiv);
            
            setTimeout(() => {
                msgDiv.style.animation = 'slideUp 0.3s ease';
                setTimeout(() => msgDiv.remove(), 300);
            }, 4000);
        }

        function updateConfiguredCount() {
            let count = 0;
            Object.values(rolePermissionsMap).forEach(role => {
                if (role.permissions && role.permissions.length > 0) {
                    count++;
                }
            });
            const configuredCountElement = document.getElementById('configuredCount');
            if (configuredCountElement) {
                configuredCountElement.textContent = count;
            }
        }


        window.addEventListener('click', function(event) {
            const modal = document.getElementById('permissionModal');
            if (event.target === modal) {
                closeModal();
            }
        });

        updateConfiguredCount();
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views/system/role_permissions.blade.php ENDPATH**/ ?>