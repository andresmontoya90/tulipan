<?php
require_once __DIR__ . '/../php/auth.php';
require_role(['admin','vendedor']);
$pdo = db();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $titulo = trim($_POST['titulo'] ?? '');
        $orden  = (int)($_POST['orden'] ?? 0);
        $activo = isset($_POST['activo']) ? 1 : 0;
        $imagen_url = null;

        if (!empty($_FILES['imagen']['name'])) {
            $file = $_FILES['imagen'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                    $folder = __DIR__ . '/../assets/imagen/carrusel/';
                    if (!is_dir($folder)) mkdir($folder, 0775, true);
                    $fname = 'car_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $folder . $fname)) {
                        $imagen_url = 'assets/imagen/carrusel/' . $fname; // relativa desde raíz
                    } else { $msg = 'No se pudo guardar la imagen.'; }
                } else { $msg = 'Formato de imagen no permitido.'; }
            } else { $msg = 'Error al subir la imagen.'; }
        }

        if ($imagen_url) {
            $stmt = $pdo->prepare("INSERT INTO carrusel_imagenes (titulo, imagen_url, orden, activo) VALUES (?,?,?,?)");
            $stmt->execute([$titulo, $imagen_url, $orden, $activo]);
            $msg = 'Imagen agregada.';
        } else { $msg = $msg ?: 'La imagen es obligatoria.'; }
    }

    if (isset($_POST['delete'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM carrusel_imagenes WHERE id_imagen=?");
            $stmt->execute([$id]);
            $msg = 'Imagen eliminada.';
        }
    }
}

$items = $pdo->query("SELECT * FROM carrusel_imagenes ORDER BY orden ASC, creado_en DESC")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Carrusel - Tulipán</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <main class="container">
    <h1>Carrusel</h1>
    <?php if ($msg): ?><div class="alert"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

    <section class="card">
      <h2>Nueva imagen</h2>
      <form method="post" enctype="multipart/form-data" class="form">
        <input type="hidden" name="create" value="1">
        <label>Título<input name="titulo"></label>
        <label>Orden<input type="number" name="orden" value="0"></label>
        <label><input type="checkbox" name="activo" checked> Activo</label>
        <label>Imagen<input type="file" name="imagen" accept=".jpg,.jpeg,.png,.webp" required></label>
        <button type="submit">Agregar</button>
      </form>
    </section>

    <section class="card">
      <h2>Listado</h2>
      <div class="grid">
        <?php foreach ($items as $i): ?>
          <div class="item">
            <img src="../<?= htmlspecialchars($i['imagen_url']) ?>" alt="<?= htmlspecialchars($i['titulo'] ?: 'Carrusel') ?>">
            <div class="item-body">
              <strong><?= htmlspecialchars($i['titulo'] ?: 'Sin título') ?></strong>
              <small>Orden: <?= (int)$i['orden'] ?> · <?= $i['activo'] ? 'Activo' : 'Inactivo' ?></small>
              <form method="post" onsubmit="return confirm('¿Eliminar imagen?');">
                <input type="hidden" name="id" value="<?= (int)$i['id_imagen'] ?>">
                <button class="danger" name="delete" value="1">Eliminar</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
</body>
</html>