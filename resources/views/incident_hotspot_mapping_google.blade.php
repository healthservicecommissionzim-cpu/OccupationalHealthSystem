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