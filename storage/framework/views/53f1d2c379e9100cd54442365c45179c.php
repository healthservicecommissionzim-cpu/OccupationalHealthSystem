<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>Facilities Directory · System Configuration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: Tahoma, sans-serif; background: offwhite; padding: 2.5rem 0; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; padding: 0 1rem; }
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
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .back-button i {
            font-size: 13px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #1D4533;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .btn-primary:hover {
            background: #0f2818;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .btn-primary i {
            font-size: 13px;
        }
        .stats-inline { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 0.5rem; 
            margin-bottom: 1rem;
        }
        .stat-inline { 
            background: white; 
            padding: 0.5rem 0.75rem; 
            border-radius: 8px; 
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border-top: 3px solid #1D4533;
            transition: all 0.3s ease;
        }
        .stat-inline:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
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
        .table-wrapper { background: white; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); overflow: hidden; margin-bottom: 3rem; }
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
        }
        .table-container { overflow-x: auto; }
        table { width: 100%; font-size: 13px; border-collapse: collapse; }
        thead { background: #f7fafc; }
        th { 
            text-align: left; 
            padding: 0.4rem 0.75rem; 
            font-weight: 700; 
            color: #2d3748; 
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            line-height: 2rem;
        }
        td { 
            padding: 0.4rem 0.75rem; 
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-family: Tahoma, sans-serif;
            line-height: 1.3;
        }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f7fafc; }
        tbody tr:last-child td { border-bottom: none; }
        .facility-name { 
            font-weight: 600; 
            color: #1D4533;
        }
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.4rem 0.6rem;
            background: #1D4533;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.2s ease;
            margin-right: 0.3rem;
        }
        .action-btn:hover {
            background: #0f2818;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(29, 69, 51, 0.2);
        }
        .action-btn.danger {
            background: #ef4444;
        }
        .action-btn.danger:hover {
            background: #dc2626;
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
            padding: 1rem; 
            text-align: center; 
            background: white; 
            border-top: 1px solid #e2e8f0;
        }
        .pagination { 
            display: flex; 
            justify-content: center; 
            gap: 0.3rem; 
            flex-wrap: wrap;
            align-items: center;
        }
        .pagination a, .pagination span { 
            padding: 0.4rem 0.6rem; 
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
            color: #4a5568;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
        }
        .pagination a:hover {
            border-color: #1D4533;
            color: #1D4533;
            background: #f0f4f2;
        }
        .pagination .active span, .pagination .active a { 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white; 
            border-color: #1D4533; 
        }
        .pagination .disabled span {
            color: #cbd5e1;
            cursor: not-allowed;
            background: #f7fafc;
        }
        /* Hide previous/next text but keep page numbers */
        .pagination a:contains("Previous"),
        .pagination a:contains("Next"),
        .pagination span:contains("Previous"),
        .pagination span:contains("Next") {
            display: none !important;
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
            transition: all 0.2s ease;
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
            font-family: 'Roboto', sans-serif;
        }
        .search-box input::placeholder {
            color: #a0aec0;
        }
        .search-box i {
            color: #64748b;
            font-size: 14px;
            flex-shrink: 0;
        }
        @media (max-width: 768px) {
            body { padding: 1.5rem 0; }
            .header h1 { font-size: 24px; }
            .header-top { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .table-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .stats-inline { grid-template-columns: repeat(2, 1fr); }
            th, td { padding: 0.75rem; font-size: 12px; }
        }
        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-header {
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .modal-header h2 {
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }
        .modal-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 0.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .modal-close:hover {
            transform: scale(1.1);
        }
        .modal-body {
            padding: 1.2rem 1.5rem;
        }
        .modal-body .form-group {
            margin-bottom: 1.2rem;
        }
        .modal-body label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .modal-body input[type="text"],
        .modal-body select {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 12px;
            font-family: Tahoma, sans-serif;
            transition: all 0.2s ease;
            background: white;
            color: #2d3748;
        }
        .modal-body input[type="text"]:focus,
        .modal-body select:focus {
            outline: none;
            border-color: #1D4533;
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
        }
        .form-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.2rem;
            padding-top: 1.2rem;
            border-top: 1px solid #e2e8f0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            flex: 1;
        }
        .btn-primary {
            background: #1D4533;
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .btn-primary:hover {
            background: #0f2818;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #2d3748;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .btn-secondary:hover {
            background: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <div class="container">
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
            <div class="header-top">
                <h3><i class="fa-solid fa-hospital"></i> Facilities Directory</h3>
                <a href="javascript:history.back()" class="back-button">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <div class="stats-inline">
            <div class="stat-inline">
                <p>Total Facilities</p>
                <strong><?php echo e(number_format($statistics['total_facilities'])); ?></strong>
            </div>
            <div class="stat-inline">
                <p>Provinces</p>
                <strong><?php echo e(number_format($statistics['total_provinces'])); ?></strong>
            </div>
            <div class="stat-inline">
                <p>Districts</p>
                <strong><?php echo e(number_format($statistics['total_districts'])); ?></strong>
            </div>
            <div class="stat-inline">
                <p>Facility Types</p>
                <strong><?php echo e(number_format($statistics['total_facility_types'])); ?></strong>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="table-header">
                <div style="display: flex; align-items: center; gap: 1.5rem; flex: 1;">
                    <h2><i class="fa-solid fa-table-list"></i> Facilities [<span id="facilityCount"><?php echo e($facilities->count()); ?></span>]</h2>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <label for="recordsPerPage" style="font-size: 12px; font-weight: 600; color: white; margin: 0; white-space: nowrap;">Show:</label>
                        <select id="recordsPerPage" style="padding: 0.4rem 0.6rem; border: 1px solid rgba(255,255,255,0.3); border-radius: 4px; background: white; color: #000; font-size: 12px; font-weight: 600; cursor: pointer;">
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                            <option value="all" selected>All</option>
                        </select>
                    </div>
                </div>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="tableSearch" placeholder="Search by name, type, or district..." />
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
                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
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
<?php /**PATH D:\OccupationalHealth System\resources\views/system/facilities.blade.php ENDPATH**/ ?>