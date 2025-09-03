<?php
require_once __DIR__ . '/../php/auth.php';
require_role(['admin','vendedor']);
$pdo = db();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $categoria = trim($_POST['categoria'] ?? '');
    $stock = (int)($_POST['stock'] ?? 0);
    $disponible = isset($_POST['disponible']) ? 1 : 0;
    $imagen_url = null;

    if (!empty($_FILES['imagen']['name'])) {
        $file = $_FILES['imagen'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $folder = __DIR__ . '/../assets/imagen/producto/';
                if (!is_dir($folder)) mkdir($folder, 0775, true);
                $fname = 'prod_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $folder . $fname)) {
                    $imagen_url = 'assets/imagen/producto/' . $fname; // URL relativa desde raíz
                } else { $msg = 'No se pudo guardar la imagen.'; }
            } else { $msg = 'Formato de imagen no permitido.'; }
        } else { $msg = 'Error al subir la imagen.'; }
    }

    if ($nombre && $precio > 0) {
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, categoria, stock, disponible, imagen_url) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$nombre, $descripcion, $precio, $categoria, $stock, $disponible, $imagen_url]);
        $msg = 'Producto creado.';
    } else { $msg = $msg ?: 'Nombre y precio son obligatorios.'; }
}

$productos = $pdo->query("SELECT id_producto, nombre, precio, categoria, stock, disponible, imagen_url, creado_en FROM productos ORDER BY creado_en DESC")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Productos - Tulipán</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <main class="container">
    <h1>Productos</h1>
    <?php if ($msg): ?><div class="alert"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

    <section class="card">
      <h2>Nuevo producto</h2>
      <form method="post" enctype="multipart/form-data" class="form">
        <label>Nombre<input name="nombre" required></label>
        <label>Descripción<textarea name="descripcion"></textarea></label>
        <label>Precio<input type="number" step="0.01" name="precio" required></label>
        <label>Categoría<input name="categoria"></label>
        <label>Stock<input type="number" name="stock" value="0"></label>
        <label><input type="checkbox" name="disponible" checked> Disponible</label>
        <label>Imagen<input type="file" name="imagen" accept=".jpg,.jpeg,.png,.webp"></label>
        <button type="submit">Guardar</button>
      </form>
    </section>

    <section class="card">
      <h2>Listado</h2>
      <div class="grid">
        <?php foreach ($productos as $p): ?>
          <div class="item">
            <?php if ($p['imagen_url']): ?><img src="../<?= htmlspecialchars($p['imagen_url']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>"><?php endif; ?>
            <div class="item-body">
              <strong><?= htmlspecialchars($p['nombre']) ?></strong>
              <div>$<?= number_format($p['precio'], 2) ?></div>
              <small><?= htmlspecialchars($p['categoria'] ?: 'Sin categoría') ?> · Stock: <?= (int)$p['stock'] ?> · <?= $p['disponible'] ? 'Activo' : 'Inactivo' ?></small>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
</body>
</html>