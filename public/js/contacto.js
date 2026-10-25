document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contactForm');
    const correoDestino = 'ramirezreynosae@gmail.com';

    if (!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const nombre = document.getElementById('contactNombre').value.trim();
        const email = document.getElementById('contactEmail').value.trim();
        const tipo = document.getElementById('contactTipo').value;
        const asuntoBase = document.getElementById('contactAsunto').value.trim();
        const mensaje = document.getElementById('contactMensaje').value.trim();

        const subject = encodeURIComponent(`[Vita Sanctorum - ${tipo}] ${asuntoBase}`);
        const body = encodeURIComponent(
            `Hola,\n\nMi nombre es: ${nombre}\nCorreo de contacto: ${email}\nTipo de consulta: ${tipo}\n\nDetalle:\n${mensaje}\n\n---\nEnviado desde Vita Sanctorum`
        );

        const gmailUrl = `https://mail.google.com/mail/?view=cm&fs=1&to=${correoDestino}&su=${subject}&body=${body}`;
        window.open(gmailUrl, '_blank');
    });
});