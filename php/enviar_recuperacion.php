<?php
include "conexion.php";

$correo = $_POST['correo'];

// Buscar usuario con ese correo (debes tener campo email en la tabla empleados)
$sql = "SELECT * FROM empleados WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    $token = bin2hex(random_bytes(16)); // Token de recuperación
    $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Guardamos el token (agrega una tabla llamada `recuperacion`)
    $insert = $conexion->prepare("INSERT INTO recuperacion (email, token, expira) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $correo, $token, $expira);
    $insert->execute();

    // Simula envío (puedes usar PHPMailer para correos reales)
    $link = "http://localhost/tulipan/resetear_contrasena.php?token=$token";
    echo "Hemos enviado un correo con instrucciones: <a href='$link'>Recuperar ahora</a>";
} else {
    echo "Correo no registrado.";
}
?>
