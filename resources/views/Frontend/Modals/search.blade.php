<div class="offcanvas offcanvas-top canvas-search" id="canvasSearch" tabindex="-1">
  <div class="canvas-search-wrapper">

    {{-- Header Row --}}
    <div class="canvas-search-header">
      <span class="canvas-search-label">
        <i class="icon icon-search me-2"></i> Search
      </span>
      <button class="canvas-search-close" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="icon icon-close"></i>
      </button>
    </div>

    {{-- Search Input --}}
    <form class="canvas-search-form" id="searchForm">
      <div class="canvas-search-input-wrap">
        <i class="icon icon-search canvas-search-icon"></i>
        <input
          type="text"
          id="searchInput"
          name="search"
          autocomplete="off"
          placeholder="Search for products, brands and more..."
          class="canvas-search-input"
          tabindex="0"
          aria-label="Search products"
        >
        <button type="submit" class="canvas-search-submit">Search</button>
      </div>
    </form>

    {{-- Search History --}}
    <div class="canvas-search-history" id="canvas-history-section" style="display:none;">
      <div class="canvas-search-history-header">
        <p class="canvas-search-trending-title">Recent Searches</p>
        <button class="canvas-history-clear" id="clearSearchHistory">Clear All</button>
      </div>
      <div class="canvas-search-tags" id="canvas-history-tags"></div>
    </div>

    {{-- Live Results --}}
    <div id="search-results-container" class="canvas-search-results"></div>

  </div>
</div>

<script>
  (function () {
    const HISTORY_KEY = 'itsroop_search_history';
    const MAX_HISTORY = 8;

    function getHistory() {
      try { return JSON.parse(localStorage.getItem(HISTORY_KEY)) || []; }
      catch { return []; }
    }

    function saveHistory(history) {
      localStorage.setItem(HISTORY_KEY, JSON.stringify(history));
    }

    function addToHistory(query) {
      if (!query || query.trim().length < 2) return;
      let history = getHistory();
      // Remove duplicate, then add to front
      history = history.filter(h => h.toLowerCase() !== query.toLowerCase());
      history.unshift(query.trim());
      history = history.slice(0, MAX_HISTORY);
      saveHistory(history);
    }

    function renderHistory() {
      const history = getHistory();
      const section = document.getElementById('canvas-history-section');
      const tagsContainer = document.getElementById('canvas-history-tags');

      if (!section || !tagsContainer) return;

      if (history.length === 0) {
        section.style.display = 'none';
        return;
      }

      section.style.display = 'block';
      tagsContainer.innerHTML = '';

      history.forEach(function (query) {
        const a = document.createElement('a');
        a.href = '/products?search=' + encodeURIComponent(query);
        a.className = 'canvas-search-tag canvas-search-tag--history';
        a.innerHTML = '<i class="icon-clock-history me-1"></i>' + query;

        // Save to history on click too
        a.addEventListener('click', function () {
          addToHistory(query);
        });

        tagsContainer.appendChild(a);
      });
    }

    // Show history when search opens
    const canvasSearch = document.getElementById('canvasSearch');
    if (canvasSearch) {
      canvasSearch.addEventListener('show.bs.offcanvas', function () {
        renderHistory();
      });
      canvasSearch.addEventListener('shown.bs.offcanvas', function () {
        const input = document.getElementById('searchInput');
        if (input) input.focus();
      });
    }

    // Clear All button
    const clearBtn = document.getElementById('clearSearchHistory');
    if (clearBtn) {
      clearBtn.addEventListener('click', function () {
        localStorage.removeItem(HISTORY_KEY);
        renderHistory();
      });
    }

    // Save query when search form is submitted
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
      searchForm.addEventListener('submit', function () {
        const input = document.getElementById('searchInput');
        if (input && input.value.trim()) {
          addToHistory(input.value.trim());
        }
      });
    }
  })();
</script>
