<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Provinces & Districts · System Configuration</title>
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
        
        /* Tree/Hierarchy */
        .tree-wrapper { background: white; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); overflow: hidden; margin-bottom: 3rem; }
        .tree-header { 
            padding: 1.5rem; 
            background: linear-gradient(135deg, #079b6d 0%, #067c58 100%);
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
            border-color: #079b6d;
        }
        .province-row.expanded {
            background: #e0f2fe;
            border-color: #079b6d;
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
            color: #079b6d;
            font-size: 12px;
        }
        .province-row.expanded .toggle-icon {
            transform: rotate(90deg);
        }
        .province-name {
            font-weight: 600;
            color: #101a31;
            font-size: 14px;
        }
        .province-meta {
            font-size: 11px;
            color: #718096;
            margin-top: 0.2rem;
        }
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.4rem 0.6rem;
            background: #079b6d;
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
            background: #067c58;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(7, 155, 109, 0.2);
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
            border-color: #079b6d;
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
            color: #718096; 
            font-weight: 600; 
        }
        @media (max-width: 768px) {
            .page { padding: 22px 15px; }
            .title { font-size: 20px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-map"></i></div>
                <div>
                    <h1 class="title">Provinces & Districts</h1>
                    <div class="sub">Manage geographic hierarchy</div>
                    <span class="case">Total: {{ count($provinces) }} provinces</span>
                </div>
            </div>
            <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <!-- Hierarchy Card -->
        <div class="tree-wrapper">
            <div class="tree-header">
                <h2><i class="fa-solid fa-sitemap"></i> Geographic Hierarchy</h2>
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
                                <div style="text-align: center; padding: 1rem; color: #718096; font-size: 13px;">
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
