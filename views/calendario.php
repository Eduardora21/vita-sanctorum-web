<?php
$page_title = 'Calendario Litúrgico';
$active_tab = 'calendario';

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Santo.php';

// Detectar mes actual por defecto o parámetro GET
$mes_actual = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('n');
if ($mes_actual < 1 || $mes_actual > 12) {
    $mes_actual = (int)date('n');
}

$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

// Conectar con la base de datos
$database = new Database();
$db = $database->getConnection();
$santoModel = new Santo($db);

$festividades = $santoModel->getByMesLiturgico($mes_actual);
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
    
    <!-- Enlace a las dos hojas de estilo -->
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/index_showcase.css">
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
                <li><a href="calendario.php" class="nav-link active">Calendario Litúrgico</a></li>
                <li><a href="patronazgos.php" class="nav-link">Patronazgos</a></li>
            </ul>
        </nav>
    </header>

    <!-- Portada de la Sección -->
    <section class="hero">
        <p class="hero-subtitle">Ciclo Anual de Festividades</p>
        <h1 class="hero-title">Calendario Litúrgico</h1>
        <p class="hero-description">
            Sigue las fechas conmemorativas, solemnidades y memorias de la Iglesia universal a lo largo de cada mes del año.
        </p>

        <!-- Barra selectora de meses -->
        <nav class="months-bar">
            <?php foreach ($meses as $num => $nombre): ?>
                <a href="calendario.php?mes=<?= $num; ?>" class="month-pill <?= $num === $mes_actual ? 'month-pill-active' : ''; ?>">
                    <?= $nombre; ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </section>

    <!-- Lista de Festividades del Mes -->
    <main class="calendar-container">
        <div class="calendar-header-info">
            <h2 class="calendar-current-month"><?= $meses[$mes_actual]; ?></h2>
            <span class="calendar-counter"><?= count($festividades); ?> conmemoraciones registradas</span>
        </div>

        <?php if (!empty($festividades)): ?>
            <div class="calendar-timeline">
                <?php foreach ($festividades as $item): ?>
                    <?php 
                        $img = !empty($item['imagen']) ? '../' . ltrim($item['imagen'], '/') : '../public/img/san-francisco.jpg';
                    ?>
                    <article class="calendar-entry">
                        <div class="calendar-date-badge">
                            <span class="calendar-day"><?= htmlspecialchars($item['dia_liturgico']); ?></span>
                            <span class="calendar-month-short"><?= substr($meses[$mes_actual], 0, 3); ?></span>
                        </div>

                        <img src="<?= htmlspecialchars($img); ?>" alt="<?= htmlspecialchars($item['nombre']); ?>" class="calendar-saint-thumb">

                        <div class="calendar-entry-details">
                            <span class="calendar-rank"><?= htmlspecialchars($item['grado_liturgico'] ?? 'Memoria'); ?></span>
                            <h3 class="calendar-saint-name"><?= htmlspecialchars($item['nombre']); ?></h3>
                            <p class="calendar-saint-desc"><?= htmlspecialchars($item['titulo']); ?></p>
                        </div>

                        <div class="calendar-entry-action">
                            <a href="detalle.php?slug=<?= urlencode($item['slug']); ?>" class="btn-calendar-read">
                                Ver biografía &rarr;
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="calendar-empty">
                <p>No hay conmemoraciones cargadas para el mes de <?= $meses[$mes_actual]; ?> en este momento.</p>
                <a href="calendario.php?mes=10" class="btn-calendar-read" style="margin-top: 1.2rem; display: inline-block;">Ver festividades de Octubre</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Bloque Espiritual de Oración Litúrgica -->
    <section class="spiritual-quote-banner" style="margin-bottom: 5rem;">
        <div class="spiritual-quote-content">
            <span class="quote-icon">✝</span>
            <blockquote class="spiritual-text">
                "La oración es la elevación del alma a Dios o la petición al Señor de bienes convenientes. Quien bien ora, bien vive; quien bien vive, bien muere."
            </blockquote>
            <cite class="spiritual-author">— San Juan Damasceno, <em>De la fe ortodoxa</em></cite>
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

</body>
</html>