<?php
// datos de conexion a la base de datos local con xampp
$host = "localhost";
$user = "root";
$pass = "";
$db   = "4736484_volley";

// creamos la conexion
$conexion = new mysqli($host, $user, $pass, $db);

// comprobamos si ha habido algun error al conectar
if ($conexion->connect_error) {
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}

// esto es para que los caracteres especiales como tildes se vean bien
$conexion->set_charset("utf8");
?>