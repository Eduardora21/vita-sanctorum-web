<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si no existe la variable de sesión, redirige inmediatamente al login
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php');
    exit;
}