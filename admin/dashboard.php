<?php
require_once __DIR__ . '/../php/auth.php';
require_login();
$u = current_user();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel - Tulipán</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <header class="topbar">
    <div>Bienvenido, <?= htmlspecialchars($u['nombre']) ?> (<?= htmlspecialchars($u['rol']) ?>)</div>
    <nav>
      <?php if ($u['rol'] === 'admin'): ?>
        <a href="empleados.php">Empleados</a>
      <?php endif; ?>
      <a href="productos.php">Productos</a>
      <a href="carrusel.php">Carrusel</a>
      <a href="logout.php">Salir</a>
    </nav>
  </header>
  <main class="container">
    <h1>Panel de administración</h1>
    <p>Usa el menú para gestionar el contenido según tu rol.</p>
  </main>
</body>
</html>