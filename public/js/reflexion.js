document.addEventListener('DOMContentLoaded', () => {
    const triggerBtn = document.getElementById('btnIaTrigger');
    const modal = document.getElementById('iaModal');
    const closeBtn = document.getElementById('iaModalClose');
    const generateBtn = document.getElementById('btnGenerarReflexion');
    const outputBox = document.getElementById('iaReflexionOutput');

    if (!triggerBtn || !modal) return;

    // Abrir modal
    triggerBtn.addEventListener('click', () => {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; // Evita scroll de fondo
    });

    // Cerrar modal
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    // Cerrar si hace clic fuera de la ventana
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // Cerrar con tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Generar meditación con la API
    generateBtn.addEventListener('click', async () => {
        const santoNombre = triggerBtn.getAttribute('data-santo') || '';
        const santoTitulo = triggerBtn.getAttribute('data-titulo') || '';

        generateBtn.disabled = true;
        generateBtn.textContent = 'Meditando en la vida del santo...';
        outputBox.innerHTML = '<p class="ia-loading-text">✦ Consultando las virtudes de este santo y formulando la meditación...</p>';

        try {
            const response = await fetch('../api/reflexion.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    santo: santoNombre,
                    titulo: santoTitulo
                })
            });

            const data = await response.json();

            if (data.ok) {
                const parrafos = data.reflexion.split('\n\n')
                    .filter(p => p.trim() !== '')
                    .map(p => `<p class="ia-reflexion-p">${p.replace(/\n/g, '<br>')}</p>`)
                    .join('');

                outputBox.innerHTML = parrafos;
                generateBtn.textContent = '✦ Regenerar meditación';
            } else {
                outputBox.innerHTML = `<p class="ia-error-text">⚠️ ${data.error || 'No fue posible generar la reflexión.'}</p>`;
                generateBtn.textContent = '✦ Reintentar';
            }
        } catch (error) {
            outputBox.innerHTML = '<p class="ia-error-text">⚠️ Ocurrió una dificultad de comunicación con el servicio de IA.</p>';
            generateBtn.textContent = '✦ Reintentar';
        } finally {
            generateBtn.disabled = false;
        }
    });
});