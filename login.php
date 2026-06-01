<?php
include 'conexion.php';
session_start();

// Si el usuario ya está logueado, lo redirigimos directamente al index
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$error_login = "";

// Comprobamos si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Buscamos al usuario por su email
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            
           // Verificamos la contraseña encriptada
        if (password_verify($password, $usuario['password'])) {
    // Guardamos los datos en la sesión global
             $_SESSION['usuario_id'] = $usuario['id'];
             $_SESSION['usuario_nombre'] = $usuario['nombre'];
             $_SESSION['usuario_rol'] = $usuario['rol']; //
 // Redirigimos al index ya logueado
    header("Location: index.php");
    exit();
}else {
                $error_login = "Contraseña incorrecta.";
            }
        } else {
            $error_login = "El correo electrónico no está registrado.";
        }
        $stmt->close();
    } else {
        $error_login = "Por favor, rellena todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WIKIVOLLEY - Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="body-login">

<div class="contenedor-login">
    <div class="login-header">
        <a href="index.php">
            <img src="img/logodef.png" alt="Logo WikiVolley" class="logo-login">
        </a>
        <h2>Acceso al Portal</h2>
    </div>

    <?php if (!empty($error_login)): ?>
        <div class="mensaje-error">
            <p>⚠️ <?php echo htmlspecialchars($error_login); ?></p>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="formulario-login">
        <div class="grupo-campo">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" placeholder="ejemplo@volley.com" required>
        </div>

        <div class="login-footer" style="display: flex; flex-direction: column; gap: 10px; text-align: center;">
            <a href="registro.php" style="color: #38bdf8; text-decoration: none; font-size: 0.9rem;">¿No tienes cuenta? Regístrate aquí</a>
            <a href="index.php" class="enlace-volver">← Volver al inicio</a>
        </div>
        <div class="grupo-campo">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
        </div>

        <button type="submit" class="btn-login">Iniciar Sesión</button>
    </form>
    
    <div class="login-footer">
        <a href="index.php" class="enlace-volver">← Volver al inicio</a>
    </div>
</div>

</body>
</html>