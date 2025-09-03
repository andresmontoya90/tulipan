<?php
include "conexion.php";

$correo = $_POST['correo'];

// Buscar empleado por correo
$sql = "SELECT nombre_usuario, contrasena_hash FROM empleados WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $empleado = $resultado->fetch_assoc();
    $usuario = $empleado['nombre_usuario'];
    $contrasena = $empleado['contrasena_hash']; // aquí es texto plano

    // Simular envío de correo (solo imprime en pantalla)
    $mensaje = "Hola, $usuario. Tu contraseña es: $contrasena";
    echo "<p>Correo enviado exitosamente:</p><p>$mensaje</p>";

    // Para envío real, puedes usar mail():
    // mail($correo, "Recuperación de contraseña", $mensaje);
} else {
    echo "Correo no encontrado.";
}
?>
