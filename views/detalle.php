<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Santo.php';

// Obtener el slug solicitado
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: ../index.php');
    exit;
}

// Conectar con la base de datos y consultar el santo por su slug
$database = new Database();
$db = $database->getConnection();
$santoModel = new Santo($db);

$santo = $santoModel->getBySlug($slug);

// Si el registro no existe en la base de datos, redirigir al catálogo principal
if (!$santo) {
    header('Location: ../index.php');
    exit;
}

$page_title = $santo['nombre'];

// Mapear campos de la base de datos
$lugarOrigen = $santo['lugar_origen'] ?? 'Origen no especificado';
$fraseCelebre = $santo['frase_celebre'] ?? '';

// Normalizar la ruta de la imagen para que cargue correctamente desde la subcarpeta views/
$rutaImagen = $santo['imagen'];
if (!str_starts_with($rutaImagen, '../')) {
    $rutaImagen = '../' . ltrim($rutaImagen, '/');
}

// Separar párrafos de la biografía (almacenados con dobles saltos de línea)
$parrafosBiografia = explode("\n\n", $santo['biografia']);
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
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
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
                <li><a href="../index.php" class="nav-link active">Vidas de Santos</a></li>
                <li><a href="calendario.php" class="nav-link">Calendario Litúrgico</a></li>
                <li><a href="patronazgos.php" class="nav-link">Patronazgos</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenedor Principal de Lectura a 2 Columnas -->
    <main class="detail-page-wrapper">
        <div class="reading-back">
            <a href="../index.php" class="btn-back">&larr; Volver al catálogo principal</a>
        </div>

        <div class="detail-layout">
            <!-- Columna Izquierda: Ficha Visual & Atributos -->
            <aside class="detail-sidebar">
                <div class="portrait-card">
                    <img src="<?= htmlspecialchars($rutaImagen); ?>" alt="<?= htmlspecialchars($santo['nombre']); ?>" class="portrait-image">
                    <div class="portrait-meta">
                        <span class="portrait-feast"><?= htmlspecialchars($santo['festividad']); ?></span>
                        <span class="portrait-epoch"><?= htmlspecialchars($santo['siglo'] ?? ''); ?></span>
                    </div>
                </div>

                <div class="sidebar-box">
                    <h3 class="sidebar-heading">Datos Canónicos</h3>
                    
                    <div class="fact-row">
                        <span class="fact-label">Origen</span>
                        <span class="fact-value"><?= htmlspecialchars($lugarOrigen); ?></span>
                    </div>
                    <div class="fact-row">
                        <span class="fact-label">Patronazgo</span>
                        <span class="fact-value"><?= htmlspecialchars($santo['patronazgo'] ?? 'No especificado'); ?></span>
                    </div>
                    <div class="fact-row">
                        <span class="fact-label">Atributos</span>
                        <span class="fact-value"><?= htmlspecialchars($santo['atributos'] ?? 'Tradición hagiográfica'); ?></span>
                    </div>
                </div>
            </aside>

            <!-- Columna Derecha: Lectura Editorial Completa -->
            <article class="detail-content">
                <header class="article-header">
                    <span class="article-kicker">Hagiografía Histórica</span>
                    <h1 class="article-title"><?= htmlspecialchars($santo['nombre']); ?></h1>
                    <p class="article-lead"><?= htmlspecialchars($santo['titulo']); ?></p>
                </header>

                <!-- Cita célebre resaltada -->
                <?php if (!empty($fraseCelebre)): ?>
                    <blockquote class="saint-quote">
                        <p class="quote-text">"<?= htmlspecialchars($fraseCelebre); ?>"</p>
                        <cite class="quote-author">&mdash; <?= htmlspecialchars($santo['nombre']); ?></cite>
                    </blockquote>
                <?php endif; ?>

                <!-- Biografía con Capitular -->
                <section class="article-section">
                    <h2 class="section-title">Vida y Vocación</h2>
                    <?php foreach ($parrafosBiografia as $index => $parrafo): ?>
                        <p class="article-paragraph <?= $index === 0 ? 'drop-cap-paragraph' : ''; ?>">
                            <?= nl2br(htmlspecialchars(trim($parrafo))); ?>
                        </p>
                    <?php endforeach; ?>
                </section>

                <!-- Camino a la santidad -->
                <?php if (!empty($santo['santidad'])): ?>
                    <section class="article-section">
                        <h2 class="section-title">Reconocimiento y Canonización</h2>
                        <p class="article-paragraph"><?= nl2br(htmlspecialchars($santo['santidad'])); ?></p>
                    </section>
                <?php endif; ?>

                <!-- Módulo de Reflexión Espiritual Asistida por IA -->
                 <!-- Botón Flotante de Reflexión Asistida (Estilo WhatsApp) -->
<button type="button" 
        id="btnIaTrigger" 
        class="ia-fab-btn" 
        title="Meditar sobre este santo"
        data-santo="<?= htmlspecialchars($santo['nombre']); ?>"
        data-titulo="<?= htmlspecialchars($santo['titulo'] ?? ''); ?>">
    <span class="ia-fab-icon">✦</span>
    <span class="ia-fab-text">Reflexión Espiritual</span>
</button>

<!-- Ventana Flotante / Modal Códice -->
<div id="iaModal" class="ia-modal-overlay">
    <div class="ia-modal-window">
        <div class="ia-modal-header">
            <div class="ia-modal-title-wrap">
                <span class="ia-badge">Asistente Hagiográfico</span>
                <h3 class="ia-modal-title">Meditación &amp; Virtud Diaria</h3>
                <span class="ia-modal-saint"><?= htmlspecialchars($santo['nombre']); ?></span>
            </div>
            <button type="button" id="iaModalClose" class="ia-modal-close" aria-label="Cerrar">&times;</button>
        </div>

        <div class="ia-modal-body">
            <div id="iaReflexionOutput" class="ia-output-box">
                <p class="ia-initial-state">
                    Haz clic en el botón de abajo para meditar sobre las virtudes de <strong><?= htmlspecialchars($santo['nombre']); ?></strong> y su enseñanza para la vida contemporánea.
                </p>
            </div>
        </div>

        <div class="ia-modal-footer">
            <button type="button" id="btnGenerarReflexion" class="btn-gold btn-ia-action">
                ✦ Generar Reflexión
            </button>
        </div>
    </div>
</div>


                <!-- Oración tradicional -->
                <?php if (!empty($santo['oracion'])): ?>
                    <section class="prayer-card">
                        <span class="prayer-label">Oración de Intercesión</span>
                        <p class="prayer-text"><?= nl2br(htmlspecialchars($santo['oracion'])); ?></p>
                    </section>
                <?php endif; ?>
            </article>
        </div>
    </main>

    <!-- Pie de página -->
    <footer class="site-footer">
        <div class="footer-bottom">
            <div class="footer-bottom-container">
                <p>&copy; <?= date('Y'); ?> Vita Sanctorum. Todos los derechos reservados.</p>
                <p class="footer-subtext">Diseñado para una lectura serena y reflexiva.</p>
            </div>
        </div>
    </footer>
<script src="../public/js/reflexion.js"></script>
</body>
</html>