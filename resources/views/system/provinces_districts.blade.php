<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Provinces & Districts · System Configuration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: Tahoma, sans-serif; background: offwhite; padding: 2.5rem 0; min-height: 100vh; }
        .container { max-width: 900px; margin: 0 auto; padding: 0 1rem; }
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
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .btn-group {
            display: flex;
            gap: 0.5rem;
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
        .stats-inline { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
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
        .tree-wrapper { background: white; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); overflow: hidden; margin-bottom: 3rem; }
        .tree-header { 
            padding: 1.5rem; 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .tree-header h2 { 
            font-size: 16px; 
            font-weight: 700; 
            color: white; 
            display: flex; 
            align-items: center; 
            gap: 0.75rem;
        }
        .tree-content {
            padding: 1rem;
        }
        .tree-item {
            margin-bottom: 0.5rem;
        }
        .province-row {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .province-row:hover {
            background: #eef2f5;
            border-color: #1D4533;
        }
        .province-row.expanded {
            background: #e0f2fe;
            border-color: #1D4533;
        }
        .province-info {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .toggle-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            transition: transform 0.2s ease;
            color: #1D4533;
            font-size: 12px;
        }
        .province-row.expanded .toggle-icon {
            transform: rotate(90deg);
        }
        .province-name {
            font-weight: 600;
            color: #1D4533;
            font-size: 14px;
        }
        .province-meta {
            font-size: 11px;
            color: #64748b;
            margin-top: 0.2rem;
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
        .districts-container {
            display: none;
            margin-top: 1rem;
            margin-left: 1.5rem;
            border-left: 2px solid #e2e8f0;
            padding-left: 1rem;
        }
        .districts-container.show {
            display: block;
        }
        .district-row {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            transition: all 0.2s ease;
        }
        .district-row:hover {
            background: #f7fafc;
            border-color: #1D4533;
        }
        .district-name {
            font-weight: 500;
            color: #2d3748;
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
        @media (max-width: 768px) {
            body { padding: 1.5rem 0; }
            .header h1 { font-size: 24px; }
            .header-top { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .btn-group { flex-direction: column; }
            .stats-inline { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-top">
                <h3><i class="fa-solid fa-map"></i> Provinces & Districts</h3>
                <a href="javascript:history.back()" class="back-button">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <div class="stats-inline">
            <div class="stat-inline">
                <p>Total Provinces</p>
                <strong>{{ $statistics['total_provinces'] ?? 0 }}</strong>
            </div>
            <div class="stat-inline">
                <p>Total Districts</p>
                <strong>{{ $statistics['total_districts'] ?? 0 }}</strong>
            </div>
            <div class="stat-inline">
                <p>Total Facilities</p>
                <strong>{{ $statistics['total_facilities'] ?? 0 }}</strong>
            </div>
        </div>

        <div class="tree-wrapper">
            <div class="tree-header">
                <h2><i class="fa-solid fa-sitemap"></i> Hierarchy [<span id="provinceCount">{{ count($provinces) }}</span>]</h2>
            </div>

            <div class="tree-content">
                @forelse($provinces as $province)
                    <div class="tree-item">
                        <div class="province-row" onclick="toggleDistricts(this)">
                            <div class="province-info">
                                <div class="toggle-icon">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </div>
                                <div>
                                    <div class="province-name">{{ $province->name }}</div>
                                    <div class="province-meta">{{ $province->districts_count ?? 0 }} districts • {{ $province->facilities_count ?? 0 }} facilities</div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.3rem;">
                                <button class="action-btn" onclick="editProvince({{ $province->id }}, '{{ $province->name }}', event)" title="Edit Province"><i class="fa-solid fa-edit"></i> Edit</button>
                            </div>
                        </div>

                        <div class="districts-container">
                            @forelse($province->districts as $district)
                                <div class="district-row">
                                    <span class="district-name">{{ $district->name }}</span>
                                    <button class="action-btn" onclick="editDistrict({{ $district->id }}, '{{ $district->name }}', {{ $province->id }}, event)" title="Edit District"><i class="fa-solid fa-edit"></i> Edit</button>
                                </div>
                            @empty
                                <div style="text-align: center; padding: 1rem; color: #64748b; font-size: 13px;">
                                    <i class="fa-solid fa-inbox" style="margin-right: 0.5rem;"></i>
                                    No districts
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox"></i>
                        <p>No provinces found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function toggleDistricts(element) {
            const container = element.nextElementSibling;
            element.classList.toggle('expanded');
            container.classList.toggle('show');
        }

        function editProvince(provinceId, name, event) {
            event.stopPropagation();
            alert(`Edit Province: ${name}`);
        }

        function editDistrict(districtId, name, provinceId, event) {
            event.stopPropagation();
            alert(`Edit District: ${name}`);
        }
    </script>
</body>
</html>
