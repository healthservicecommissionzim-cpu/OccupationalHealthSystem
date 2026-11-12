<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Incident Hotspot Mapping · Zimbabwe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    
    <!-- Google Maps API Loader -->
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg", v: "weekly"});</script>
    
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
        
        /* Map Container */
        .map-container { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        #map { height: 650px; width: 100%; }
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 22px; }
        .stat-card { background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); color: white; padding: 18px; border-radius: 14px; text-align: center; box-shadow: 0 8px 25px rgba(16,26,49,.06); }
        .stat-card p { font-size: 10px; opacity: 0.9; margin-bottom: 8px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        .stat-card strong { font-size: 32px; font-weight: 750; display: block; }
        
        /* Responsive */
        @media (max-width: 900px) {
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            #map { height: 450px; }
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
            <a href="{{ route('dashboards.super_admin') }}" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <!-- Map -->
        <div class="map-container">
            <div id="map"></div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <p>Total Incidents</p>
                <strong id="total-incidents">206</strong>
            </div>
            <div class="stat-card">
                <p>Provinces Covered</p>
                <strong>10</strong>
            </div>
            <div class="stat-card">
                <p>Active Facilities</p>
                <strong id="total-facilities">48</strong>
            </div>
            <div class="stat-card">
                <p>Active Cases</p>
                <strong id="active-cases">89</strong>
            </div>
        </div>
    </div>

    <script>
        let map;
        let markers = [];
        
        // Zimbabwe provinces data with coordinates and incident counts
        const zimbabweProvinces = [
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

        // Zimbabwe boundaries (approximate)
        const zimbabweBounds = {
            north: -15.5,
            south: -22.5,
            west: 25.2,
            east: 33.1
        };

        // Initialize Google Map centered on Zimbabwe
        async function initMap() {
            try {
                // Center of Zimbabwe
                const zimbabweCenter = { lat: -19.0154, lng: 29.1549 };

                // Request Google Maps libraries
                const { Map } = await google.maps.importLibrary("maps");
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

                // Create map restricted to Zimbabwe
                map = new Map(document.getElementById("map"), {
                    zoom: 7,
                    center: zimbabweCenter,
                    mapId: "ZIMBABWE_INCIDENTS_MAP",
                    restriction: {
                        latLngBounds: {
                            north: zimbabweBounds.north,
                            south: zimbabweBounds.south,
                            west: zimbabweBounds.west,
                            east: zimbabweBounds.east
                        },
                        strictBounds: true
                    },
                    minZoom: 6,
                    maxZoom: 18,
                    styles: [
                        {
                            featureType: "all",
                            elementType: "geometry",
                            stylers: [{ color: "#f5f7fa" }]
                        },
                        {
                            featureType: "water",
                            elementType: "geometry",
                            stylers: [{ color: "#c9e6f2" }]
                        },
                        {
                            featureType: "road",
                            elementType: "geometry",
                            stylers: [{ color: "#ffffff" }]
                        }
                    ]
                });

                // Add markers for each province
                zimbabweProvinces.forEach(province => {
                    // Create custom marker content
                    const markerDiv = document.createElement('div');
                    markerDiv.style.cssText = `
                        background: ${getColorByIncidents(province.incidents)};
                        width: ${Math.min(60, province.incidents + 20)}px;
                        height: ${Math.min(60, province.incidents + 20)}px;
                        border-radius: 50%;
                        border: 3px solid white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-weight: bold;
                        font-size: 14px;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                        cursor: pointer;
                        transition: transform 0.2s;
                    `;
                    markerDiv.textContent = province.incidents;
                    markerDiv.onmouseenter = () => { markerDiv.style.transform = 'scale(1.1)'; };
                    markerDiv.onmouseleave = () => { markerDiv.style.transform = 'scale(1)'; };

                    const marker = new AdvancedMarkerElement({
                        map: map,
                        position: { lat: province.lat, lng: province.lng },
                        content: markerDiv,
                        title: province.name
                    });

                    // Create info window
                    const infoWindow = new google.maps.InfoWindow({
                        content: `
                            <div style="padding: 10px; font-family: Inter, sans-serif;">
                                <h3 style="margin: 0 0 8px 0; color: #101a31; font-size: 16px;">${province.name}</h3>
                                <p style="margin: 4px 0; color: #718096; font-size: 13px;">
                                    <i class="fa-solid fa-triangle-exclamation" style="color: #079b6d;"></i> 
                                    <strong>${province.incidents}</strong> incidents
                                </p>
                                <p style="margin: 4px 0; color: #718096; font-size: 13px;">
                                    <i class="fa-solid fa-hospital" style="color: #079b6d;"></i> 
                                    <strong>${province.facilities}</strong> facilities
                                </p>
                            </div>
                        `
                    });

                    // Add click listener to show info window
                    markerDiv.addEventListener('click', () => {
                        infoWindow.open(map, marker);
                    });

                    markers.push(marker);
                });

                console.log('Map initialized successfully with ' + markers.length + ' markers');
            } catch (error) {
                console.error('Error initializing map:', error);
            }
        }

        // Get color based on incident count
        function getColorByIncidents(count) {
            if (count > 35) return '#dc2626'; // Red - high incidents
            if (count > 20) return '#f59e0b'; // Orange - medium incidents
            if (count > 10) return '#10b981'; // Green-ish - moderate incidents
            return '#079b6d'; // Green - low incidents
        }

        // Initialize map when page loads
        window.addEventListener('load', initMap);
    </script>
</body>
</html>
