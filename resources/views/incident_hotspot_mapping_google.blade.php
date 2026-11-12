<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Hotspot Mapping - Zimbabwe</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

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
            margin-bottom: 30px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #079b6d, #067c58);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .header-title h1 {
            font-size: 28px;
            font-weight: 800;
            color: #101a31;
            margin-bottom: 5px;
        }

        .header-title p {
            font-size: 14px;
            color: #718096;
        }

        .back-btn {
            padding: 12px 20px;
            background: white;
            border: 1px solid #e1e7ee;
            border-radius: 10px;
            color: #263149;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #f8fafb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .map-wrapper {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            position: relative;
        }

        #map {
            width: 100%;
            height: 650px;
        }

        .search-box {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
            background: white;
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            width: 350px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e1e7ee;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
        }

        .search-box input:focus {
            outline: none;
            border-color: #079b6d;
        }

        .map-legend {
            position: absolute;
            bottom: 20px;
            right: 20px;
            z-index: 10;
            background: white;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .legend-title {
            font-size: 13px;
            font-weight: 700;
            color: #101a31;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .legend-dot {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #079b6d 0%, #067c58 100%);
            padding: 24px;
            border-radius: 14px;
            color: white;
            text-align: center;
            box-shadow: 0 8px 24px rgba(7, 155, 109, 0.2);
        }

        .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 40px;
            font-weight: 800;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 20px;
            }

            .back-btn {
                width: 100%;
                justify-content: center;
            }

            #map {
                height: 450px;
            }

            .search-box {
                width: calc(100% - 40px);
                left: 20px;
                right: 20px;
            }

            .map-legend {
                bottom: 10px;
                right: 10px;
                font-size: 11px;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div class="header-title">
                    <h1>Incident Hotspot Mapping</h1>
                    <p>Interactive Zimbabwe map showing incident distribution</p>
                </div>
            </div>
            <a href="{{ route('dashboards.super_admin') }}" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>

        <!-- Map -->
        <div class="map-wrapper">
            <div id="map"></div>
            
            <div class="search-box">
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Search for a location in Zimbabwe..."
                    autocomplete="off"
                >
            </div>

            <div class="map-legend">
                <div class="legend-title">
                    <i class="fa-solid fa-circle-info"></i>
                    Incident Levels
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background: #dc2626;"></div>
                    <span>High (35+ incidents)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background: #f59e0b;"></div>
                    <span>Medium (20-35)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background: #10b981;"></div>
                    <span>Moderate (10-20)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background: #079b6d;"></div>
                    <span>Low (&lt;10)</span>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats">
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
        </div>
    </div>

    <script>
        // Zimbabwe provinces data
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

        let map;
        let markers = [];
        let searchMarker;

        function initMap() {
            // Zimbabwe center
            const center = { lat: -19.0154, lng: 29.1549 };

            // Create map
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 7,
                center: center,
                restriction: {
                    latLngBounds: {
                        north: -15.5,
                        south: -22.5,
                        west: 25.2,
                        east: 33.1
                    },
                    strictBounds: true
                },
                minZoom: 6,
                maxZoom: 18,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
                styles: [
                    { featureType: "all", elementType: "geometry", stylers: [{ color: "#f5f7fa" }] },
                    { featureType: "water", elementType: "geometry", stylers: [{ color: "#c9e6f2" }] },
                    { featureType: "road", elementType: "geometry", stylers: [{ color: "#ffffff" }] }
                ]
            });

            // Add province markers
            provinces.forEach(province => {
                const color = getColor(province.incidents);
                const size = Math.min(35, province.incidents / 2 + 12);

                const marker = new google.maps.Marker({
                    position: { lat: province.lat, lng: province.lng },
                    map: map,
                    title: province.name,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: size,
                        fillColor: color,
                        fillOpacity: 0.85,
                        strokeColor: '#ffffff',
                        strokeWeight: 3
                    },
                    label: {
                        text: String(province.incidents),
                        color: '#ffffff',
                        fontSize: '13px',
                        fontWeight: 'bold'
                    }
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 12px; font-family: Inter, sans-serif;">
                            <h3 style="margin: 0 0 10px 0; color: #101a31; font-size: 17px; font-weight: 700;">
                                ${province.name}
                            </h3>
                            <p style="margin: 5px 0; color: #718096; font-size: 14px;">
                                <i class="fa-solid fa-triangle-exclamation" style="color: #079b6d;"></i>
                                <strong>${province.incidents}</strong> incidents
                            </p>
                            <p style="margin: 5px 0; color: #718096; font-size: 14px;">
                                <i class="fa-solid fa-hospital" style="color: #079b6d;"></i>
                                <strong>${province.facilities}</strong> facilities
                            </p>
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });

                markers.push(marker);
            });

            // Setup autocomplete
            const input = document.getElementById('searchInput');
            const autocomplete = new google.maps.places.Autocomplete(input, {
                componentRestrictions: { country: 'zw' },
                fields: ['geometry', 'name', 'formatted_address']
            });

            autocomplete.bindTo('bounds', map);

            searchMarker = new google.maps.Marker({
                map: map,
                visible: false
            });

            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();

                if (!place.geometry) {
                    alert('No location found for: ' + place.name);
                    return;
                }

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(15);
                }

                searchMarker.setPosition(place.geometry.location);
                searchMarker.setVisible(true);

                const searchInfo = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 10px; font-family: Inter, sans-serif;">
                            <h4 style="margin: 0 0 8px 0; color: #101a31; font-size: 16px;">
                                <i class="fa-solid fa-location-dot" style="color: #079b6d;"></i>
                                ${place.name}
                            </h4>
                            <p style="margin: 0; color: #718096; font-size: 13px;">
                                ${place.formatted_address || 'Zimbabwe'}
                            </p>
                        </div>
                    `
                });

                searchInfo.open(map, searchMarker);
            });
        }

        function getColor(incidents) {
            if (incidents > 35) return '#dc2626';
            if (incidents > 20) return '#f59e0b';
            if (incidents > 10) return '#10b981';
            return '#079b6d';
        }
    </script>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa0Bfxbhl60_Xk7wEU1nfdxMGjHqKneGA&libraries=places&callback=initMap" async defer></script>
</body>
</html>
