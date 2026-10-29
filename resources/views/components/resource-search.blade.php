<!-- Resource Search & Filter Component -->
<div class="resource-search-section">
    <div class="search-header">
        <h2>Search Resources</h2>
        <p>Find the information you need quickly</p>
    </div>

    <div class="search-container">
        <input 
            type="text" 
            id="resourceSearch" 
            class="search-input" 
            placeholder="Search resources, guidelines, protocols..." 
            aria-label="Search resources"
        >
        <button class="search-btn" aria-label="Search">
            <i class="fa-solid fa-search"></i>
        </button>
    </div>

    <div class="filter-container">
        <div class="filter-group">
            <label for="categoryFilter">Filter by Category</label>
            <select id="categoryFilter" class="filter-select" aria-label="Filter by category">
                <option value="">All Categories</option>
                <option value="protocols">Safety Protocols</option>
                <option value="guidelines">Guidelines</option>
                <option value="compliance">Compliance</option>
                <option value="training">Training Materials</option>
                <option value="forms">Forms & Templates</option>
                <option value="ppe">PPE & Safety Equipment</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="documentTypeFilter">Document Type</label>
            <select id="documentTypeFilter" class="filter-select" aria-label="Filter by document type">
                <option value="">All Types</option>
                <option value="pdf">PDF</option>
                <option value="html">Web Page</option>
                <option value="video">Video</option>
                <option value="checklist">Checklist</option>
            </select>
        </div>

        <button id="clearFilters" class="clear-filters-btn">Clear All Filters</button>
    </div>

    <div class="search-results-info" id="searchResultsInfo" style="display: none;">
        <p>Found <span id="resultCount">0</span> results</p>
    </div>
</div>

<style>
.resource-search-section {
    background: linear-gradient(135deg, #f0fdf4 0%, #e9f8f3 100%);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 32px;
}

.search-header {
    text-align: center;
    margin-bottom: 24px;
}

.search-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #1D4533;
    margin: 0 0 8px;
}

.search-header p {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

.search-container {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.search-input {
    flex: 1;
    padding: 12px 16px;
    border: 2px solid white;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.3s;
}

.search-input:focus {
    outline: none;
    border-color: #1D4533;
    box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
}

.search-btn {
    padding: 12px 20px;
    background: #1D4533;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s;
}

.search-btn:hover {
    background: #0f2818;
}

.search-btn:focus {
    outline: 2px solid #f59e0b;
    outline-offset: 2px;
}

.filter-container {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    align-items: flex-end;
    max-width: 600px;
    margin: 0 auto;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
    min-width: 200px;
}

.filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-select {
    padding: 10px 12px;
    border: 2px solid white;
    border-radius: 6px;
    background: white;
    font-size: 13px;
    font-family: inherit;
    cursor: pointer;
    transition: all 0.3s;
}

.filter-select:focus {
    outline: none;
    border-color: #1D4533;
    box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
}

.clear-filters-btn {
    padding: 10px 16px;
    background: white;
    color: #1D4533;
    border: 2px solid #1D4533;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.clear-filters-btn:hover {
    background: #1D4533;
    color: white;
}

.search-results-info {
    text-align: center;
    margin-top: 16px;
    padding: 12px;
    background: rgba(29, 69, 51, 0.1);
    border-radius: 6px;
    font-size: 13px;
    color: #374151;
}

@media (max-width: 768px) {
    .resource-search-section {
        padding: 16px;
    }

    .search-container {
        flex-direction: column;
    }

    .filter-container {
        flex-direction: column;
    }

    .filter-group {
        min-width: 100%;
    }

    .clear-filters-btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('resourceSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const documentTypeFilter = document.getElementById('documentTypeFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const searchResultsInfo = document.getElementById('searchResultsInfo');

    // Search and filter logic
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        const category = categoryFilter.value;
        const docType = documentTypeFilter.value;

        // Find all resource items (this assumes resources are in data-* attributes or specific selectors)
        const resourceItems = document.querySelectorAll('[data-resource-item]');
        let visibleCount = 0;

        resourceItems.forEach(item => {
            let matches = true;

            // Search term matching
            if (searchTerm) {
                const itemText = item.textContent.toLowerCase();
                const itemTitle = item.dataset.title?.toLowerCase() || '';
                const itemDescription = item.dataset.description?.toLowerCase() || '';
                matches = itemText.includes(searchTerm) || itemTitle.includes(searchTerm) || itemDescription.includes(searchTerm);
            }

            // Category matching
            if (matches && category) {
                matches = item.dataset.category === category;
            }

            // Document type matching
            if (matches && docType) {
                matches = item.dataset.type === docType;
            }

            // Show/hide item
            item.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        // Update results info
        if (searchTerm || category || docType) {
            searchResultsInfo.style.display = 'block';
            document.getElementById('resultCount').textContent = visibleCount;
        } else {
            searchResultsInfo.style.display = 'none';
        }
    }

    // Event listeners
    searchInput.addEventListener('input', performSearch);
    searchInput.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    categoryFilter.addEventListener('change', performSearch);
    documentTypeFilter.addEventListener('change', performSearch);

    clearFiltersBtn.addEventListener('click', () => {
        searchInput.value = '';
        categoryFilter.value = '';
        documentTypeFilter.value = '';
        searchResultsInfo.style.display = 'none';
        document.querySelectorAll('[data-resource-item]').forEach(item => {
            item.style.display = '';
        });
        searchInput.focus();
    });
});
</script>
