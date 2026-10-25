document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.search-input');
    const cards = document.querySelectorAll('.saint-card');

    if (!searchInput) return;

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase().trim();

        cards.forEach(card => {
            const name = card.querySelector('.saint-name')?.textContent.toLowerCase() || '';
            const meta = card.querySelector('.saint-meta')?.textContent.toLowerCase() || '';
            const bio = card.querySelector('.saint-bio')?.textContent.toLowerCase() || '';

            const matches = name.includes(query) || meta.includes(query) || bio.includes(query);

            if (matches) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
});