<?php
$page_title = 'Contacto y Sugerencias';
$active_tab = 'contacto';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title); ?> | Vita Sanctorum</title>
    <link rel="icon" type="image/jpeg" href="../public/img/logo.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="../public/css/index_showcase.css?v=<?= time(); ?>">
</head>
<body>

    <!-- Navegación -->
    <header class="site-header">
        <nav class="nav-container">
            <a href="../index.php" class="brand-link">
                <span class="logo-wrapper">
                    <img src="../public/img/logo.jpg" alt="Icono Vita Sanctorum" class="brand-logo">
                </span>
                <div class="brand-text">
                    <span class="brand-title">Vita Sanctorum</span>
                    <span class="brand-tagline">Historia &amp; Tradición</span>
                </div>
            </a>
            <ul class="nav-menu">
                <li><a href="../index.php" class="nav-link">Vidas de Santos</a></li>
                <li><a href="calendario.php" class="nav-link">Calendario Litúrgico</a></li>
                <li><a href="patronazgos.php" class="nav-link">Patronazgos</a></li>
            </ul>
        </nav>
    </header>

    <!-- Encabezado Hero -->
    <main class="contact-wrapper">
        <div class="contact-header">
            <p class="hero-subtitle">Buzón Hagiográfico &amp; Colaboración</p>
            <h1 class="contact-title">Sugerencias y Enlace</h1>
            <p class="contact-description">
                Un canal abierto para preservar con fidelidad la historia sagrada. Envíanos propuestas de santos no registrados, rectificaciones biográficas o consultas.
            </p>
        </div>

        <!-- Disposición a dos columnas -->
        <div class="contact-layout-grid">
            
            <!-- Columna Izquierda: Información de Colaboración -->
            <aside class="contact-info-panel">
                <div class="info-card-block">
                    <span class="info-card-badge">Criterios de Archivo</span>
                    <h3 class="info-card-title">Propuestas de Canonización</h3>
                    <p class="info-card-text">
                        Para sugerir la incorporación de un nuevo siervo de Dios o santo, es de gran ayuda incluir el siglo aproximado, patronazgo tradicional y fecha festiva.
                    </p>
                </div>

                <div class="info-card-block">
                    <span class="info-card-badge">Rigor Histórico</span>
                    <h3 class="info-card-title">Fuentes &amp; Correcciones</h3>
                    <p class="info-card-text">
                        Las biografías se nutren de la patrística, actas de mártires y leccionarios oficiales. Si notas algún dato cronológico impreciso, lo revisaremos con prontitud.
                    </p>
                </div>

                <div class="info-card-block direct-mail-card">
                    <h4 class="direct-mail-title">Contacto Directo</h4>
                    <p class="direct-mail-sub">¿Prefieres redactar desde tu cliente de correo local?</p>
                    <a href="mailto:ramirezreynosae@gmail.com" class="direct-mail-link">
                        ramirezreynosae@gmail.com &rarr;
                    </a>
                </div>
            </aside>

            <!-- Columna Derecha: Formulario -->
            <div class="contact-card">
                <form id="contactForm" class="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contactNombre">Tu Nombre</label>
                            <input type="text" id="contactNombre" placeholder="Ej: Francisco Ramírez" required>
                        </div>
                        <div class="form-group">
                            <label for="contactEmail">Correo Electrónico</label>
                            <input type="email" id="contactEmail" placeholder="tunombre@correo.com" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contactTipo">Motivo de Colaboración</label>
                        <select id="contactTipo" class="contact-select">
                            <option value="Sugerencia de Nuevo Santo">Sugerir un nuevo santo para el catálogo</option>
                            <option value="Corrección de Información">Corrección de fecha litúrgica o dato biográfico</option>
                            <option value="Aporte Bibliográfico">Aporte de oración o fuente histórica</option>
                            <option value="Comentario General">Comentario o consulta general</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="contactAsunto">Asunto Breve</label>
                        <input type="text" id="contactAsunto" placeholder="Ej: Adición de San Martín de Porres" required>
                    </div>

                    <div class="form-group">
                        <label for="contactMensaje">Mensaje o Detalle</label>
                        <textarea id="contactMensaje" rows="6" placeholder="Describe aquí la información o el santo propuesto..." required></textarea>
                    </div>

                    <div class="contact-actions">
                        <button type="submit" class="btn-gold btn-block-contact">
                            ✉ Preparar Mensaje en Gmail Web
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <!-- Bloque de Oración Espiritual -->
    <section class="spiritual-quote-banner" style="margin-bottom: 5.5rem;">
        <div class="spiritual-quote-content">
            <span class="quote-icon">❦</span>
            <blockquote class="spiritual-text">
                "Creador inefable, derrama sobre las tinieblas de mi inteligencia un rayo de tu claridad. Dame agudeza para entender, capacidad para retener, método y facilidad para aprender, y gracia abundante para hablar."
            </blockquote>
            <cite class="spiritual-author">— Santo Tomás de Aquino, <em>Oración antes del estudio y la labor</em></cite>
        </div>
    </section>

    <!-- Pie de página editorial unificado -->
    <footer class="site-footer">
        <div class="footer-content-grid">
            <div class="footer-col footer-col-brand">
                <div class="footer-brand-header">
                    <span class="logo-wrapper logo-wrapper-sm">
                        <img src="../public/img/logo.jpg" alt="Icono Vita Sanctorum" class="brand-logo">
                    </span>
                    <span class="brand-title">Vita Sanctorum</span>
                </div>
                <p class="footer-quote">"Sancti et sanctae Dei, intercedite pro nobis."</p>
                <p class="footer-description">
                    Un espacio dedicado a la preservación y lectura reposada de las vidas y virtudes cristianas.
                </p>
            </div>

            <div class="footer-col">
                <h4 class="footer-heading">Secciones</h4>
                <ul class="footer-clean-links">
                    <li><a href="../index.php">Santos Destacados</a></li>
                    <li><a href="calendario.php">Calendario Litúrgico</a></li>
                    <li><a href="patronazgos.php">Patronos y Protectores</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="footer-heading">Colaboración</h4>
                <p class="footer-description" style="margin-bottom: 1.2rem;">
                    Textos e investigaciones con fines pedagógicos y culturales. ¿Tienes sugerencias o deseas aportar?
                </p>
                <a href="contacto.php" class="btn-footer-contact">
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

    <!-- Script modular de contacto -->
    <script src="../public/js/contacto.js"></script>
</body>
</html>