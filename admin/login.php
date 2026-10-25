<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si ya tiene sesión activa, redirigir directo al panel
if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Por favor complete todos los campos.';
    } else {
        $database = new Database();
        $db = $database->getConnection();

        if ($db) {
            $stmt = $db->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar contraseña con hash BCRYPT
            if ($usuario && password_verify($password, $usuario['password'])) {
                $_SESSION['admin_logged'] = true;
                $_SESSION['admin_id'] = $usuario['id'];
                $_SESSION['admin_nombre'] = $usuario['nombre'];

                header('Location: index.php');
                exit;
            } else {
                $error = 'Credenciales incorrectas. Verifique su correo o contraseña.';
            }
        } else {
            $error = 'No fue posible conectar con el servidor de autenticación.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo | Vita Sanctorum</title>
    <link rel="icon" type="image/jpeg" href="../public/img/logo.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-login-body">

    <div class="login-container">
        <div class="login-card">
            
            <div class="login-header">
                <span class="logo-wrapper login-logo">
                    <img src="../public/img/logo.jpg" alt="Logo Vita Sanctorum" class="brand-logo">
                </span>
                <h1 class="login-title">Vita Sanctorum</h1>
                <p class="login-subtitle">Acceso al Panel de Gestión</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="login-alert-error">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Usuario o Correo Institucional</label>
                    <input 
                        type="email" 
                        id="username" 
                        name="username" 
                        placeholder="admin@vitasanctorum.com" 
                        value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>"
                        required 
                        autocomplete="username"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••••••" 
                        required 
                        autocomplete="current-password"
                    >
                </div>

                <div class="login-actions">
                    <button type="submit" class="btn-gold btn-block">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <div class="login-footer">
                <a href="../index.php" class="login-back-link">&larr; Volver al portal público</a>
            </div>
        </div>
    </div>

</body>
</html>