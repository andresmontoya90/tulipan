<?php
/*session_start();
include "conexion.php";

$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

$sql = "SELECT * FROM empleados WHERE nombre_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $empleado = $resultado->fetch_assoc();

    if (password_verify($contrasena, $empleado['contrasena_hash'])) {
        $_SESSION['usuario'] = $empleado['nombre_usuario'];
        $_SESSION['rol'] = $empleado['rol'];

        // Si marcó "recordarme"
        if (isset($_POST['recordar'])) {
            setcookie("usuario", $empleado['nombre_usuario'], time() + (86400 * 7), "/");
        }

        header("Location: ../admin/dashboard.php");
        exit;
    }
}

echo "Usuario o contraseña incorrectos.";*/
<?php
require_once __DIR__ . 'php/autenticacion.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['usuario'] ?? '');
    $pass = $_POST['password'] ?? '';
    if ($user && $pass && login_user($user, $pass)) {
        header('Location: ../admin/dashboard.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}
header('Location: ../login.html' . ($error ? '?e=1' : ''));
?>

