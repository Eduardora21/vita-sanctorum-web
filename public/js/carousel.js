document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('carouselContainer');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    const searchInput = document.getElementById('quickSearchInput');

    if (!container) return;

    let autoScrollInterval = null;
    let isHovered = false;
    const scrollStep = 340;
    const autoScrollDelay = 3500;

    // Diccionario de términos afines para búsquedas temáticas
    const synonymMap = {
        'estudiante': ['estudiante', 'universidad', 'escuela', 'teólogo', 'académico', 'bibliotecario', 'traductor', 'educador', 'monje'],
        'estudio': ['estudiante', 'universidad', 'escuela', 'teólogo', 'académico', 'bibliotecario', 'traductor', 'educador'],
        'enfermo': ['enfermo', 'enfermera', 'salud', 'médico', 'cirujano', 'lepra', 'hospital', 'convulsiv', 'desahuciado'],
        'salud': ['enfermo', 'enfermera', 'salud', 'médico', 'cirujano', 'hospital'],
        'trabajo': ['trabajador', 'carpintero', 'zapatero', 'curtidor', 'sastre', 'herrero', 'jinete', 'soldado', 'agricultor', 'comerciante', 'ingeniero', 'pescador'],
        'oficio': ['carpintero', 'zapatero', 'curtidor', 'sastre', 'herrero', 'tonelero', 'pescador', 'panadero'],
        'urgente': ['difícil', 'desesperada', 'imposible', 'urgente', 'perdida'],
        'musica': ['música', 'músico', 'poeta', 'cantante', 'compositor', 'oratorio'],
        'arte': ['arte', 'artista', 'pintor', 'escritor', 'poeta', 'encuadernador']
    };

    function startAutoPlay() {
        if (autoScrollInterval) clearInterval(autoScrollInterval);

        autoScrollInterval = setInterval(() => {
            if (isHovered) return;

            const maxScrollLeft = container.scrollWidth - container.clientWidth;
            if (container.scrollLeft >= maxScrollLeft - 10) {
                container.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: scrollStep, behavior: 'smooth' });
            }
        }, autoScrollDelay);
    }

    function stopAutoPlay() {
        if (autoScrollInterval) clearInterval(autoScrollInterval);
    }

    startAutoPlay();

    container.addEventListener('mouseenter', () => { isHovered = true; });
    container.addEventListener('mouseleave', () => { isHovered = false; });

    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            container.scrollBy({ left: -scrollStep, behavior: 'smooth' });
        });
    }

    if (btnNext) {
        btnNext.addEventListener('click', () => {
            const maxScrollLeft = container.scrollWidth - container.clientWidth;
            if (container.scrollLeft >= maxScrollLeft - 10) {
                container.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: scrollStep, behavior: 'smooth' });
            }
        });
    }

    // Buscador interactivo inteligente
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            const slides = document.querySelectorAll('.saint-card-slide');

            if (query.length > 0) {
                stopAutoPlay();
            } else {
                startAutoPlay();
            }

            // Hallar posibles sinónimos para la palabra ingresada
            let searchTerms = [query];
            for (const [key, synonyms] of Object.entries(synonymMap)) {
                if (query.includes(key)) {
                    searchTerms = searchTerms.concat(synonyms);
                }
            }

            slides.forEach(slide => {
                const name = slide.getAttribute('data-name') || '';
                const cause = slide.getAttribute('data-cause') || '';
                const meta = slide.getAttribute('data-meta') || '';
                const fullText = `${name} ${cause} ${meta}`;

                if (!query) {
                    slide.style.display = 'flex';
                    return;
                }

                const matches = searchTerms.some(term => fullText.includes(term));
                slide.style.display = matches ? 'flex' : 'none';
            });
        });
    }
});