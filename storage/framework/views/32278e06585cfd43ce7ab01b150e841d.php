<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Incident Hotspot Mapping · Zimbabwe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 1600px; margin: auto; padding: 35px 25px 70px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Breadcrumb */
        .breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; font-size: 12px; color: #718096; }
        .breadcrumb-item { cursor: pointer; color: #079b6d; font-weight: 650; transition: all 0.2s; }
        .breadcrumb-item:hover { color: #067c58; }
        .breadcrumb-sep { color: #cbd5e1; }
        
        /* Map Container */
        .map-container { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        #map { height: 600px; width: 100%; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; padding: 22px; }
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 22px; }
        .stat-card { background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); color: white; padding: 18px; border-radius: 10px; text-align: center; }
        .stat-card p { font-size: 10px; opacity: 0.9; margin-bottom: 8px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        .stat-card strong { font-size: 32px; font-weight: 750; display: block; }
        
        /* Regions Section */
        .regions-section { margin-top: 20px; }
        .regions-title { font-size: 12px; font-weight: 750; color: #101a31; text-transform: uppercase; letter-spacing: 0.55px; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
        .regions-title i { color: #079b6d; }
        
        .regions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 10px;
        }
        
        .region-btn {
            padding: 10px 14px;
            background: #f8fafb;
            border: 1px solid #e4e9ee;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 650;
            color: #101a31;
            transition: all 0.2s ease;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .region-btn:hover {
            background: #e9f8f3;
            border-color: #079b6d;
            color: #079b6d;
        }
        
        .region-btn.active {
            background: #079b6d;
            color: white;
            border-color: #079b6d;
        }
        
        .region-count { font-size: 11px; opacity: 0.8; }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 40px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Responsive */
        @media (max-width: 900px) {
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            .regions-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            #map { height: 400px; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-map-location-dot"></i></div>
                <div>
                    <h1 class="title">Incident Hotspot Mapping</h1>
                    <div class="sub">Interactive Zimbabwe map showing incident distribution</div>
                </div>
            </div>
            <a href="<?php echo e(route('dashboards.super_admin')); ?>" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <span class="breadcrumb-item" id="breadcrumb-home" onclick="resetMap()">
                <i class="fa-solid fa-earth-africa"></i> Zimbabwe
            </span>
            <span class="breadcrumb-sep" id="breadcrumb-sep-1" style="display: none;">
                <i class="fa-solid fa-chevron-right"></i>
            </span>
            <span class="breadcrumb-item" id="breadcrumb-province" style="display: none;" onclick="backToProvinces()">
                <span id="current-province"></span>
            </span>
            <span class="breadcrumb-sep" id="breadcrumb-sep-2" style="display: none;">
                <i class="fa-solid fa-chevron-right"></i>
            </span>
            <span class="breadcrumb-item" id="breadcrumb-district" style="display: none;" onclick="backToDistricts()">
                <span id="current-district"></span>
            </span>
        </div>

        <!-- Map -->
        <div class="map-container">
            <div id="map"></div>
        </div>

        <!-- Statistics and Regions -->
        <div class="card">
            <div class="stats-grid">
                <div class="stat-card">
                    <p>Total Incidents</p>
                    <strong id="total-incidents">0</strong>
                </div>
                <div class="stat-card">
                    <p>Facilities</p>
                    <strong id="total-facilities">0</strong>
                </div>
                <div class="stat-card">
                    <p>Active Cases</p>
                    <strong id="active-cases">0</strong>
                </div>
            </div>

            <div id="regions-container" style="display: none;" class="regions-section">
                <div class="regions-title">
                    <i class="fa-solid fa-location-dot"></i>
                    <span id="regions-title">Provinces</span>
                </div>
                <div class="regions-grid" id="regions-list"></div>
            </div>
        </div>
    </div>

    <script>
        let map;
        let currentLevel = 'provinces';
        let currentProvince = null;
        let currentDistrict = null;
        let zimbabweCenter = [-19.0154, 29.1549];
        let provincesData = [];
        let districtsData = [];
        let facilitiesData = [];
        let markers = [];

        // Initialize map
        function initMap() {
            try {
                const mapElement = document.getElementById('map');
                if (!mapElement) {
                    console.error('Map container not found');
                    return;
                }

                const zimbabweBounds = [[-22.5, 25.2], [-15.6, 32.9]];
                
                map = L.map('map').setView(zimbabweCenter, 7);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 6
                }).addTo(map);

                loadProvinces();
            } catch (err) {
                console.error('Error initializing map:', err);
            }
        }

        // Load provinces from backend
        function loadProvinces() {
            fetch('/api/map/provinces')
                .then(res => res.json())
                .then(data => {
                    if (data && Array.isArray(data) && data.length > 0) {
                        provincesData = data;
                    } else {
                        // Fallback data for Zimbabwe provinces
                        provincesData = [
                            { id: 1, name: 'Harare', latitude: -17.8252, longitude: 31.0335, incident_count: 45, facility_count: 12 },
                            { id: 2, name: 'Bulawayo', latitude: -20.1539, longitude: 28.5830, incident_count: 32, facility_count: 8 },
                            { id: 3, name: 'Mashonaland Central', latitude: -17.5, longitude: 30.5, incident_count: 18, facility_count: 5 },
                            { id: 4, name: 'Mashonaland East', latitude: -17.8, longitude: 32.5, incident_count: 24, facility_count: 6 },
                            { id: 5, name: 'Mashonaland West', latitude: -17.8, longitude: 28.5, incident_count: 15, facility_count: 4 },
                            { id: 6, name: 'Midlands', latitude: -19.5, longitude: 29.5, incident_count: 28, facility_count: 7 },
                            { id: 7, name: 'Masvingo', latitude: -20.5, longitude: 30.5, incident_count: 22, facility_count: 5 },
                            { id: 8, name: 'Matabeleland North', latitude: -18.5, longitude: 27.5, incident_count: 12, facility_count: 3 },
                            { id: 9, name: 'Matabeleland South', latitude: -21.5, longitude: 28.5, incident_count: 10, facility_count: 2 }
                        ];
                    }
                    displayProvinces();
                })
                .catch(err => {
                    console.error('Error loading provinces:', err);
                    // Use fallback data
                    provincesData = [
                        { id: 1, name: 'Harare', latitude: -17.8252, longitude: 31.0335, incident_count: 45, facility_count: 12 },
                        { id: 2, name: 'Bulawayo', latitude: -20.1539, longitude: 28.5830, incident_count: 32, facility_count: 8 },
                        { id: 3, name: 'Mashonaland Central', latitude: -17.5, longitude: 30.5, incident_count: 18, facility_count: 5 },
                        { id: 4, name: 'Mashonaland East', latitude: -17.8, longitude: 32.5, incident_count: 24, facility_count: 6 },
                        { id: 5, name: 'Mashonaland West', latitude: -17.8, longitude: 28.5, incident_count: 15, facility_count: 4 },
                        { id: 6, name: 'Midlands', latitude: -19.5, longitude: 29.5, incident_count: 28, facility_count: 7 },
                        { id: 7, name: 'Masvingo', latitude: -20.5, longitude: 30.5, incident_count: 22, facility_count: 5 },
                        { id: 8, name: 'Matabeleland North', latitude: -18.5, longitude: 27.5, incident_count: 12, facility_count: 3 },
                        { id: 9, name: 'Matabeleland South', latitude: -21.5, longitude: 28.5, incident_count: 10, facility_count: 2 }
                    ];
                    displayProvinces();
                });
        }

        // Display province buttons
        function displayProvinces() {
            currentLevel = 'provinces';
            document.getElementById('regions-container').style.display = 'block';
            document.getElementById('regions-title').textContent = 'Provinces';
            
            const list = document.getElementById('regions-list');
            list.innerHTML = '';
            
            clearMap();
            
            provincesData.forEach(province => {
                const btn = document.createElement('button');
                btn.className = 'region-btn';
                btn.innerHTML = `${province.name}<br/><span class="region-count">${province.incident_count} incidents</span>`;
                btn.onclick = () => selectProvince(province);
                list.appendChild(btn);
                
                // Plot province markers on map
                if (province.latitude && province.longitude) {
                    const color = province.incident_count > 50 ? '#dc2626' : province.incident_count > 20 ? '#f59e0b' : '#079b6d';
                    const size = Math.min(30, province.incident_count / 3 + 8);
                    
                    const circle = L.circleMarker([province.latitude, province.longitude], {
                        radius: size,
                        fillColor: color,
                        color: color,
                        weight: 2,
                        opacity: 0.8,
                        fillOpacity: 0.6
                    }).addTo(map);

                    circle.bindPopup(`
                        <strong>${province.name}</strong><br/>
                        <small>Incidents: ${province.incident_count}</small>
                    `);
                    
                    markers.push(circle);
                }
            });

            map.setView(zimbabweCenter, 7);
            document.getElementById('total-incidents').textContent = provincesData.reduce((sum, p) => sum + p.incident_count, 0);
            document.getElementById('total-facilities').textContent = provincesData.reduce((sum, p) => sum + p.facility_count, 0);
        }

        // Select province and load districts
        function selectProvince(province) {
            currentProvince = province;
            currentLevel = 'districts';
            document.getElementById('current-province').textContent = province.name;
            document.getElementById('breadcrumb-province').style.display = 'inline';
            document.getElementById('breadcrumb-sep-1').style.display = 'inline';
            
            fetch(`/api/map/districts/${province.id}`)
                .then(res => res.json())
                .then(data => {
                    districtsData = data;
                    displayDistricts();
                })
                .catch(err => console.error('Error loading districts:', err));
        }

        // Display districts
        function displayDistricts() {
            document.getElementById('regions-title').textContent = 'Districts';
            const list = document.getElementById('regions-list');
            list.innerHTML = '';
            
            clearMap();
            
            districtsData.forEach(district => {
                const btn = document.createElement('button');
                btn.className = 'region-btn';
                btn.innerHTML = `${district.name}<br/><span class="region-count">${district.incident_count} incidents</span>`;
                btn.onclick = () => selectDistrict(district);
                list.appendChild(btn);
                
                // Plot district markers on map
                if (district.latitude && district.longitude) {
                    const color = district.incident_count > 20 ? '#dc2626' : district.incident_count > 10 ? '#f59e0b' : '#079b6d';
                    const size = Math.min(25, district.incident_count / 2 + 6);
                    
                    const circle = L.circleMarker([district.latitude, district.longitude], {
                        radius: size,
                        fillColor: color,
                        color: color,
                        weight: 2,
                        opacity: 0.8,
                        fillOpacity: 0.6
                    }).addTo(map);

                    circle.bindPopup(`
                        <strong>${district.name}</strong><br/>
                        <small>Incidents: ${district.incident_count}</small>
                    `);
                    
                    markers.push(circle);
                }
            });

            document.getElementById('total-incidents').textContent = currentProvince.incident_count;
            document.getElementById('total-facilities').textContent = currentProvince.facility_count;
        }

        // Select district and load facilities
        function selectDistrict(district) {
            currentDistrict = district;
            currentLevel = 'facilities';
            document.getElementById('current-district').textContent = district.name;
            document.getElementById('breadcrumb-district').style.display = 'inline';
            document.getElementById('breadcrumb-sep-2').style.display = 'inline';
            
            fetch(`/api/map/facilities/${district.id}`)
                .then(res => res.json())
                .then(data => {
                    facilitiesData = data;
                    displayFacilities();
                })
                .catch(err => console.error('Error loading facilities:', err));
        }

        // Display facilities
        function displayFacilities() {
            document.getElementById('regions-title').textContent = 'Facilities';
            const list = document.getElementById('regions-list');
            list.innerHTML = '';
            
            clearMap();
            
            facilitiesData.forEach(facility => {
                const btn = document.createElement('button');
                btn.className = 'region-btn';
                btn.innerHTML = `${facility.name}<br/><span class="region-count">${facility.incident_count} incidents</span>`;
                btn.onclick = () => viewFacilityIncidents(facility);
                list.appendChild(btn);
                
                // Plot facility markers on map
                if (facility.latitude && facility.longitude) {
                    const color = facility.incident_count > 10 ? '#dc2626' : facility.incident_count > 5 ? '#f59e0b' : '#079b6d';
                    const size = Math.min(20, facility.incident_count / 1.5 + 5);
                    
                    const circle = L.circleMarker([facility.latitude, facility.longitude], {
                        radius: size,
                        fillColor: color,
                        color: color,
                        weight: 2,
                        opacity: 0.8,
                        fillOpacity: 0.7
                    }).addTo(map);

                    circle.bindPopup(`
                        <strong>${facility.name}</strong><br/>
                        <small>Type: ${facility.facility_type}</small><br/>
                        <small>Incidents: ${facility.incident_count}</small>
                    `);
                    
                    markers.push(circle);
                }
            });

            document.getElementById('total-incidents').textContent = currentDistrict.incident_count;
            document.getElementById('total-facilities').textContent = facilitiesData.length;
        }

        // View incidents for facility
        function viewFacilityIncidents(facility) {
            // Center map on facility
            map.setView([facility.latitude, facility.longitude], 11);
        }

        // Clear map markers
        function clearMap() {
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];
        }

        // Navigation functions
        function backToProvinces() {
            currentProvince = null;
            currentDistrict = null;
            document.getElementById('breadcrumb-province').style.display = 'none';
            document.getElementById('breadcrumb-district').style.display = 'none';
            document.getElementById('breadcrumb-sep-1').style.display = 'none';
            document.getElementById('breadcrumb-sep-2').style.display = 'none';
            displayProvinces();
        }

        function backToDistricts() {
            currentDistrict = null;
            document.getElementById('breadcrumb-district').style.display = 'none';
            document.getElementById('breadcrumb-sep-2').style.display = 'none';
            displayDistricts();
        }

        function resetMap() {
            currentProvince = null;
            currentDistrict = null;
            currentLevel = 'provinces';
            document.getElementById('breadcrumb-province').style.display = 'none';
            document.getElementById('breadcrumb-district').style.display = 'none';
            document.getElementById('breadcrumb-sep-1').style.display = 'none';
            document.getElementById('breadcrumb-sep-2').style.display = 'none';
            displayProvinces();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Give Leaflet time to load
            setTimeout(initMap, 100);
        });
        
        // Fallback for older browsers
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(initMap, 100);
            });
        } else {
            setTimeout(initMap, 100);
        }
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\incident_hotspot_mapping.blade.php ENDPATH**/ ?>