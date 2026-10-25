<?php
$page_title = 'Inicio';
$active_tab = 'inicio';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Santo.php';

$database = new Database();
$db = $database->getConnection();
$santoModel = new Santo($db);

// Consultar santos destacados
$santos = $santoModel->getDestacados();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vita Sanctorum | Vidas de Santos e Historia Litúrgica</title>
    <link rel="icon" type="image/jpeg" href="public/img/logo.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/index_showcase.css">
</head>
<body>

    <!-- Navegación Superior -->
    <header class="site-header">
        <nav class="nav-container">
            <a href="index.php" class="brand-link">
                <span class="logo-wrapper">
                    <img src="public/img/logo.jpg" alt="Icono Vita Sanctorum" class="brand-logo">
                </span>
                <div class="brand-text">
                    <span class="brand-title">Vita Sanctorum</span>
                    <span class="brand-tagline">Historia &amp; Tradición</span>
                </div>
            </a>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link active">Vidas de Santos</a></li>
                <li><a href="views/calendario.php" class="nav-link">Calendario Litúrgico</a></li>
                <li><a href="views/patronazgos.php" class="nav-link">Patronazgos</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Editorial Principal -->
    <section class="hero-home">
        <div class="hero-home-inner">
            <span class="hero-tag">Compendio Hagiográfico Universal</span>
            <h1 class="hero-main-title">Ejemplos de Fe, Virtud &amp; Sabiduría Trascendente</h1>
            <p class="hero-lead">
                Explora las biografías, patronazgos, oraciones tradicionales y el legado perenne de los santos de la Iglesia universal a través de los siglos.
            </p>

            <div class="hero-search-wrapper">
                <input type="text" id="quickSearchInput" class="hero-search-input" placeholder="Buscar santo, causa, patronazgo o época...">
            </div>
        </div>
    </section>

    <!-- Carrusel de Santos Destacados -->
    <section class="carousel-section">
        <div class="section-title-wrap">
            <div>
                <span class="section-kicker">Catálogo Principal</span>
                <h2 class="section-heading">Santos Venerados</h2>
            </div>
            <div class="carousel-controls">
                <button type="button" class="carousel-arrow" id="btnPrev" aria-label="Anterior">&lsaquo;</button>
                <button type="button" class="carousel-arrow" id="btnNext" aria-label="Siguiente">&rsaquo;</button>
            </div>
        </div>

        <div class="carousel-track-container" id="carouselContainer">
            <?php if (!empty($santos)): ?>
                <?php foreach ($santos as $s): ?>
                    <?php 
                        $img = !empty($s['imagen']) ? $s['imagen'] : 'public/img/san-francisco.jpg';
                    ?>
                    <article class="saint-card-slide" 
                             data-name="<?= strtolower(htmlspecialchars($s['nombre'])); ?>" 
                             data-cause="<?= strtolower(htmlspecialchars($s['patronazgo'] ?? '')); ?>"
                             data-meta="<?= strtolower(htmlspecialchars(($s['titulo'] ?? '') . ' ' . ($s['festividad'] ?? ''))); ?>">
                        <div class="slide-image-wrapper">
                            <img src="<?= htmlspecialchars($img); ?>" alt="<?= htmlspecialchars($s['nombre']); ?>" class="slide-image">
                            <span class="slide-feast-badge"><?= htmlspecialchars($s['festividad']); ?></span>
                        </div>
                        <div class="slide-content">
                            <h3 class="slide-title"><?= htmlspecialchars($s['nombre']); ?></h3>
                            <p class="slide-subtitle"><?= htmlspecialchars($s['titulo']); ?></p>
                            <p class="slide-patron">
                                <strong>Patrono de:</strong> <?= htmlspecialchars($s['patronazgo'] ?? 'General'); ?>
                            </p>
                            <a href="views/detalle.php?slug=<?= urlencode($s['slug']); ?>" class="slide-action-btn">
                                Ver Biografía &rarr;
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--text-muted); padding: 2rem;">No hay santos disponibles en este momento.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Sección: Evangelio del Día (Consumido vía API interna) -->
    <section class="evangelio-section">
        <div class="evangelio-container" id="evangelioCard">
            <div class="evangelio-header">
                <span class="evangelio-badge">Palabra de Dios</span>
                <span class="evangelio-fecha" id="evangelioFecha">Lectura Litúrgica Diaria</span>
            </div>
            
            <h2 class="evangelio-title" id="evangelioCita">Lectura del Santo Evangelio</h2>
            
            <div class="evangelio-body" id="evangelioTexto">
                <p class="evangelio-loading">Cargando lectura litúrgica...</p>
            </div>
        </div>
    </section>

    <!-- Cita Espiritual -->
    <section class="spiritual-quote-banner">
        <div class="spiritual-quote-content">
            <span class="quote-icon">❦</span>
            <blockquote class="spiritual-text">
                "Nos hiciste, Señor, para ti, y nuestro corazón está inquieto hasta que descanse en ti."
            </blockquote>
            <cite class="spiritual-author">— San Agustín de Hipona, <em>Las Confesiones</em></cite>
        </div>
    </section>

    <!-- Módulos de Acceso Directo -->
    <section class="categories-preview-section">
        <div class="categories-preview-grid">
            <a href="views/calendario.php" class="preview-box">
                <span class="preview-number">01</span>
                <h3 class="preview-box-title">Calendario Litúrgico</h3>
                <p class="preview-box-desc">Conoce las solemnidades, memorias y fiestas mes a mes durante todo el ciclo anual.</p>
                <span class="preview-link-arrow">Explorar calendario &rarr;</span>
            </a>

            <a href="views/patronazgos.php" class="preview-box">
                <span class="preview-number">02</span>
                <h3 class="preview-box-title">Patronos e Intercesores</h3>
                <p class="preview-box-desc">Descubre a qué santos invocar por profesiones, estudiantes, viajeros y causas urgentes.</p>
                <span class="preview-link-arrow">Consultar patronazgos &rarr;</span>
            </a>

            <a href="views/contacto.php" class="preview-box">
                <span class="preview-number">03</span>
                <h3 class="preview-box-title">Buzón de Sugerencias</h3>
                <p class="preview-box-desc">¿Falta algún santo o deseas colaborar con fuentes históricas? Escríbenos directamente.</p>
                <span class="preview-link-arrow">Enviar mensaje &rarr;</span>
            </a>
        </div>
    </section>

    <!-- Pie de Página -->
    <footer class="site-footer">
        <div class="footer-content-grid">
            <div class="footer-col footer-col-brand">
                <div class="footer-brand-header">
                    <span class="logo-wrapper logo-wrapper-sm">
                        <img src="public/img/logo.jpg" alt="Icono Vita Sanctorum" class="brand-logo">
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
                    <li><a href="index.php">Santos Destacados</a></li>
                    <li><a href="views/calendario.php">Calendario Litúrgico</a></li>
                    <li><a href="views/patronazgos.php">Patronos y Protectores</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="footer-heading">Colaboración</h4>
                <p class="footer-description" style="margin-bottom: 1.2rem;">
                    Textos e investigaciones con fines pedagógicos y culturales. ¿Tienes sugerencias o deseas aportar?
                </p>
                <a href="views/contacto.php" class="btn-footer-contact">
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

    <!-- Scripts JavaScript organizados en public/js/ -->
    <script src="public/js/carousel.js"></script>
    <script src="public/js/evangelio.js"></script>
</body>
</html>