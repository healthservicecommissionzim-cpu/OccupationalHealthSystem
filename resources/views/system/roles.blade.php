<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Roles · System Configuration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 1400px; margin: auto; padding: 35px 25px 70px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .case { display: inline-block; margin-top: 9px; padding: 5px 10px; border-radius: 6px; background: #eef2f6; color: #5d687b; font-size: 11px; font-weight: 700; }
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
        
        /* Button */
        .btn-add { background: #079b6d; color: white; border: none; padding: 10px 17px; border-radius: 8px; font-size: 12px; font-weight: 650; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-add:hover { background: #067c58; transform: translateY(-1px); }
        
        .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; border: none; border-radius: 6px; font-size: 10px; font-weight: 650; text-decoration: none; cursor: pointer; transition: all 0.2s; }
        .action-btn:hover { background: #067c58; transform: translateY(-1px); }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: flex-start; justify-content: center; overflow-y: auto; backdrop-filter: blur(4px); padding: 40px 20px; }
        .modal.active { display: flex; }
        .modal-content { background: white; border-radius: 14px; max-width: 500px; width: 100%; box-shadow: 0 25px 80px rgba(0,0,0,0.4); border: 2px solid #e1e7ee; max-height: 90vh; overflow-y: auto; margin: auto; }
        .modal-header { position: sticky; top: 0; background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #079b6d; z-index: 2001; }
        .modal-header h2 { color: white; font-size: 18px; font-weight: 750; margin: 0; display: flex; align-items: center; gap: 10px; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; font-size: 20px; cursor: pointer; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-body { padding: 24px; }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 11px; font-weight: 750; color: #4e596c; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
        .form-group input { width: 100%; padding: 10px 12px; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; color: #263149; transition: all 0.2s; }
        .form-group input:focus { outline: none; border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        
        .form-actions { display: flex; gap: 10px; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e4e9ee; }
        .btn-primary { background: #079b6d; color: white; border: none; padding: 10px 17px; border-radius: 8px; font-size: 12px; font-weight: 650; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; flex: 1; justify-content: center; }
        .btn-primary:hover { background: #067c58; transform: translateY(-1px); }
        .btn-secondary { background: #e4e9ee; color: #4e596c; border: none; padding: 10px 17px; border-radius: 8px; font-size: 12px; font-weight: 650; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; flex: 1; justify-content: center; }
        .btn-secondary:hover { background: #cbd5e1; }
        
        /* Responsive */
        @media (max-width: 900px) {
            .chead { flex-direction: column; align-items: stretch; }
            .filters { flex-direction: column; }
            .search-box { min-width: 100%; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            table { font-size: 11px; }
            th, td { padding: 10px 8px; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-shield"></i></div>
                <div>
                    <h1 class="title">Roles</h1>
                    <div class="sub">Manage system roles and permissions</div>
                    <span class="case">System Configuration</span>
                </div>
            </div>
            <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <!-- Roles Table -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-table-list"></i> &nbsp; Role Records [<span id="roleCount">{{ $roles->count() }}</span>]</div>
                <div class="filters">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="tableSearch" placeholder="Search by role name..." />
                    </div>
                    <button class="btn-add" onclick="openAddRoleModal()">
                        <i class="fa-solid fa-plus"></i> Add Role
                    </button>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Role Name</th>
                            <th>Display Name</th>
                            <th style="width: 100px; text-align: center;">Users</th>
                            <th style="width: 120px; text-align: center;">Permissions</th>
                            <th style="width: 100px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="rolesTable">
                        @forelse($roles ?? [] as $role)
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;">{{ $loop->iteration }}</td>
                                <td><span class="role-name">{{ $role->name }}</span></td>
                                <td>{{ $role->display_name ?? 'N/A' }}</td>
                                <td style="text-align: center;">{{ $role->users_count ?? 0 }}</td>
                                <td style="text-align: center;">{{ $role->permissions_count ?? 0 }}</td>
                                <td style="text-align: center;">
                                    <button class="action-btn" onclick="manageRole({{ $role->id }}, '{{ $role->name }}')" title="Manage Role">
                                        <i class="fa-solid fa-cog"></i> Manage
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No roles found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div id="addRoleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fa-solid fa-plus"></i> Add Role</h2>
                <button class="modal-close" onclick="closeAddRoleModal()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="addRoleForm" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="roleName">Role Name</label>
                        <input type="text" id="roleName" name="name" placeholder="e.g., admin, editor" required />
                    </div>

                    <div class="form-group">
                        <label for="displayName">Display Name</label>
                        <input type="text" id="displayName" name="display_name" placeholder="e.g., Administrator" required />
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-check"></i> Add Role
                        </button>
                        <button type="button" class="btn-secondary" onclick="closeAddRoleModal()">
                            <i class="fa-solid fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tableSearch').addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('#rolesTable tr');
                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
                        return;
                    }

                    const text = row.textContent.toLowerCase();
                    if (searchTerm === '' || text.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                document.getElementById('roleCount').textContent = visibleCount;
            });
        });

        function manageRole(roleId, roleName) {
            alert(`Manage Role: ${roleName}`);
        }

        function openAddRoleModal() {
            document.getElementById('addRoleModal').classList.add('active');
        }

        function closeAddRoleModal() {
            document.getElementById('addRoleModal').classList.remove('active');
            document.getElementById('addRoleForm').reset();
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('addRoleModal');
            if (e.target === modal) {
                closeAddRoleModal();
            }
        });

        document.getElementById('addRoleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const roleName = document.getElementById('roleName').value;
            const displayName = document.getElementById('displayName').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch('{{ route("system.roles.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    name: roleName,
                    display_name: displayName
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Role created successfully! Refreshing...');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to create role'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the role');
            });
        });
    </script>
</body>
</html>
