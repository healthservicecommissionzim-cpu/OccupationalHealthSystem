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
            overflow: hidden;
        }

        .page {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header - Compact for full-page map */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px;
            background: white;
            border-bottom: 1px solid #e1e7ee;
            box-shadow: 0 2px 8px rgba(16, 26, 49, 0.06);
            flex-shrink: 0;
        }

        .head {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #e9f8f3;
            color: #079b6d;
            display: grid;
            place-items: center;
            font-size: 18px;
        }

        .title {
            margin: 0;
            color: #101a31;
            font-size: 18px;
            font-weight: 750;
        }

        .sub {
            margin-top: 2px;
            color: #718096;
            font-size: 11px;
        }

        .case {
            display: inline-block;
            margin-left: 15px;
            padding: 4px 8px;
            border-radius: 6px;
            background: #eef2f6;
            color: #5d687b;
            font-size: 10px;
            font-weight: 700;
        }

        .back {
            padding: 8px 15px;
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

        /* Map Container - Full page */
        .map-container {
            flex: 1;
            width: 100%;
            position: relative;
            overflow: hidden;
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
            .header {
                padding: 12px 15px;
            }

            .head {
                gap: 10px;
            }

            .title {
                font-size: 16px;
            }

            .sub {
                font-size: 10px;
            }

            .case {
                display: block;
                margin-left: 0;
                margin-top: 6px;
            }

            .back {
                padding: 6px 12px;
                font-size: 11px;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .back {
                width: 100%;
                justify-content: center;
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
                </div>
                <span class="case">{{ count($incidents ?? []) }} incidents</span>
            </div>
            <a href="{{ route('dashboard') }}" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <!-- Full Page Map -->
        <div class="map-container">
            <div id="map"></div>
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

        // Zimbabwe boundaries (accurate geographical coordinates)
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

            // Create bounds for Zimbabwe
            const bounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(zimbabweBounds.south, zimbabweBounds.west), // Southwest
                new google.maps.LatLng(zimbabweBounds.north, zimbabweBounds.east)  // Northeast
            );

            // Initialize map with Zimbabwe bounds
            map = new Map(document.getElementById('map'), {
                mapId: 'HSC_OHS_ZIMBABWE_MAP', // Required for Advanced Markers
                restriction: {
                    latLngBounds: bounds,
                    strictBounds: true
                },
                mapTypeControl: true,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
                gestureHandling: 'greedy',
            });

            // Function to calculate responsive padding based on container size
            function getResponsivePadding() {
                const container = document.getElementById('map');
                const width = container.offsetWidth;
                const height = container.offsetHeight;
                
                // Calculate padding as percentage of container size
                // Larger containers need more absolute padding
                // Smaller containers need proportional padding
                const padding = {
                    top: Math.max(40, height * 0.08),
                    right: Math.max(40, width * 0.05),
                    bottom: Math.max(40, height * 0.08),
                    left: Math.max(40, width * 0.05)
                };
                
                return padding;
            }

            // Function to fit map to Zimbabwe bounds with responsive padding
            function fitMapToZimbabwe() {
                const padding = getResponsivePadding();
                map.fitBounds(bounds, padding);
            }

            // Initial fit to Zimbabwe bounds
            fitMapToZimbabwe();

            // Re-fit after map fully loads (ensures perfect initial display)
            google.maps.event.addListenerOnce(map, 'idle', function() {
                fitMapToZimbabwe();
            });

            // Re-fit on window resize to maintain proper bounds
            let resizeTimer;
            window.addEventListener('resize', function() {
                // Debounce resize events to avoid excessive re-renders
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    fitMapToZimbabwe();
                }, 250);
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
            
            // Final fit after all markers are loaded
            setTimeout(function() {
                fitMapToZimbabwe();
            }, 300);
        }

        // Load error handler
        window.gm_authFailure = function() {
            alert('Google Maps API authentication failed. Please check the API key.');
        };
    </script>
</body>
</html>
