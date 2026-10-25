<footer class="site-footer">
        <div class="footer-content-grid">
            <!-- Columna 1: Identidad -->
            <div class="footer-col footer-col-brand">
                <div class="footer-brand-header">
                    <span class="logo-wrapper logo-wrapper-sm">
                        <img src="/public/img/logo.jpg" alt="Icono Vita Sanctorum" class="brand-logo">
                    </span>
                    <span class="brand-title">Vita Sanctorum</span>
                </div>
                <p class="footer-quote">"Sancti et sanctae Dei, intercedite pro nobis."</p>
                <p class="footer-description">
                    Un espacio dedicado a la preservación y lectura reposada de las vidas y virtudes cristianas.
                </p>
            </div>

            <!-- Columna 2: Navegación limpia -->
            <div class="footer-col">
                <h4 class="footer-heading">Secciones</h4>
                <ul class="footer-clean-links">
                    <li><a href="/index.php">Santos Destacados</a></li>
                    <li><a href="/views/calendario.php">Calendario Litúrgico</a></li>
                    <li><a href="/views/patronazgos.php">Patronos y Protectores</a></li>
                </ul>
            </div>

            <!-- Columna 3: Información y Botón de Contacto -->
            <div class="footer-col">
                <h4 class="footer-heading">Colaboración</h4>
                <p class="footer-description" style="margin-bottom: 1.2rem;">
                    Textos e investigaciones con fines pedagógicos y culturales. ¿Tienes sugerencias o deseas aportar?
                </p>
                <a href="/views/contacto.php" class="btn-footer-contact">
                    ✉ Buzón de Sugerencias
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-container">
                <p>&copy; <?= date('Y'); ?> Vita Sanctorum. Todos los derechos reservados.</p>
                <p class="footer-subtext">Diseñado para una lectura serena y reflexiva.</p>
            </div>
        </div>
    </footer>
</body>
</html>