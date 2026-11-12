<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Hotspot Mapping - Zimbabwe</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            color: #263149;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            flex: 0 0 50px;
            background: linear-gradient(135deg, #079b6d, #067c58);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 23px;
        }

        .header-title h1 {
            font-size: 26px;
            font-weight: 800;
            color: #101a31;
            margin-bottom: 5px;
        }

        .header-title p { font-size: 13px; color: #718096; }

        .back-btn {
            padding: 12px 18px;
            background: white;
            border: 1px solid #e1e7ee;
            border-radius: 10px;
            color: #263149;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: .2s ease;
            white-space: nowrap;
        }

        .back-btn:hover {
            background: #f8fafb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }

        .map-wrapper {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,.08);
            margin-bottom: 24px;
            position: relative;
            border: 1px solid #e9eef3;
        }

        #map {
            width: 100%;
            height: 650px;
            background: #e8eef2;
            z-index: 1;
        }

        .search-box {
            position: absolute;
            top: 18px;
            left: 18px;
            z-index: 500;
            width: min(360px, calc(100% - 36px));
            background: white;
            padding: 10px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,.16);
        }

        .search-input-wrap { position: relative; }

        .search-input-wrap i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #8793a3;
            font-size: 14px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 13px 12px 37px;
            border: 1px solid #e1e7ee;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            outline: none;
        }

        .search-box input:focus { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7,155,109,.10); }

        .search-results {
            display: none;
            max-height: 240px;
            overflow-y: auto;
            margin-top: 7px;
            border-top: 1px solid #edf0f4;
        }

        .search-results.show { display: block; }

        .search-result {
            display: block;
            width: 100%;
            padding: 10px 8px;
            border: 0;
            border-bottom: 1px solid #f0f2f5;
            background: white;
            text-align: left;
            cursor: pointer;
            color: #263149;
            font: inherit;
            font-size: 12px;
            line-height: 1.45;
        }

        .search-result:hover { background: #f2faf7; }
        .search-result small { display: block; color: #8793a3; margin-top: 2px; }

        .map-legend {
            position: absolute;
            bottom: 22px;
            right: 18px;
            z-index: 500;
            background: rgba(255,255,255,.97);
            padding: 15px 16px 10px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
            min-width: 185px;
        }

        .legend-title {
            font-size: 12px;
            font-weight: 800;
            color: #101a31;
            margin-bottom: 11px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 8px;
            font-size: 11px;
            color: #526071;
        }

        .legend-dot {
            width: 13px;
            height: 13px;
            flex: 0 0 13px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 1px 4px rgba(0,0,0,.22);
        }

        .map-note {
            position: absolute;
            left: 18px;
            bottom: 18px;
            z-index: 400;
            background: rgba(255,255,255,.94);
            border: 1px solid #e8edf1;
            color: #637083;
            padding: 8px 10px;
            border-radius: 8px;
            font-size: 10px;
            max-width: 280px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .stat-card {
            background: linear-gradient(135deg, #079b6d 0%, #067c58 100%);
            padding: 22px;
            border-radius: 14px;
            color: white;
            text-align: center;
            box-shadow: 0 8px 24px rgba(7,155,109,.16);
        }

        .stat-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: .9;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stat-value { font-size: 36px; font-weight: 800; }

        .leaflet-popup-content-wrapper { border-radius: 10px; }
        .province-popup { min-width: 170px; font-family: Inter, sans-serif; }
        .province-popup h3 { color: #101a31; font-size: 14px; margin-bottom: 9px; }
        .province-popup p { color: #647184; font-size: 12px; margin: 5px 0; }
        .province-popup strong { color: #263149; }
        .popup-tag {
            display: inline-block;
            margin-top: 7px;
            padding: 4px 7px;
            border-radius: 5px;
            background: #eef8f3;
            color: #067c58;
            font-size: 10px;
            font-weight: 700;
        }

        @media (max-width: 900px) {
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 600px) {
            .container { padding: 20px 12px; }
            .header { align-items: stretch; flex-direction: column; }
            .header-title h1 { font-size: 21px; }
            .back-btn { justify-content: center; }
            #map { height: 520px; }
            .search-box { top: 12px; left: 12px; width: calc(100% - 24px); }
            .map-legend { right: 10px; bottom: 10px; padding: 11px; min-width: 160px; }
            .map-note { left: 10px; bottom: 10px; max-width: 155px; font-size: 9px; }
            .stats { gap: 10px; }
            .stat-card { padding: 17px 10px; }
            .stat-value { font-size: 29px; }
            .stat-label { font-size: 9px; }
        }
    </style>
</head>
<body>
<div class="container">
    <header class="header">
        <div class="header-left">
            <div class="header-icon"><i class="fa-solid fa-map-location-dot"></i></div>
            <div class="header-title">
                <h1>Incident Hotspot Mapping</h1>
                <p>Interactive Zimbabwe map showing incident distribution</p>
            </div>
        </div>
        <a href="{{ route('dashboards.super_admin') }}" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
    </header>

    <section class="map-wrapper" aria-label="Zimbabwe incident hotspot map">
        <div id="map"></div>

        <div class="search-box">
            <div class="search-input-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" id="searchInput"
                       placeholder="Search a town, city or location..."
                       autocomplete="off" aria-label="Search a location in Zimbabwe">
            </div>
            <div id="searchResults" class="search-results"></div>
        </div>

        <div class="map-legend">
            <div class="legend-title"><i class="fa-solid fa-circle-info"></i> Incident Levels</div>
            <div class="legend-item"><span class="legend-dot" style="background:#dc2626"></span> High (35+ incidents)</div>
            <div class="legend-item"><span class="legend-dot" style="background:#f59e0b"></span> Medium (21–35)</div>
            <div class="legend-item"><span class="legend-dot" style="background:#10b981"></span> Moderate (11–20)</div>
            <div class="legend-item"><span class="legend-dot" style="background:#079b6d"></span> Low (0–10)</div>
        </div>

        <div class="map-note">
            <i class="fa-solid fa-circle-info"></i>
            Select a province marker to view its summary. Map data is illustrative until connected to the system database.
        </div>
    </section>

    <!-- These figures are retained from the supplied page and should be replaced
         with Laravel database values before being used as official statistics. -->
    <section class="stats">
        <div class="stat-card">
            <div class="stat-label">Total Incidents</div>
            <div class="stat-value">206</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Provinces</div>
            <div class="stat-value">10</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Facilities</div>
            <div class="stat-value">48</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Active Cases</div>
            <div class="stat-value">89</div>
        </div>
    </section>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Illustrative values carried over from the supplied page.
    // Replace these with values returned by your Laravel controller.
    const provinces = [
        { name: 'Harare', lat: -17.8252, lng: 31.0335, incidents: 45, facilities: 12 },
        { name: 'Bulawayo', lat: -20.1539, lng: 28.5830, incidents: 32, facilities: 8 },
        { name: 'Mashonaland Central', lat: -16.7833, lng: 31.1167, incidents: 18, facilities: 5 },
        { name: 'Mashonaland East', lat: -18.0833, lng: 31.5833, incidents: 24, facilities: 6 },
        { name: 'Mashonaland West', lat: -17.4333, lng: 29.8167, incidents: 15, facilities: 4 },
        { name: 'Midlands', lat: -19.4500, lng: 29.8167, incidents: 28, facilities: 7 },
        { name: 'Masvingo', lat: -20.0637, lng: 30.8275, incidents: 22, facilities: 5 },
        { name: 'Matabeleland North', lat: -18.5000, lng: 27.5000, incidents: 12, facilities: 3 },
        { name: 'Matabeleland South', lat: -21.0833, lng: 29.0000, incidents: 10, facilities: 2 },
        { name: 'Manicaland', lat: -18.9667, lng: 32.6667, incidents: 20, facilities: 6 }
    ];

    const zimbabweBounds = L.latLngBounds(
        L.latLng(-22.5, 25.2),
        L.latLng(-15.5, 33.1)
    );

    const map = L.map('map', {
        zoomControl: true,
        minZoom: 6,
        maxZoom: 17,
        maxBounds: zimbabweBounds,
        maxBoundsViscosity: 0.85
    });

    // OpenStreetMap tiles provide the actual map background without a Google API key.
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
    }).addTo(map);

    map.fitBounds(zimbabweBounds, { padding: [18, 18] });

    function getColor(incidents) {
        if (incidents > 35) return '#dc2626';
        if (incidents > 20) return '#f59e0b';
        if (incidents > 10) return '#10b981';
        return '#079b6d';
    }

    const provinceMarkers = [];

    provinces.forEach(province => {
        const color = getColor(province.incidents);
        const radius = Math.max(9, Math.min(22, 7 + province.incidents * 0.28));

        const marker = L.circleMarker([province.lat, province.lng], {
            radius,
            color: '#ffffff',
            weight: 2,
            opacity: 1,
            fillColor: color,
            fillOpacity: 0.88
        }).addTo(map);

        marker.bindTooltip(
            `<strong>${province.name}</strong><br>${province.incidents} incidents`,
            { direction: 'top', offset: [0, -7] }
        );

        marker.bindPopup(`
            <div class="province-popup">
                <h3>${province.name}</h3>
                <p><i class="fa-solid fa-triangle-exclamation"></i>
                   <strong>${province.incidents}</strong> incidents</p>
                <p><i class="fa-solid fa-hospital"></i>
                   <strong>${province.facilities}</strong> facilities</p>
                <span class="popup-tag">${province.incidents > 35 ? 'High incident level' :
                    province.incidents > 20 ? 'Medium incident level' :
                    province.incidents > 10 ? 'Moderate incident level' : 'Low incident level'}</span>
            </div>
        `);

        provinceMarkers.push({ province, marker });
    });

    // Location search uses the Nominatim OpenStreetMap geocoding service.
    // Requests are debounced and restricted to Zimbabwe.
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    let searchTimer = null;
    let activeSearchMarker = null;
    let lastQuery = '';

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        const query = searchInput.value.trim();

        if (query.length < 3) {
            searchResults.classList.remove('show');
            searchResults.innerHTML = '';
            return;
        }

        searchTimer = setTimeout(() => searchLocations(query), 650);
    });

    async function searchLocations(query) {
        if (query === lastQuery) return;
        lastQuery = query;

        searchResults.innerHTML = '<div class="search-result">Searching locations...</div>';
        searchResults.classList.add('show');

        try {
            const url = new URL('https://nominatim.openstreetmap.org/search');
            url.search = new URLSearchParams({
                q: query + ', Zimbabwe',
                countrycodes: 'zw',
                format: 'jsonv2',
                limit: '6',
                addressdetails: '1'
            });

            const response = await fetch(url.toString(), {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error('Location search is temporarily unavailable.');

            const results = await response.json();
            if (!results.length) {
                searchResults.innerHTML = '<div class="search-result">No matching Zimbabwe location found.</div>';
                return;
            }

            searchResults.innerHTML = '';
            results.forEach(result => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'search-result';

                const mainName = (result.name || result.display_name.split(',')[0]);
                const detail = result.display_name;

                const title = document.createElement('span');
                title.textContent = mainName;
                const subtitle = document.createElement('small');
                subtitle.textContent = detail;

                button.append(title, subtitle);
                button.addEventListener('click', () => {
                    const lat = Number(result.lat);
                    const lng = Number(result.lon);
                    map.setView([lat, lng], 14);

                    if (activeSearchMarker) map.removeLayer(activeSearchMarker);
                    activeSearchMarker = L.marker([lat, lng]).addTo(map)
                        .bindPopup(`<strong>${escapeHtml(mainName)}</strong><br>${escapeHtml(detail)}`)
                        .openPopup();

                    searchInput.value = mainName;
                    searchResults.classList.remove('show');
                    searchResults.innerHTML = '';
                });
                searchResults.appendChild(button);
            });
        } catch (error) {
            searchResults.innerHTML = '<div class="search-result">Unable to search right now. Please try again shortly.</div>';
            console.error(error);
        }
    }

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, character => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        })[character]);
    }

    document.addEventListener('click', event => {
        if (!event.target.closest('.search-box')) {
            searchResults.classList.remove('show');
        }
    });

    // Ensure Leaflet recalculates its dimensions after the page has rendered.
    window.addEventListener('load', () => {
        setTimeout(() => {
            map.invalidateSize();
            map.fitBounds(zimbabweBounds, { padding: [18, 18] });
        }, 150);
    });
</script>
</body>
</html>
