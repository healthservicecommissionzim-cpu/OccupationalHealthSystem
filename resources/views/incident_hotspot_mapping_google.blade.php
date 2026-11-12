<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Incident Hotspot Mapping - HSC OHS</title>
    
    <!-- Favicon -->
    @if(file_exists(public_path('hsclogo.png')))
    <link rel="icon" type="image/x-icon" href="{{ asset('hsclogo.png') }}">
    @endif
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            color: #2c3e50;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .map-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: 700px;
            position: relative;
            width: 100%;
        }

        #map {
            width: 100%;
            height: 100%;
            border-radius: 12px;
        }
        
        /* Ensure Google Maps controls don't overflow */
        #map * {
            box-sizing: border-box;
        }

        .legend {
            background: white;
            padding: 1.25rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 1.5rem;
        }

        .legend h3 {
            margin-bottom: 1rem;
            color: #2c3e50;
            font-size: 1.125rem;
        }

        .legend-items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid rgba(0,0,0,0.1);
        }

        .info-popup {
            min-width: 250px;
        }

        .info-popup h3 {
            color: #667eea;
            margin-bottom: 0.75rem;
            font-size: 1.125rem;
        }

        .info-popup p {
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #555;
        }

        .popup-tag {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .container {
                padding: 0 1rem;
                margin: 1rem auto;
            }

            .map-container {
                height: 500px;
                max-width: 100%;
            }
            
            #map {
                border-radius: 8px;
            }

            .legend-items {
                grid-template-columns: 1fr;
            }
        }
        
        /* Fix for Google Maps overflow */
        .gm-style {
            border-radius: 12px;
        }
        
        .gm-style > div {
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>
                <i class="fa-solid fa-map-location-dot"></i>
                Incident Hotspot Mapping
            </h1>
            <a href="{{ route('dashboard') }}" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </header>

    <div class="container">
        <div class="map-container">
            <div id="map"></div>
        </div>

        <div class="legend">
            <h3><i class="fa-solid fa-circle-info"></i> Incident Severity Levels</h3>
            <div class="legend-items">
                <div class="legend-item">
                    <div class="legend-color" style="background: #e74c3c;"></div>
                    <span>High (>35 incidents)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #e67e22;"></div>
                    <span>Medium (21-35 incidents)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #f39c12;"></div>
                    <span>Moderate (11-20 incidents)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #27ae60;"></div>
                    <span>Low (≤10 incidents)</span>
                </div>
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
            
            // Add individual incident markers
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
                        color = '#3498db'; // Blue
                        severityLabel = 'Minor';
                        break;
                    default:
                        color = '#95a5a6'; // Gray
                        severityLabel = incident.severity || 'Unknown';
                }

                // Add small random offset to prevent markers stacking exactly on same spot
                const latOffset = (Math.random() - 0.5) * 0.05;
                const lngOffset = (Math.random() - 0.5) * 0.05;

                // Create small pin for individual incidents
                const incidentPin = new PinElement({
                    background: color,
                    borderColor: '#ffffff',
                    glyphColor: '#ffffff',
                    scale: 0.7,
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

                // Info window for incident
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div class="info-popup">
                            <h3 style="color: ${color};">${incident.reference}</h3>
                            <p><i class="fa-solid fa-calendar"></i> <strong>Date:</strong> ${incident.date}</p>
                            <p><i class="fa-solid fa-hospital"></i> <strong>Facility:</strong> ${incident.facility}</p>
                            <p><i class="fa-solid fa-map-pin"></i> <strong>Province:</strong> ${incident.province}</p>
                            <p><i class="fa-solid fa-exclamation-circle"></i> <strong>Type:</strong> ${incident.type || 'Not specified'}</p>
                            <p><i class="fa-solid fa-heartbeat"></i> <strong>Severity:</strong> <span style="color: ${color}; font-weight: bold;">${severityLabel}</span></p>
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
