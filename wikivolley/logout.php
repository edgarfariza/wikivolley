<?php
session_start();

// Destruimos todas las variables de la sesión
$_SESSION = array();

// Si se desea destruir la cookie de sesión, se borra también
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruimos la sesión
session_destroy();

// Redirigimos al usuario al index, que ahora sabrá que está desconectado
header("Location: index.php");
exit();
?>