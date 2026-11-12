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

        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }

        .map-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: 700px;
        }

        #map {
            width: 100%;
            height: 100%;
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
            }

            .legend-items {
                grid-template-columns: 1fr;
            }
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
        <div class="search-box">
            <i class="fa-solid fa-search search-icon"></i>
            <input 
                type="text" 
                id="searchInput" 
                class="search-input" 
                placeholder="Search for a facility or location in Zimbabwe..."
                autocomplete="off"
            >
        </div>

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

    <!-- Google Maps API with callback -->
    <script>
        // Zimbabwe incident data by province
        const provinceData = @json($provinceData ?? []);

        let map;
        let markers = [];
        let autocomplete;
        let searchMarker = null;

        // Zimbabwe boundaries
        const zimbabweBounds = {
            north: -15.5,
            south: -22.5,
            west: 25.2,
            east: 33.1
        };

        // This function is called by Google Maps API when loaded
        function initMap() {
            // Initialize map centered on Zimbabwe
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: -19.0154, lng: 29.1549 },
                zoom: 7,
                minZoom: 6,
                maxZoom: 18,
                restriction: {
                    latLngBounds: zimbabweBounds,
                    strictBounds: true
                },
                mapTypeControl: true,
                streetViewControl: false,
                fullscreenControl: true
            });

            // Initialize Places Autocomplete
            const input = document.getElementById('searchInput');
            autocomplete = new google.maps.places.Autocomplete(input, {
                bounds: zimbabweBounds,
                componentRestrictions: { country: 'zw' },
                fields: ['place_id', 'geometry', 'name', 'formatted_address'],
                strictBounds: true,
                types: ['establishment', 'geocode']
            });

            // Handle place selection
            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();
                
                if (!place.geometry || !place.geometry.location) {
                    alert('No location found for: ' + place.name);
                    return;
                }

                // Remove previous search marker
                if (searchMarker) {
                    searchMarker.setMap(null);
                }

                // Center map on selected location
                map.setCenter(place.geometry.location);
                map.setZoom(14);

                // Add marker for searched location
                searchMarker = new google.maps.Marker({
                    map: map,
                    position: place.geometry.location,
                    title: place.name,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        fillColor: '#667eea',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 2
                    }
                });

                // Info window for search result
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div class="info-popup">
                            <h3>${place.name}</h3>
                            <p><i class="fa-solid fa-location-dot"></i> ${place.formatted_address}</p>
                        </div>
                    `
                });

                searchMarker.addListener('click', function() {
                    infoWindow.open(map, searchMarker);
                });

                infoWindow.open(map, searchMarker);
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

                // Create marker
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(province.lat), lng: parseFloat(province.lng) },
                    map: map,
                    title: province.name,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: Math.max(15, Math.min(40, province.incidents * 0.8)),
                        fillColor: color,
                        fillOpacity: 0.7,
                        strokeColor: '#ffffff',
                        strokeWeight: 2
                    }
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

                marker.addListener('click', function() {
                    infoWindow.open(map, marker);
                });

                markers.push(marker);
            });
        }

        // Load error handler
        window.gm_authFailure = function() {
            alert('Google Maps API authentication failed. Please check the API key.');
        };
    </script>
    
    <!-- Load Google Maps API with callback and async loading -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa0Bfxbhl60_Xk7wEU1nfdxMGjHqKneGA&libraries=places&callback=initMap&loading=async" async defer></script>
</body>
</html>
