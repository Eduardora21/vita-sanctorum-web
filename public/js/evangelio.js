document.addEventListener('DOMContentLoaded', () => {
    const evangelioCita = document.getElementById('evangelioCita');
    const evangelioTexto = document.getElementById('evangelioTexto');
    const evangelioFecha = document.getElementById('evangelioFecha');

    if (!evangelioTexto) return;

    // Formatear la fecha en la tarjeta
    const hoy = new Date();
    const opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    if (evangelioFecha) {
        evangelioFecha.textContent = hoy.toLocaleDateString('es-ES', opcionesFecha);
    }

    // Consulta directa a nuestra propia API interna
    fetch('api/evangelio.php')
        .then(response => {
            if (!response.ok) throw new Error('Error al conectar con la API interna');
            return response.json();
        })
        .then(data => {
            if (evangelioCita) {
                evangelioCita.textContent = data.cita;
            }
            evangelioTexto.innerHTML = `
                <p>${data.texto}</p>
                <p class="evangelio-ref">— ${data.aclamacion}</p>
            `;
        })
        .catch(err => {
            console.error('Error cargando el evangelio interno:', err);
            if (evangelioCita) {
                evangelioCita.textContent = 'Lectura del Santo Evangelio';
            }
            evangelioTexto.innerHTML = `
                <p>"Yo soy el camino, la verdad y la vida; nadie viene al Padre sino por mí."</p>
                <p class="evangelio-ref">— San Juan 14, 6</p>
            `;
        });
});