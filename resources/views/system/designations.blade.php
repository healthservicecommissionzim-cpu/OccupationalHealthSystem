<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Designations · System Configuration</title>
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
        
        /* Search */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; min-width: 250px; }
        .search-box:focus-within { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 12px; color: #263149; }
        .search-box input::placeholder { color: #8a95a5; }
        .search-box i { color: #8a95a5; font-size: 13px; }
        
        /* Button */
        .btn-primary { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #079b6d; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: 650; transition: all 0.2s; }
        .btn-primary:hover { background: #067c58; transform: translateY(-1px); }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .desig-name { font-weight: 700; color: #079b6d; font-size: 12px; }
        
        .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; text-decoration: none; border-radius: 6px; font-size: 10px; font-weight: 650; transition: all 0.2s; border: none; cursor: pointer; }
        .action-btn:hover { background: #067c58; transform: translateY(-1px); }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Modal */
        .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 2000; backdrop-filter: blur(4px); animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-content { background: white; border-radius: 14px; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 80px rgba(0,0,0,0.4); border: 2px solid #e1e7ee; animation: slideUp 0.3s ease; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-header { padding: 20px 24px; background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); color: white; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #079b6d; }
        .modal-header h2 { font-size: 18px; font-weight: 750; display: flex; align-items: center; gap: 10px; margin: 0; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; font-size: 20px; cursor: pointer; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-body { padding: 24px; }
        .modal-body .form-group { margin-bottom: 18px; }
        .modal-body label { display: block; font-size: 11px; font-weight: 700; color: #4e596c; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .modal-body input[type="text"] { width: 100%; padding: 10px 12px; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; transition: all 0.2s; background: white; color: #263149; font-family: Inter, sans-serif; }
        .modal-body input[type="text"]:focus { outline: none; border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        .form-actions { display: flex; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e4e9ee; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: 650; transition: all 0.2s; text-decoration: none; flex: 1; }
        .btn-primary-modal { background: #079b6d; color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
        .btn-primary-modal:hover { background: #067c58; transform: translateY(-1px); }
        .btn-secondary-modal { background: #e4e9ee; color: #4e596c; box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
        .btn-secondary-modal:hover { background: #cbd5e1; transform: translateY(-1px); }
        
        /* Toast */
        .toast { position: fixed; bottom: 24px; right: 24px; padding: 16px 20px; border-radius: 8px; color: white; font-size: 13px; font-weight: 550; box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: slideInRight 0.3s ease; z-index: 9999; display: flex; align-items: center; gap: 12px; }
        @keyframes slideInRight { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(400px); opacity: 0; } }
        .toast.success { background: #10b981; }
        .toast.error { background: #ef4444; }
        .toast.removing { animation: slideOutRight 0.3s ease; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .fa-spin { animation: spin 1s linear infinite; }
        
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
        <!-- Edit Modal -->
        <div id="editModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fa-solid fa-edit"></i> Edit Designation</h2>
                    <button class="modal-close" onclick="closeEditModal()">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="editName">Designation Name</label>
                            <input type="text" id="editName" name="name" placeholder="Enter designation name" required />
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary-modal">
                                <i class="fa-solid fa-check"></i>
                                Save Changes
                            </button>
                            <button type="button" class="btn btn-secondary-modal" onclick="closeEditModal()">
                                <i class="fa-solid fa-times"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Designation Modal -->
        <div id="addModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fa-solid fa-plus"></i> Add Designation</h2>
                    <button class="modal-close" onclick="closeAddModal()">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addForm" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="addName">Designation Name</label>
                            <input type="text" id="addName" name="name" placeholder="Enter designation name" required />
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary-modal">
                                <i class="fa-solid fa-plus"></i>
                                Add Designation
                            </button>
                            <button type="button" class="btn btn-secondary-modal" onclick="closeAddModal()">
                                <i class="fa-solid fa-times"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-id-badge"></i></div>
                <div>
                    <h1 class="title">Designations</h1>
                    <div class="sub">Manage job designations and titles</div>
                    <span class="case">Total: {{ $designations->count() }} designations</span>
                </div>
            </div>
            <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <!-- Designations Table -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-table-list"></i> &nbsp; Designation Records</div>
                <div class="filters">
                    <button class="btn-primary" onclick="openAddModal()" title="Add Designation">
                        <i class="fa-solid fa-plus"></i>
                        Add Designation
                    </button>
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="tableSearch" placeholder="Search by name..." />
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Designation Name</th>
                            <th style="width: 80px; text-align: center;">Users</th>
                            <th style="width: 100px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($designations ?? [] as $designation)
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;">{{ $loop->iteration }}</td>
                                <td><span class="desig-name">{{ $designation->name }}</span></td>
                                <td style="text-align: center;">{{ $designation->users_count }}</td>
                                <td style="text-align: center;">
                                    <button class="action-btn" onclick="openEditModal({{ $designation->id }}, '{{ $designation->name }}')" title="Edit Designation"><i class="fa-solid fa-edit"></i> Edit</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No designations found</p>
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
        let currentDesignationId = null;

        function showMessage(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fa-solid fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

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
            });

            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitEditForm();
            });

            document.getElementById('addForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitAddForm();
            });

            window.addEventListener('click', function(e) {
                const editModal = document.getElementById('editModal');
                const addModal = document.getElementById('addModal');
                if (e.target === editModal) {
                    closeEditModal();
                }
                if (e.target === addModal) {
                    closeAddModal();
                }
            });
        });

        function openEditModal(desigId, name) {
            currentDesignationId = desigId;
            document.getElementById('editForm').action = `/system/designations/${desigId}`;
            document.getElementById('editName').value = name;
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            currentDesignationId = null;
        }

        function openAddModal() {
            document.getElementById('addName').value = '';
            document.getElementById('addForm').action = '/system/designations';
            document.getElementById('addModal').style.display = 'flex';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function submitEditForm() {
            const submitBtn = document.querySelector('#editForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const desigId = currentDesignationId;
            const desigName = document.getElementById('editName').value.trim();

            if (!desigName) {
                showMessage('Please enter a designation name', 'error');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

            fetch(`/system/designations/${desigId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    name: desigName,
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        const errorMessage = data.message || data.errors?.name?.[0] || 'Failed to update designation';
                        throw new Error(errorMessage);
                    }).catch(e => {
                        throw new Error(e.message || 'Server error');
                    });
                }
                return response.json();
            })
            .then(data => {
                showMessage(data.message || 'Designation updated successfully!', 'success');
                closeEditModal();
                setTimeout(() => location.reload(), 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage(error.message || 'Error updating designation', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        function submitAddForm() {
            const submitBtn = document.querySelector('#addForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const desigName = document.getElementById('addName').value.trim();

            if (!desigName) {
                showMessage('Please enter a designation name', 'error');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

            fetch('/system/designations', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    name: desigName,
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        const errorMessage = data.message || data.errors?.name?.[0] || 'Failed to save designation';
                        throw new Error(errorMessage);
                    }).catch(e => {
                        throw new Error(e.message || 'Server error');
                    });
                }
                return response.json();
            })
            .then(data => {
                showMessage(data.message || 'Designation added successfully!', 'success');
                closeAddModal();
                setTimeout(() => location.reload(), 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage(error.message || 'Error saving designation', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }
    </script>
</body>
</html>
