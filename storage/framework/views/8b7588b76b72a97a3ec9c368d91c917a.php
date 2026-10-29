<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>Facilities Directory · System Configuration</title>
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
        
        /* Stats */
        .stats-inline { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
        .stat-inline { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 18px; text-align: center; transition: all 0.2s; border-top: 3px solid #079b6d; }
        .stat-inline:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16,26,49,.08); }
        .stat-inline p { font-size: 10px; color: #8a95a5; font-weight: 700; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-inline strong { font-size: 24px; font-weight: 750; color: #101a31; display: block; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        
        /* Search and Filter */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; min-width: 250px; }
        .search-box:focus-within { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 12px; color: #263149; }
        .search-box input::placeholder { color: #8a95a5; }
        .search-box i { color: #8a95a5; font-size: 13px; }
        
        .filter-select { padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; color: #263149; font-weight: 600; cursor: pointer; outline: none; }
        .filter-select:hover { border-color: #079b6d; }
        .filter-select:focus { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        
        .records-label { font-size: 11px; font-weight: 650; color: #4e596c; margin: 0; white-space: nowrap; }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .facility-name { font-weight: 700; color: #079b6d; font-size: 12px; }
        
        .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; text-decoration: none; border-radius: 6px; font-size: 10px; font-weight: 650; transition: all 0.2s; border: none; cursor: pointer; }
        .action-btn:hover { background: #067c58; transform: translateY(-1px); }
        .action-btn.danger { background: #ef4444; }
        .action-btn.danger:hover { background: #dc2626; }
        
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
        .modal-body input[type="text"], .modal-body select { width: 100%; padding: 10px 12px; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; transition: all 0.2s; background: white; color: #263149; font-family: Inter, sans-serif; }
        .modal-body input[type="text"]:focus, .modal-body select:focus { outline: none; border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        .form-actions { display: flex; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e4e9ee; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: 650; transition: all 0.2s; text-decoration: none; flex: 1; }
        .btn-primary { background: #079b6d; color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
        .btn-primary:hover { background: #067c58; transform: translateY(-1px); }
        .btn-secondary { background: #e4e9ee; color: #4e596c; box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
        .btn-secondary:hover { background: #cbd5e1; transform: translateY(-1px); }
        
        /* Responsive */
        @media (max-width: 900px) {
            .stats-inline { grid-template-columns: repeat(2, 1fr); }
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
                    <h2><i class="fa-solid fa-edit"></i> Edit Facility</h2>
                    <button class="modal-close" onclick="closeEditModal()">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="form-group">
                            <label for="editName">Facility Name</label>
                            <input type="text" id="editName" name="name" placeholder="Enter facility name" required />
                        </div>

                        <div class="form-group">
                            <label for="editFacilityType">Facility Type</label>
                            <select id="editFacilityType" name="facility_type_id" required>
                                <option value="">-- Select Facility Type --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editProvince">Province</label>
                            <select id="editProvince" name="province_id" required>
                                <option value="">-- Select Province --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editDistrict">District</label>
                            <select id="editDistrict" name="district_id" required>
                                <option value="">-- Select District --</option>
                            </select>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-check"></i>
                                Save Changes
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                                <i class="fa-solid fa-times"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-hospital"></i></div>
                <div>
                    <h1 class="title">Facilities Directory</h1>
                    <div class="sub">Manage health facilities across all provinces</div>
                    <span class="case">Total: <?php echo e(number_format($statistics['total_facilities'])); ?> facilities</span>
                </div>
            </div>
            <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <div class="card">
            <div class="chead">
                <div class="filters" style="flex: 1;">
                    <div class="ctitle"><i class="fa-solid fa-table-list"></i> &nbsp; Facility Records [<span id="facilityCount"><?php echo e($facilities->count()); ?></span>]</div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label for="recordsPerPage" class="records-label">Show:</label>
                        <select id="recordsPerPage" class="filter-select">
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                            <option value="all" selected>All</option>
                        </select>
                    </div>
                </div>
                <div class="filters">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="tableSearch" placeholder="Search by name, type, or district..." />
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Facility Name</th>
                            <th>Type</th>
                            <th>District</th>
                            <th>Province</th>
                            <th style="width: 120px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $facilities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;"><?php echo e($loop->iteration); ?></td>
                                <td><span class="facility-name"><?php echo e($facility->name); ?></span></td>
                                <td><?php echo e($facility->facilityType->name ?? 'N/A'); ?></td>
                                <td><?php echo e($facility->district->name ?? 'N/A'); ?></td>
                                <td><?php echo e($facility->district->province->name ?? 'N/A'); ?></td>
                                <td style="text-align: center;">
                                    <button class="action-btn" onclick="openEditModal(<?php echo e($facility->id); ?>, '<?php echo e($facility->name); ?>', <?php echo e($facility->facility_type_id); ?>, <?php echo e($facility->district_id); ?>)" title="Edit Facility"><i class="fa-solid fa-edit"></i> Edit</button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No facilities found</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let facilityTypes = [];
        let provinces = [];
        let allDistricts = [];
        let currentFacilityId = null;
        let allRows = [];
        let recordsPerPage = 'all';

        document.addEventListener('DOMContentLoaded', function() {
            // Load facility types and provinces for the modal
            loadFacilityTypesAndProvinces();

            // Store all rows for pagination
            allRows = Array.from(document.querySelectorAll('table tbody tr')).filter(row => {
                return !(row.cells.length === 1 && row.cells[0].colSpan > 1);
            });

            // Handle records per page dropdown
            document.getElementById('recordsPerPage').addEventListener('change', function() {
                recordsPerPage = this.value;
                paginateTable();
            });

            document.getElementById('tableSearch').addEventListener('keyup', function() {
                paginateTable();
            });

            // Handle form submission
            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitEditForm();
            });

            // Hide pagination wrapper
            const paginationWrapper = document.querySelector('.pagination-wrapper');
            if (paginationWrapper) {
                paginationWrapper.style.display = 'none';
            }

            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                const modal = document.getElementById('editModal');
                if (e.target === modal) {
                    closeEditModal();
                }
            });

            // Initial pagination
            paginateTable();
        });

        function paginateTable() {
            const searchTerm = document.getElementById('tableSearch').value.toLowerCase();
            const tbody = document.querySelector('table tbody');
            
            // Filter rows based on search
            let filteredRows = allRows.filter(row => {
                const text = row.textContent.toLowerCase();
                return searchTerm === '' || text.includes(searchTerm);
            });

            // Clear tbody
            tbody.innerHTML = '';

            if (filteredRows.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fa-solid fa-inbox"></i>
                            <p>No facilities found</p>
                        </div>
                    </td>
                `;
                tbody.appendChild(emptyRow);
                updateFacilityCount(0);
                return;
            }

            // Paginate
            let pagesToShow = recordsPerPage === 'all' ? filteredRows.length : parseInt(recordsPerPage);
            filteredRows.slice(0, pagesToShow).forEach((row, index) => {
                // Update row number
                row.cells[0].textContent = index + 1;
                tbody.appendChild(row);
            });

            updateFacilityCount(filteredRows.length);
        }

        function updateFacilityCount(count) {
            document.getElementById('facilityCount').textContent = count;
        }

        function loadFacilityTypesAndProvinces() {
            facilityTypes = <?php echo json_encode($facilityTypes->map(fn($t) => ['id' => $t->id, 'name' => $t->name])); ?>;
            provinces = <?php echo json_encode($provinces->map(fn($p) => ['id' => $p->id, 'name' => $p->name])); ?>;
            allDistricts = <?php echo json_encode(\App\Models\District::select('id', 'name', 'province_id')->get()); ?>;
        }

        function openEditModal(facilityId, name, facilityTypeId, districtId) {
            currentFacilityId = facilityId;

            // Set form action
            document.getElementById('editForm').action = `/system/facilities/${facilityId}`;

            // Pre-fill form fields
            document.getElementById('editName').value = name;
            document.getElementById('editFacilityType').value = facilityTypeId;

            // Populate facility types dropdown
            const facilityTypeSelect = document.getElementById('editFacilityType');
            facilityTypeSelect.innerHTML = '<option value="">-- Select Facility Type --</option>';
            facilityTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type.id;
                option.textContent = type.name;
                option.selected = type.id == facilityTypeId;
                facilityTypeSelect.appendChild(option);
            });

            // Populate provinces dropdown
            const provinceSelect = document.getElementById('editProvince');
            provinceSelect.innerHTML = '<option value="">-- Select Province --</option>';
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });

            // Set current district's province
            const currentDistrict = allDistricts.find(d => d.id == districtId);
            if (currentDistrict) {
                provinceSelect.value = currentDistrict.province_id;
                loadDistrictsForProvince(currentDistrict.province_id, districtId);
            }

            // Show modal
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            currentFacilityId = null;
        }

        function loadDistrictsForProvince(provinceId, selectedDistrictId = null) {
            const districtSelect = document.getElementById('editDistrict');
            districtSelect.innerHTML = '<option value="">-- Select District --</option>';

            const districtsForProvince = allDistricts.filter(d => d.province_id == provinceId);
            districtsForProvince.forEach(district => {
                const option = document.createElement('option');
                option.value = district.id;
                option.textContent = district.name;
                option.selected = selectedDistrictId && district.id == selectedDistrictId;
                districtSelect.appendChild(option);
            });
        }

        document.getElementById('editProvince').addEventListener('change', function() {
            if (this.value) {
                loadDistrictsForProvince(this.value);
            }
        });

        function submitEditForm() {
            const formData = new FormData(document.getElementById('editForm'));
            const facilityId = currentFacilityId;

            fetch(`/system/facilities/${facilityId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    name: formData.get('name'),
                    facility_type_id: formData.get('facility_type_id'),
                    district_id: formData.get('district_id'),
                })
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Update failed');
            })
            .then(data => {
                // Show success message
                alert('Facility updated successfully!');
                closeEditModal();
                // Reload the page to show updated data
                location.reload();
            })
            .catch(error => {
                alert('Error updating facility: ' + error.message);
            });
        }
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\system\facilities.blade.php ENDPATH**/ ?>