 <?php
// Solo el administrador puede ejecutar esto
session_start();
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

// Ejecutamos el script de Python en segundo plano
$output = shell_exec('python3 "' . __DIR__ . '/import json.py" 2>&1');

// Volvemos al panel de admin con un mensaje de confirmación
header("Location: index.php?script=" . urlencode($output));
exit();
?>