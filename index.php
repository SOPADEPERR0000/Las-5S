<?php
// ============================================================
// index.php — Pantalla de inicio de sesión (Login)
// ============================================================

session_start();

// Si el usuario ya tiene sesión activa, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once 'conexion.php';

$error   = '';
$success = '';

// ---- Procesar el formulario cuando se envía por POST ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitizar entradas básicas (la validación real la hace PDO)
    $correo    = trim($_POST['correo']    ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    // Validación de campos vacíos
    if (empty($correo) || empty($contrasena)) {
        $error = 'Por favor, completa todos los campos.';

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'El formato del correo electrónico no es válido.';

    } else {
        // Buscar al usuario por correo usando Prepared Statement (previene SQL Injection)
        $pdo  = obtenerConexion();
        $stmt = $pdo->prepare('SELECT id, nombre, contrasena FROM usuarios WHERE correo = :correo LIMIT 1');
        $stmt->execute([':correo' => $correo]);
        $usuario = $stmt->fetch();

        // Verificar que el usuario exista y que la contraseña sea correcta
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            // Regenerar el ID de sesión para prevenir Session Fixation
            session_regenerate_id(true);

            // Guardar datos del usuario en la sesión
            $_SESSION['usuario_id']     = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];

            header('Location: dashboard.php');
            exit;
        } else {
            // Mensaje genérico para no revelar si el correo existe o no
            $error = 'Correo o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">

        <!-- Encabezado -->
        <div class="auth-header">
            <div class="logo">
                <!-- Ícono de candado -->
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 1C9.24 1 7 3.24 7 6v2H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2h-2V6c0-2.76-2.24-5-5-5zm0 2c1.66 0 3 1.34 3 3v2H9V6c0-1.66 1.34-3 3-3zm0 9a2 2 0 1 1 0 4 2 2 0 0 1 0-4z"/>
                </svg>
            </div>
            <h1>Bienvenido</h1>
            <p>Inicia sesión para continuar</p>
        </div>

        <!-- Mensaje de error -->
        <?php if ($error): ?>
        <div class="alerta alerta-error" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <!-- Mensaje de éxito (viene de registro.php) -->
        <?php if (isset($_GET['registro']) && $_GET['registro'] === 'ok'): ?>
        <div class="alerta alerta-exito" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            Cuenta creada con éxito. Ya puedes iniciar sesión.
        </div>
        <?php endif; ?>

        <!-- Formulario de login -->
        <form method="POST" action="index.php" novalidate>

            <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <div class="input-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="tu@correo.com"
                        value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <div class="input-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input
                        type="password"
                        id="contrasena"
                        name="contrasena"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                </div>
            </div>

            <button type="submit" class="btn-primary">Iniciar sesión</button>
        </form>

        <!-- Enlace a registro -->
        <div class="auth-footer">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </div>

    </div>
</div>
</body>
</html>
