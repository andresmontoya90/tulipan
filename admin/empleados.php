<?php
require_once __DIR__ . '/../php/auth.php';
require_role(['admin']);
$pdo = db();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $usuario = trim($_POST['usuario'] ?? '');
        $nombre  = trim($_POST['nombre'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $rol     = $_POST['rol'] ?? 'vendedor';
        $pass    = $_POST['password'] ?? '';
        if ($usuario && $email && $pass) {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare("INSERT INTO empleados (nombre_usuario, nombre_completo, email, contrasena_hash, rol) VALUES (?,?,?,?,?)");
                $stmt->execute([$usuario, $nombre, $email, $hash, $rol]);
                $msg = 'Empleado creado.';
            } catch (PDOException $e) { $msg = 'Error: ' . $e->getMessage(); }
        } else { $msg = 'Usuario, email y contraseña son obligatorios.'; }
    }
    if (isset($_POST['delete'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id === current_user()['id']) {
            $msg = 'No puedes eliminar tu propia cuenta.';
        } elseif ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM empleados WHERE id_empleado=?");
            $stmt->execute([$id]);
            $msg = 'Empleado eliminado.';
        }
    }
}
$empleados = $pdo->query("SELECT id_empleado, nombre_usuario, nombre_completo, email, rol, creado_en FROM empleados ORDER BY creado_en DESC")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Empleados - Tulipán</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <main class="container">
    <h1>Empleados</h1>
    <?php if ($msg): ?><div class="alert"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

    <section class="card">
      <h2>Crear empleado</h2>
      <form method="post" class="form">
        <input type="hidden" name="create" value="1">
        <label>Usuario<input name="usuario" required></label>
        <label>Nombre<input name="nombre"></label>
        <label>Email<input type="email" name="email" required></label>
        <label>Rol
          <select name="rol">
            <option value="vendedor">Vendedor</option>
            <option value="admin">Admin</option>
          </select>
        </label>
        <label>Contraseña<input type="password" name="password" required></label>
        <button type="submit">Crear</button>
      </form>
    </section>

    <section class="card">
      <h2>Listado</h2>
      <table class="table">
        <thead><tr><th>ID</th><th>Usuario</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Creado</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($empleados as $e): ?>
          <tr>
            <td><?= (int)$e['id_empleado'] ?></td>
            <td><?= htmlspecialchars($e['nombre_usuario']) ?></td>
            <td><?= htmlspecialchars($e['nombre_completo']) ?></td>
            <td><?= htmlspecialchars($e['email']) ?></td>
            <td><?= htmlspecialchars($e['rol']) ?></td>
            <td><?= htmlspecialchars($e['creado_en']) ?></td>
            <td>
              <form method="post" onsubmit="return confirm('¿Eliminar empleado?');">
                <input type="hidden" name="id" value="<?= (int)$e['id_empleado'] ?>">
                <button class="danger" name="delete" value="1">Eliminar</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </main>
</body>
</html>