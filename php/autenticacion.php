<?php
require_once __DIR__ . '/conexion.php';

function login_user(string $username, string $password): bool {
    $sql = "SELECT id_empleado, nombre_usuario, nombre_completo, email, contrasena_hash, rol
            FROM empleados WHERE nombre_usuario = ?";
    $stmt = db()->prepare($sql);
    $stmt->execute([$username]);
    $u = $stmt->fetch();
    if (!$u) return false;
    if (!password_verify($password, $u['contrasena_hash'])) return false;

    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => (int)$u['id_empleado'],
        'usuario' => $u['nombre_usuario'],
        'nombre' => $u['nombre_completo'] ?: $u['nombre_usuario'],
        'email' => $u['email'],
        'rol' => $u['rol'], // admin | vendedor
    ];
    return true;
}

function logout_user(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function require_login(): void {
    if (!current_user()) {
        header('Location: ../login.html');
        exit;
    }
}

function require_role(array $roles): void {
    require_login();
    if (!in_array($_SESSION['user']['rol'], $roles, true)) {
        http_response_code(403);
        echo 'Acceso denegado';
        exit;
    }
}