<?php
include 'conexion.php';
session_start();

// Si el usuario ya está logueado, lo mandamos al index
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$mensaje_error = "";
$mensaje_exito = "";

// Comprobamos si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($nombre) && !empty($email) && !empty($password)) {
        
        // 1. Validar si el email ya existe en la base de datos
        $sql_verificar = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_verificar = $conexion->prepare($sql_verificar);
        $stmt_verificar->bind_param("s", $email);
        $stmt_verificar->execute();
        $resultado = $stmt_verificar->get_result();

        if ($resultado->num_rows > 0) {
            $mensaje_error = "El correo electrónico ya está registrado.";
            $stmt_verificar->close();
        } else {
            $stmt_verificar->close();

            // 2. Encriptar la contraseña de forma segura (Nativa de PHP)
            $password_encriptada = password_hash($password, PASSWORD_DEFAULT);

            // 3. Insertar el nuevo usuario en la tabla
            $sql_insertar = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
            $stmt_insertar = $conexion->prepare($sql_insertar);
            $stmt_insertar->bind_param("sss", $nombre, $email, $password_encriptada);

            if ($stmt_insertar->execute()) {
                $mensaje_exito = "¡Cuenta creada con éxito! Redirigiendo al inicio de sesión...";
                // Redirigimos al login después de 2 segundos para que puedan leer el mensaje de éxito
                header("refresh:2; url=login.php");
            } else {
                $mensaje_error = "Hubo un error al registrar la cuenta. Inténtalo de nuevo.";
            }
            $stmt_insertar->close();
        }
    } else {
        $mensaje_error = "Por favor, rellena todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WIKIVOLLEY - Crear Cuenta</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="body-login">

<div class="contenedor-login">
    <div class="login-header">
        <a href="index.php">
            <img src="img/logodef.png" alt="Logo WikiVolley" class="logo-login">
        </a>
        <h2>Crear Nueva Cuenta</h2>
    </div>

    <!-- Mensajes de Alerta -->
    <?php if (!empty($mensaje_error)): ?>
        <div class="mensaje-error">
            <p>⚠️ <?php echo htmlspecialchars($mensaje_error); ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($mensaje_exito)): ?>
        <div class="mensaje-exito" style="background-color: #d1fae5; border-left: 4px solid #10b981; padding: 0.75rem; border-radius: 4px; margin-bottom: 1.5rem;">
            <p style="color: #065f46; margin: 0; font-size: 0.9rem;">✅ <?php echo htmlspecialchars($mensaje_exito); ?></p>
        </div>
    <?php endif; ?>

    <form action="registro.php" method="POST" class="formulario-login">
        <div class="grupo-campo">
            <label for="nombre">Nombre de Usuario</label>
            <input type="text" id="nombre" name="nombre" placeholder="Tu nombre o nick" required>
        </div>

        <div class="grupo-campo">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" placeholder="ejemplo@volley.com" required>
        </div>

        <div class="grupo-campo">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Mínimo 4 caracteres" required>
        </div>

        <button type="submit" class="btn-login">Registrarse</button>
    </form>
    
    <div class="login-footer">
        <a href="login.php" class="enlace-volver">¿Ya tienes cuenta? Inicia sesión</a>
    </div>
</div>

</body>
</html>