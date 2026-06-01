```php
<?php
include 'conexion.php';

$nombre = "Admin";
$email = "admin@volley.com";
$password_plana = "1234";

$password_encriptada = password_hash($password_plana, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $nombre, $email, $password_encriptada);

if ($stmt->execute()) {
    echo "¡Usuario de prueba creado con éxito directamente desde PHP!";
} else {
    echo "Error al crear el usuario: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>