document.addEventListener('DOMContentLoaded', () => {
    const patronInput = document.querySelector('.patron-search-input');
    const filterButtons = document.querySelectorAll('.chip-filter');
    const patronCards = document.querySelectorAll('.patron-card');
    const countBadge = document.getElementById('visiblePatronCount');

    let activeFilter = 'all';

    // Diccionario de términos afines por cada categoría
    const filterKeywords = {
        'all': [],
        'estudiantes': ['estudiante', 'universidad', 'escuela', 'teólogo', 'académico', 'bibliotecario', 'traductor', 'educador', 'arqueólogo'],
        'difíciles': ['difícil', 'desesperada', 'imposible', 'urgente', 'perdida', 'tribulación', 'persecución'],
        'enfermos': ['enfermo', 'enfermera', 'salud', 'médico', 'cirujano', 'lepra', 'hospital', 'convulsiv', 'envenenamiento', 'desahuciado'],
        'trabajadores': ['trabajador', 'carpintero', 'zapatero', 'curtidor', 'sastre', 'herrero', 'jinete', 'soldado', 'agricultor', 'comerciante', 'ingeniero', 'arquitecto'],
        'poetas': ['música', 'músico', 'poeta', 'cantante', 'compositor', 'arte', 'pintor', 'artista', 'escritor']
    };

    function filterCards() {
        const query = patronInput ? patronInput.value.toLowerCase().trim() : '';
        let visibleCount = 0;

        patronCards.forEach(card => {
            const cause = card.getAttribute('data-cause') || '';
            const saint = card.getAttribute('data-saint') || '';
            const meta = card.getAttribute('data-meta') || '';
            
            // Texto total donde buscar
            const fullCardText = `${cause} ${saint} ${meta}`.toLowerCase();

            // 1. Verificación por input de texto escrito
            const matchesQuery = !query || fullCardText.includes(query);

            // 2. Verificación por píldora/categoría seleccionada
            let matchesFilter = false;
            if (activeFilter === 'all') {
                matchesFilter = true;
            } else {
                const keywords = filterKeywords[activeFilter] || [];
                // Comprueba si alguna palabra clave de la categoría está presente en el texto del santo
                matchesFilter = keywords.some(keyword => fullCardText.includes(keyword));
            }

            if (matchesQuery && matchesFilter) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (countBadge) {
            countBadge.textContent = visibleCount;
        }
    }

    if (patronInput) {
        patronInput.addEventListener('input', filterCards);
    }

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeFilter = btn.getAttribute('data-filter') || 'all';
            filterCards();
        });
    });
});