<?php
include 'conexion.php';
session_start();

// Si no está logueado, lo mandamos al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Recogemos el ID del empleo que viene por la URL
if (isset($_GET['id_empleo'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $empleo_id = intval($_GET['id_empleo']);

    // Comprobamos si ya se había postulado antes para no duplicar
    $sql_check = "SELECT id FROM postulaciones WHERE usuario_id = ? AND empleo_id = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("ii", $usuario_id, $empleo_id);
    $stmt_check->execute();
    $res_check = $stmt_check->get_result();

    if ($res_check->num_rows == 0) {
        // Si no se ha postulado, guardamos la interacción
        $sql_insert = "INSERT INTO postulaciones (usuario_id, empleo_id) VALUES (?, ?)";
        $stmt_insert = $conexion->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $usuario_id, $empleo_id);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
    $stmt_check->close();
    
    // Buscamos el contacto original de la empresa para redirigir al usuario
    $sql_contacto = "SELECT CONTACTO FROM bolsa_empleo WHERE id = ?";
    $stmt_contacto = $conexion->prepare($sql_contacto);
    $stmt_contacto->bind_param("i", $empleo_id);
    $stmt_contacto->execute();
    $res_contacto = $stmt_contacto->get_result()->fetch_assoc();
    
    $contacto = $res_contacto['CONTACTO'];
    $href = (strpos($contacto, '@') !== false) ? "mailto:$contacto" : $contacto;
    
    $stmt_contacto->close();
    
    // Redirigimos al usuario al email o enlace de la oferta de empleo real
    header("Location: $href");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>