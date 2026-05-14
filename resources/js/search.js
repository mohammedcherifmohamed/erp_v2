class SearchManager {
    constructor() {
        this.debounceTimers = new Map();
        this.init();
    }

    init() {
        document.querySelectorAll('[data-search]').forEach(container => {
            const input = container.querySelector('.search-input');
            const resultsContainer = container.querySelector('.search-results-container');
            if (!input || !resultsContainer) return;

            // Debounced search on input
            input.addEventListener('input', () => {
                const key = container.dataset.search;
                clearTimeout(this.debounceTimers.get(key));
                this.debounceTimers.set(key, setTimeout(() => {
                    this.fetchResults(container, input.value, resultsContainer);
                }, 350));
            });

            // Pagination click delegation on the results container
            resultsContainer.addEventListener('click', (e) => {
                const link = e.target.closest('a[href]');
                if (!link || !link.closest('.pagination') && !link.closest('.search-pagination')) return;
                e.preventDefault();
                const url = new URL(link.href);
                const page = url.searchParams.get('page') || 1;
                this.fetchResults(container, input.value, resultsContainer, page);
            });
        });
    }

    async fetchResults(container, query, resultsContainer, page = 1) {
        const baseUrl = container.dataset.search;

        resultsContainer.innerHTML = `<div class="p-8 text-center text-gray-400">
            <svg class="animate-spin h-6 w-6 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <span class="text-sm">Chargement...</span>
        </div>`;

        try {
            const params = new URLSearchParams({ query, page });
            const response = await fetch(`${baseUrl}?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
            });

            if (!response.ok) throw new Error('Erreur serveur');

            resultsContainer.innerHTML = await response.text();
        } catch (err) {
            resultsContainer.innerHTML = `<div class="p-8 text-center text-danger-600">
                <p>Erreur lors de la recherche. Veuillez réessayer.</p>
            </div>`;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => new SearchManager());