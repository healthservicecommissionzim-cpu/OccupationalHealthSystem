<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Permissions · System Configuration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: Inter, 'Segoe UI', Arial, sans-serif; background: #f5f7fa; padding: 35px 25px 70px; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; }
        
        /* Search and Filter */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; min-width: 250px; margin-bottom: 22px; }
        .search-box:focus-within { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 12px; color: #263149; }
        .search-box input::placeholder { color: #8a95a5; }
        .search-box i { color: #8a95a5; font-size: 13px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .perm-name { font-weight: 700; color: #079b6d; font-size: 12px; }
        
        .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; text-decoration: none; border-radius: 6px; font-size: 10px; font-weight: 650; transition: all 0.2s; border: none; cursor: pointer; }
        .action-btn:hover { background: #067c58; transform: translateY(-1px); }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Add Button */
        .btn-add { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: #079b6d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 11px; font-weight: 650; transition: all 0.2s; }
        .btn-add:hover { background: #067c58; }
        
        /* Modal */
        .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: none; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px); }
        .modal.show { display: flex; }
        .modal-content { background: white; border-radius: 14px; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 80px rgba(0,0,0,.4); }
        .modal-header { background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #079b6d; }
        .modal-header h2 { margin: 0; font-size: 18px; font-weight: 750; display: flex; align-items: center; gap: 10px; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; font-size: 20px; cursor: pointer; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-body { padding: 24px; }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 6px; letter-spacing: 0.5px; }
        .form-group input { width: 100%; padding: 10px 12px; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; color: #4e596c; font-family: Inter, sans-serif; transition: all 0.2s; }
        .form-group input:focus { outline: none; border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        
        .form-actions { display: flex; gap: 10px; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e4e9ee; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 16px; border: none; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: 650; transition: all 0.2s; flex: 1; }
        .btn-save { background: #079b6d; color: white; }
        .btn-save:hover { background: #067c58; }
        .btn-cancel { background: #e4e9ee; color: #4e596c; }
        .btn-cancel:hover { background: #cbd5e1; }
        
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        @media (max-width: 900px) {
            .chead { flex-direction: column; align-items: stretch; }
            .search-box { min-width: 100%; }
        }
        @media (max-width: 650px) {
            body { padding: 22px 15px; }
            th, td { padding: 10px 8px; font-size: 11px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Add Permission Modal -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fa-solid fa-plus"></i> Add Permission</h2>
                    <button class="modal-close" onclick="closeAddModal()">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addForm" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="addSlug">Slug</label>
                            <input type="text" id="addSlug" name="slug" placeholder="e.g., create_incidents" required />
                        </div>

                        <div class="form-group">
                            <label for="addName">Permission Name</label>
                            <input type="text" id="addName" name="name" placeholder="Enter permission name" required />
                        </div>

                        <div class="form-group">
                            <label for="addDescription">Description</label>
                            <input type="text" id="addDescription" name="description" placeholder="Enter description" />
                        </div>

                        <div class="form-group">
                            <label for="addCategory">Category</label>
                            <input type="text" id="addCategory" name="category" placeholder="Enter category" />
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-save">
                                <i class="fa-solid fa-check"></i> Add Permission
                            </button>
                            <button type="button" class="btn btn-cancel" onclick="closeAddModal()">
                                <i class="fa-solid fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Permission Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fa-solid fa-edit"></i> Manage Permission</h2>
                    <button class="modal-close" onclick="closeEditModal()">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="editSlug">Slug</label>
                            <input type="text" id="editSlug" name="slug" placeholder="Enter slug" required />
                        </div>

                        <div class="form-group">
                            <label for="editName">Permission Name</label>
                            <input type="text" id="editName" name="name" placeholder="Enter permission name" required />
                        </div>

                        <div class="form-group">
                            <label for="editDescription">Description</label>
                            <input type="text" id="editDescription" name="description" placeholder="Enter description" />
                        </div>

                        <div class="form-group">
                            <label for="editCategory">Category</label>
                            <input type="text" id="editCategory" name="category" placeholder="Enter category" />
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-save">
                                <i class="fa-solid fa-check"></i> Save Changes
                            </button>
                            <button type="button" class="btn btn-cancel" onclick="closeEditModal()">
                                <i class="fa-solid fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="tableSearch" placeholder="Search by permission name..." />
        </div>

        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-table-list"></i> Permissions [<span id="permissionCount">{{ $permissions->count() }}</span>]</div>
                <button class="btn-add" onclick="openAddModal()">
                    <i class="fa-solid fa-plus"></i> Add Permission
                </button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Slug</th>
                            <th>Permission Name</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th style="width: 120px; text-align: center;">Date Created</th>
                            <th style="width: 100px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions ?? [] as $permission)
                            <tr>
                                <td style="text-align: center; font-weight: 600; color: #8a95a5;">{{ $loop->iteration }}</td>
                                <td><span class="perm-name">{{ $permission->slug }}</span></td>
                                <td>{{ $permission->name ?? 'N/A' }}</td>
                                <td style="font-size: 12px; color: #8a95a5;">{{ $permission->description ?? 'N/A' }}</td>
                                <td>{{ $permission->category ?? 'N/A' }}</td>
                                <td style="text-align: center; font-size: 12px;">{{ $permission->created_at ? $permission->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td style="text-align: center;">
                                    <button class="action-btn" onclick="openEditModal({{ $permission->id }}, '{{ $permission->slug }}', '{{ $permission->name }}', '{{ $permission->description }}', '{{ $permission->category }}')" title="Manage Permission"><i class="fa-solid fa-cog"></i> Manage</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No permissions found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tableSearch').addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('table tbody tr');
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

                document.getElementById('permissionCount').textContent = visibleCount;
            });
        });

        let currentPermissionId = null;

        function openAddModal() {
            document.getElementById('addModal').classList.add('show');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('show');
            document.getElementById('addForm').reset();
        }

        function openEditModal(permissionId, slug, name, description, category) {
            currentPermissionId = permissionId;
            document.getElementById('editForm').action = `/system/permissions/${permissionId}`;
            document.getElementById('editSlug').value = slug;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description || '';
            document.getElementById('editCategory').value = category || '';
            document.getElementById('editModal').classList.add('show');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
            document.getElementById('editForm').reset();
            currentPermissionId = null;
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            if (e.target === addModal) {
                closeAddModal();
            }
            if (e.target === editModal) {
                closeEditModal();
            }
        });

        // Handle add form submission
        document.getElementById('addForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // TODO: Add API call to create permission
            alert('Permission added successfully!');
            closeAddModal();
            // location.reload();
        });

        // Handle edit form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // TODO: Add API call to update permission
            alert('Permission updated successfully!');
            closeEditModal();
            // location.reload();
        });
    </script>
</body>
</html>
