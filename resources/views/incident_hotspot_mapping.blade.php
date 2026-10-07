<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Incident Hotspot Mapping · Zimbabwe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.0/apexcharts.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: 'Tahoma', sans-serif; background: #f5f7fa; }
        .container { max-width: 1600px; margin: 0 auto; padding: 1.5rem; }
        .header { margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 28px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.75rem; }
        .header p { font-size: 14px; color: #64748b; margin-top: 0.5rem; }
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
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 12px;
            color: #64748b;
        }
        .breadcrumb span { cursor: pointer; color: #1D4533; font-weight: 600; }
        .breadcrumb span:hover { text-decoration: underline; }
        .map-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        #map { height: 600px; width: 100%; }
        .info-panel {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .stat-card {
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }
        .stat-card p { font-size: 12px; opacity: 0.9; margin-bottom: 0.5rem; }
        .stat-card strong { font-size: 24px; font-weight: 700; }
        .regions-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
        }
        .region-btn {
            padding: 0.75rem;
            background: #f0f4f2;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            color: #1D4533;
            transition: all 0.2s;
            text-align: center;
        }
        .region-btn:hover {
            background: #1D4533;
            color: white;
            border-color: #1D4533;
        }
        .region-btn.active {
            background: #1D4533;
            color: white;
            border-color: #1D4533;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fa-solid fa-map-location-dot"></i> Incident Hotspot Mapping</h1>
                <p>Interactive Zimbabwe map showing incident distribution across provinces, districts, and facilities</p>
            </div>
            <a href="{{ route('dashboards.super_admin') }}" class="back-button">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>

        <div class="breadcrumb">
            <span id="breadcrumb-home" onclick="resetMap()">Zimbabwe</span>
            <span id="breadcrumb-province" style="display: none;">
                <i class="fa-solid fa-chevron-right"></i>
                <span id="current-province" onclick="backToProvinces()"></span>
            </span>
            <span id="breadcrumb-district" style="display: none;">
                <i class="fa-solid fa-chevron-right"></i>
                <span id="current-district" onclick="backToDistricts()"></span>
            </span>
            <span id="breadcrumb-facility" style="display: none;">
                <i class="fa-solid fa-chevron-right"></i>
                <span id="current-facility"></span>
            </span>
        </div>

        <div class="map-container">
            <div id="map"></div>
        </div>

        <div class="info-panel">
            <div class="info-grid">
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

            <div id="regions-container" style="display: none;">
                <h3 style="margin-bottom: 1rem; color: #1D4533; font-size: 14px; font-weight: 700;">
                    <span id="regions-title">Provinces</span>
                </h3>
                <div class="regions-list" id="regions-list"></div>
            </div>
        </div>
    </div>

    <script>
        let map;
        let currentLevel = 'provinces'; // provinces, districts, facilities, incidents
        let currentProvince = null;
        let currentDistrict = null;
        let zimbabweCenter = [-19.0154, 29.1549];
        let provincesData = [];
        let districtsData = [];
        let facilitiesData = [];

        // Initialize map
        function initMap() {
            // Zimbabwe bounds: roughly [South, West] to [North, East]
            const zimbabweBounds = [[-22.5, 25.2], [-15.6, 32.9]];
            
            map = L.map('map', {
                maxBounds: zimbabweBounds,
                maxBoundsViscosity: 1.0
            }).setView(zimbabweCenter, 7);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            loadProvinces();
        }

        // Load provinces from backend
        function loadProvinces() {
            fetch('/api/map/provinces')
                .then(res => res.json())
                .then(data => {
                    provincesData = data;
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
            
            provincesData.forEach(province => {
                const btn = document.createElement('button');
                btn.className = 'region-btn';
                btn.textContent = province.name + ' (' + province.incident_count + ')';
                btn.onclick = () => selectProvince(province);
                list.appendChild(btn);
            });

            map.setView(zimbabweCenter, 7);
            map.eachLayer(layer => {
                if (layer instanceof L.CircleMarker || layer instanceof L.Polygon) {
                    map.removeLayer(layer);
                }
            });
        }

        // Select province and load districts
        function selectProvince(province) {
            currentProvince = province;
            currentLevel = 'districts';
            document.getElementById('current-province').textContent = province.name;
            document.getElementById('breadcrumb-province').style.display = 'inline';
            
            fetch(`/api/map/districts/${province.id}`)
                .then(res => res.json())
                .then(data => {
                    districtsData = data;
                    displayDistricts();
                });
        }

        // Display districts
        function displayDistricts() {
            document.getElementById('regions-title').textContent = 'Districts';
            const list = document.getElementById('regions-list');
            list.innerHTML = '';
            
            districtsData.forEach(district => {
                const btn = document.createElement('button');
                btn.className = 'region-btn';
                btn.textContent = district.name + ' (' + district.incident_count + ')';
                btn.onclick = () => selectDistrict(district);
                list.appendChild(btn);
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
            
            fetch(`/api/map/facilities/${district.id}`)
                .then(res => res.json())
                .then(data => {
                    facilitiesData = data;
                    displayFacilities();
                });
        }

        // Display facilities
        function displayFacilities() {
            document.getElementById('regions-title').textContent = 'Facility Types';
            const list = document.getElementById('regions-list');
            list.innerHTML = '';
            
            // Group by facility type
            const grouped = {};
            facilitiesData.forEach(facility => {
                const type = facility.facility_type || 'Unknown';
                if (!grouped[type]) grouped[type] = [];
                grouped[type].push(facility);
            });
            
            Object.keys(grouped).forEach(type => {
                const btn = document.createElement('button');
                btn.className = 'region-btn';
                btn.textContent = type + ' (' + grouped[type].length + ')';
                btn.onclick = () => displayIncidents(grouped[type], type);
                list.appendChild(btn);
            });

            document.getElementById('total-incidents').textContent = currentDistrict.incident_count;
            document.getElementById('total-facilities').textContent = facilitiesData.length;
        }

        // Display incidents for selected facilities
        function displayIncidents(facilities, facilityType) {
            currentLevel = 'incidents';
            document.getElementById('current-facility').textContent = facilityType;
            document.getElementById('breadcrumb-facility').style.display = 'inline';
            
            document.getElementById('regions-container').style.display = 'none';
            
            map.eachLayer(layer => {
                if (layer instanceof L.CircleMarker) {
                    map.removeLayer(layer);
                }
            });

            let totalIncidents = 0;
            facilities.forEach(facility => {
                const circle = L.circleMarker([facility.latitude, facility.longitude], {
                    radius: Math.min(20, facility.incident_count / 2 + 5),
                    fillColor: '#1D4533',
                    color: '#0f2818',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.7
                }).addTo(map);

                circle.bindPopup(`
                    <strong>${facility.name}</strong><br/>
                    Type: ${facility.facility_type}<br/>
                    Incidents: ${facility.incident_count}
                `);

                totalIncidents += facility.incident_count;
            });

            document.getElementById('total-incidents').textContent = totalIncidents;
            document.getElementById('total-facilities').textContent = facilities.length;
        }

        // Navigation functions
        function backToProvinces() {
            currentProvince = null;
            currentDistrict = null;
            document.getElementById('breadcrumb-province').style.display = 'none';
            document.getElementById('breadcrumb-district').style.display = 'none';
            document.getElementById('breadcrumb-facility').style.display = 'none';
            displayProvinces();
        }

        function backToDistricts() {
            currentDistrict = null;
            document.getElementById('breadcrumb-district').style.display = 'none';
            document.getElementById('breadcrumb-facility').style.display = 'none';
            displayDistricts();
        }

        function resetMap() {
            currentProvince = null;
            currentDistrict = null;
            currentLevel = 'provinces';
            document.getElementById('breadcrumb-province').style.display = 'none';
            document.getElementById('breadcrumb-district').style.display = 'none';
            document.getElementById('breadcrumb-facility').style.display = 'none';
            displayProvinces();
        }

        // Initialize on page load
        window.addEventListener('load', initMap);
    </script>
</body>
</html>
