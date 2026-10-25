<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | Vita Sanctorum' : 'Vita Sanctorum - Historia y Vida de los Santos'; ?></title>
    
    <!-- Favicon para la pestaña del navegador -->
    <link rel="icon" type="image/jpeg" href="public/img/logo.jpg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

    <!-- Navegación Principal -->
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
                <li><a href="index.php" class="nav-link <?= (!isset($active_tab) || $active_tab === 'home') ? 'active' : ''; ?>">Vidas de Santos</a></li>
                <li><a href="views/calendario.php" class="nav-link <?= (isset($active_tab) && $active_tab === 'calendario') ? 'active' : ''; ?>">Calendario Litúrgico</a></li>
                <li><a href="views/patronazgos.php" class="nav-link <?= (isset($active_tab) && $active_tab === 'patronazgos') ? 'active' : ''; ?>">Patronazgos</a></li>
            </ul>
        </nav>
    </header>