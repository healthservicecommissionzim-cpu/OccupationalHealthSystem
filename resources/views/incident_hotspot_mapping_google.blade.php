<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Incident Hotspot Mapping · HSC-OHS</title>
    
    <!-- Favicon -->
    @if(file_exists(public_path('hsclogo.png')))
    <link rel="icon" type="image/x-icon" href="{{ asset('hsclogo.png') }}">
    @endif
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Preconnect to Google APIs for better performance -->
    <link rel="preconnect" href="https://maps.googleapis.com">
    <link rel="preconnect" href="https://maps.gstatic.com" crossorigin>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f7fa;
            color: #263149;
            font: 14px Inter, 'Segoe UI', Arial, sans-serif;
        }

        .page {
            max-width: 1400px;
            margin: auto;
            padding: 35px 25px 70px;
        }
        
        /* Header - Matching facilities page */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
        }

        .head {
            display: flex;
            gap: 14px;
        }

        .icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #e9f8f3;
            color: #079b6d;
            display: grid;
            place-items: center;
            font-size: 22px;
        }

        .title {
            margin: 0;
            color: #101a31;
            font-size: 24px;
            font-weight: 750;
        }

        .sub {
            margin-top: 5px;
            color: #718096;
            font-size: 13px;
        }

        .case {
            display: inline-block;
            margin-top: 9px;
            padding: 5px 10px;
            border-radius: 6px;
            background: #eef2f6;
            color: #5d687b;
            font-size: 11px;
            font-weight: 700;
        }

        .back {
            padding: 10px 17px;
            border: 1px solid #ccd6df;
            border-radius: 8px;
            background: white;
            color: #101a31;
            font-weight: 650;
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back:hover {
            background: #f8fafb;
        }

        /* Stats - Matching facilities page */
        .stats-inline {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 22px;
        }

        .stat-inline {
            background: #f8fafb;
            border: 1px solid #e4e9ee;
            border-radius: 10px;
            padding: 18px;
            text-align: center;
            transition: all 0.2s;
            border-top: 3px solid #079b6d;
        }

        .stat-inline:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 26, 49, 0.08);
        }

        .stat-inline p {
            font-size: 10px;
            color: #8a95a5;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-inline strong {
            font-size: 24px;
            font-weight: 750;
            color: #101a31;
            display: block;
        }

        /* Card - Matching facilities page */
        .card {
            background: #fff;
            border: 1px solid #e1e7ee;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(16, 26, 49, 0.06);
            overflow: hidden;
            margin-bottom: 22px;
        }

        .map-container {
            height: 700px;
            position: relative;
            width: 100%;
        }

        #map {
            width: 100%;
            height: 100%;
        }
        
        /* Ensure Google Maps controls don't overflow */
        #map * {
            box-sizing: border-box;
        }

        /* Info popup styling */
        .info-popup {
            min-width: 250px;
            font-family: Inter, 'Segoe UI', Arial, sans-serif;
        }

        .info-popup h3 {
            color: #079b6d;
            margin-bottom: 0.75rem;
            font-size: 14px;
            font-weight: 750;
        }

        .info-popup p {
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #4e596c;
            font-size: 12px;
        }

        .popup-tag {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }

        /* Google Maps style fixes */
        .gm-style {
            border-radius: 0;
        }
        
        .gm-style > div {
            border-radius: 0;
        }

        @media (max-width: 900px) {
            .stats-inline {
                grid-template-columns: repeat(2, 1fr);
            }

            .header {
                flex-direction: column;
                gap: 15px;
            }

            .back {
                width: 100%;
                text-align: center;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .page {
                padding: 22px 15px;
            }

            .title {
                font-size: 20px;
            }

            .stats-inline {
                grid-template-columns: 1fr;
            }

            .map-container {
                height: 500px;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-map-location-dot"></i></div>
                <div>
                    <h1 class="title">Incident Hotspot Mapping</h1>
                    <div class="sub">Geographic distribution of workplace incidents across Zimbabwe</div>
                    <span class="case">Showing {{ count($incidents ?? []) }} active incidents</span>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-inline">
            <div class="stat-inline">
                <p>Total Incidents</p>
                <strong>{{ number_format($provinceData->sum('incidents')) }}</strong>
            </div>
            <div class="stat-inline">
                <p>Provinces</p>
                <strong>{{ count($provinceData) }}</strong>
            </div>
            <div class="stat-inline">
                <p>Facilities</p>
                <strong>{{ number_format($provinceData->sum('facilities')) }}</strong>
            </div>
            <div class="stat-inline">
                <p>Active Cases</p>
                <strong>{{ count($incidents ?? []) }}</strong>
            </div>
        </div>

        <!-- Map Card -->
        <div class="card">
            <div class="map-container">
                <div id="map"></div>
            </div>
        </div>
    </div>

    <!-- Load Google Maps API with marker library -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa0Bfxbhl60_Xk7wEU1nfdxMGjHqKneGA&libraries=marker&callback=initMap&loading=async&v=beta" async defer></script>
    
    <script>
        // Zimbabwe incident data by province
        const provinceData = @json($provinceData ?? []);
        // Individual incidents data
        const incidentsData = @json($incidents ?? []);

        let map;
        let markers = [];
        let incidentMarkers = [];
        let showIndividualIncidents = false;

        // Zimbabwe boundaries
        const zimbabweBounds = {
            north: -15.5,
            south: -22.5,
            west: 25.2,
            east: 33.1
        };

        // This function is called by Google Maps API when loaded
        async function initMap() {
            // Request needed libraries
            const { Map } = await google.maps.importLibrary("maps");
            const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");

            // Initialize map centered on Zimbabwe
            map = new Map(document.getElementById('map'), {
                center: { lat: -19.0154, lng: 29.1549 },
                zoom: 7,
                mapId: 'HSC_OHS_ZIMBABWE_MAP', // Required for Advanced Markers
                restriction: {
                    latLngBounds: zimbabweBounds,
                    strictBounds: true
                },
                mapTypeControl: true,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
                gestureHandling: 'greedy', // Allows easier zoom with scroll
            });

            // Add markers for each province
            provinceData.forEach(province => {
                // Determine color based on incident count
                let color;
                let severity;
                if (province.incidents > 35) {
                    color = '#e74c3c';
                    severity = 'High incident level';
                } else if (province.incidents > 20) {
                    color = '#e67e22';
                    severity = 'Medium incident level';
                } else if (province.incidents > 10) {
                    color = '#f39c12';
                    severity = 'Moderate incident level';
                } else {
                    color = '#27ae60';
                    severity = 'Low incident level';
                }

                // Calculate marker size
                const scale = Math.max(1, Math.min(3, province.incidents * 0.05));

                // Create pin element with custom styling
                const pinElement = new PinElement({
                    background: color,
                    borderColor: '#ffffff',
                    glyphColor: '#ffffff',
                    scale: scale,
                });

                // Create advanced marker
                const marker = new AdvancedMarkerElement({
                    position: { lat: parseFloat(province.lat), lng: parseFloat(province.lng) },
                    map: map,
                    title: province.name,
                    content: pinElement.element,
                });

                // Info window content
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div class="info-popup">
                            <h3>${province.name}</h3>
                            <p>
                                <i class="fa-solid fa-exclamation-triangle"></i>
                                <strong>${province.incidents}</strong> incidents
                            </p>
                            <p>
                                <i class="fa-solid fa-hospital"></i>
                                <strong>${province.facilities}</strong> facilities
                            </p>
                            <span class="popup-tag" style="background: ${color}; color: white;">${severity}</span>
                        </div>
                    `
                });

                marker.addListener('gmp-click', () => {
                    infoWindow.open(map, marker);
                });

                markers.push(marker);
            });
            
            // Add individual incident markers as circles
            incidentsData.forEach(incident => {
                // Determine color based on severity
                let color;
                let severityLabel;
                switch(incident.severity) {
                    case 'FATAL':
                        color = '#8b0000'; // Dark red
                        severityLabel = 'Fatal';
                        break;
                    case 'SERIOUS':
                        color = '#e74c3c'; // Red
                        severityLabel = 'Serious';
                        break;
                    case 'MODERATE':
                        color = '#f39c12'; // Orange
                        severityLabel = 'Moderate';
                        break;
                    case 'MINOR':
                        color = '#079b6d'; // Green (matching theme)
                        severityLabel = 'Minor';
                        break;
                    default:
                        color = '#8a95a5'; // Gray
                        severityLabel = incident.severity || 'Unknown';
                }

                // Add small random offset to prevent markers stacking exactly on same spot
                const latOffset = (Math.random() - 0.5) * 0.05;
                const lngOffset = (Math.random() - 0.5) * 0.05;

                // Create circle pin element for individual incidents (small circles)
                const incidentPin = new PinElement({
                    background: color,
                    borderColor: '#ffffff',
                    glyphColor: '#ffffff',
                    scale: 0.5, // Smaller circles for incidents
                    glyph: '', // No icon, just circle
                });

                // Create marker
                const incidentMarker = new AdvancedMarkerElement({
                    position: { 
                        lat: parseFloat(incident.lat) + latOffset, 
                        lng: parseFloat(incident.lng) + lngOffset 
                    },
                    map: map,
                    title: incident.reference,
                    content: incidentPin.element,
                });

                // Info window for incident - matching facilities page style
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div class="info-popup">
                            <h3><i class="fa-solid fa-file-medical"></i> ${incident.reference}</h3>
                            <p><i class="fa-solid fa-calendar"></i> <strong>Date:</strong> ${incident.date}</p>
                            <p><i class="fa-solid fa-hospital"></i> <strong>Facility:</strong> ${incident.facility}</p>
                            <p><i class="fa-solid fa-map-pin"></i> <strong>Province:</strong> ${incident.province}</p>
                            <p><i class="fa-solid fa-exclamation-circle"></i> <strong>Type:</strong> ${incident.type || 'Not specified'}</p>
                            <p><i class="fa-solid fa-heartbeat"></i> <strong>Severity:</strong> <span style="color: ${color}; font-weight: 700;">${severityLabel}</span></p>
                            <p><i class="fa-solid fa-info-circle"></i> <strong>Status:</strong> ${incident.status}</p>
                        </div>
                    `
                });

                incidentMarker.addListener('gmp-click', () => {
                    infoWindow.open(map, incidentMarker);
                });

                incidentMarkers.push(incidentMarker);
            });
            
            console.log(`Loaded ${provinceData.length} provinces and ${incidentsData.length} individual incidents`);
            
            // Ensure map fits perfectly in container after loading
            google.maps.event.addListenerOnce(map, 'idle', function() {
                google.maps.event.trigger(map, 'resize');
            });
        }

        // Load error handler
        window.gm_authFailure = function() {
            alert('Google Maps API authentication failed. Please check the API key.');
        };
    </script>
</body>
</html>
