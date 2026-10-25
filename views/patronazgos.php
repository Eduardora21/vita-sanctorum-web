<?php
$page_title = 'Patronazgos y Causas';
$active_tab = 'patronazgos';

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db = $database->getConnection();

$santos_patronos = [];
if ($db) {
    $query = "SELECT slug, nombre, titulo, festividad, patronazgo, imagen 
              FROM santos 
              WHERE patronazgo IS NOT NULL AND TRIM(patronazgo) != ''
              ORDER BY nombre ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $santos_patronos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
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
    <!-- Solo cargamos style.css que ya tiene todos los estilos -->
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>

    <!-- Navegación Principal -->
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
                <li><a href="patronazgos.php" class="nav-link active">Patronazgos</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero / Portada -->
    <section class="hero-patron">
        <div class="hero-patron-inner">
            <p class="hero-subtitle">Guías Espirituales e Intercesores</p>
            <h1 class="hero-title">Patronos y Protectores</h1>
            <p class="hero-description">
                Localiza las intercesiones tradicionales vinculadas a diversas vocaciones, ciencias, oficios y circunstancias de necesidad espiritual a lo largo de la historia.
            </p>

            <!-- Buscador en tiempo real -->
            <div class="search-bar-container">
                <input type="text" class="search-input patron-search-input" placeholder="Buscar por causa, profesión, oficio o santo...">
            </div>

            <!-- Filtros Temáticos Rápidos -->
            <div class="filter-chips-container">
                <button type="button" class="chip-filter active" data-filter="all">Todas las causas</button>
                <button type="button" class="chip-filter" data-filter="estudiantes">Estudiantes &amp; Estudio</button>
                <button type="button" class="chip-filter" data-filter="difíciles">Causas Urgentes</button>
                <button type="button" class="chip-filter" data-filter="enfermos">Salud &amp; Enfermos</button>
                <button type="button" class="chip-filter" data-filter="trabajadores">Trabajo &amp; Oficios</button>
                <button type="button" class="chip-filter" data-filter="poetas">Música &amp; Arte</button>
            </div>
        </div>
    </section>

    <!-- Directorio y Grilla -->
    <main class="patron-main-container">
        <div class="patron-category-header">
            <h2 class="patron-category-title">Directorio de Intercesión</h2>
            <p class="patron-category-desc">
                Mostrando <span id="visiblePatronCount"><?= count($santos_patronos); ?></span> santos registrados con patronazgo.
            </p>
        </div>

        <?php if (!empty($santos_patronos)): ?>
            <div class="patron-grid">
                <?php foreach ($santos_patronos as $s): ?>
                    <?php 
                        $img = !empty($s['imagen']) ? '../' . ltrim($s['imagen'], '/') : '../public/img/san-francisco.jpg';
                    ?>
                    <article class="patron-card" 
                             data-cause="<?= strtolower(htmlspecialchars($s['patronazgo'])); ?>" 
                             data-saint="<?= strtolower(htmlspecialchars($s['nombre'])); ?>"
                             data-meta="<?= strtolower(htmlspecialchars($s['festividad'] . ' ' . $s['titulo'])); ?>"
                             data-category="<?= strtolower(htmlspecialchars($s['patronazgo'])); ?>">
                        
                        <div class="patron-card-top">
                            <img src="<?= htmlspecialchars($img); ?>" alt="<?= htmlspecialchars($s['nombre']); ?>" class="patron-card-thumb">
                            <div class="patron-card-heading">
                                <span class="patron-target-label">Patrono e Intercesor de</span>
                                <h3 class="patron-cause-name"><?= htmlspecialchars($s['patronazgo']); ?></h3>
                            </div>
                        </div>

                        <div class="patron-divider"></div>

                        <div class="patron-saint-info">
                            <span class="patron-saint-name"><?= htmlspecialchars($s['nombre']); ?></span>
                            <span class="patron-saint-festivity">Festividad: <?= htmlspecialchars($s['festividad']); ?></span>
                            <span class="patron-saint-meta"><?= htmlspecialchars($s['titulo']); ?></span>
                        </div>

                        <div class="patron-card-action">
                            <a href="detalle.php?slug=<?= urlencode($s['slug']); ?>" class="btn-read-patron">
                                Ver Biografía &amp; Oración &rarr;
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="calendar-empty">
                <p>No se encontraron patronazgos registrados en la base de datos.</p>
            </div>
        <?php endif; ?>
    </main>

    <!-- Pie de página unificado -->
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

    <!-- Script de filtrado -->
    <script src="../public/js/patronazgos.js"></script>
</body>
</html>