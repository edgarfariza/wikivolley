<?php
include 'conexion.php';
session_start();

// SEGURIDAD: Si no es Administrador, lo expulsamos inmediatamente
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

// Recogemos la acción y el ID del usuario por la URL
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $accion = $_GET['accion'];
    $id_usuario = intval($_GET['id']);
    $id_admin_actual = $_SESSION['usuario_id']; // Evitar auto-eliminarse o quitarse el rol

    // ACCIÓN 1: ASCENDER A ADMINISTRADOR
    if ($accion === 'hacer_admin') {
        $sql = "UPDATE usuarios SET rol = 'Admin' WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->close();
    }
    
    // ACCIÓN 2: DEGRADAR A USUARIO COMÚN
    if ($accion === 'quitar_admin' && $id_usuario !== $id_admin_actual) {
        $sql = "UPDATE usuarios SET rol = 'usuario' WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->close();
    }

    // ACCIÓN 3: ELIMINAR CUENTA
    if ($accion === 'eliminar' && $id_usuario !== $id_admin_actual) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->close();
    }
}

// Volvemos automáticamente al panel principal para ver los cambios aplicados
header("Location: index.php");
exit();
?>