<?php
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

  <link rel="icon" type="image/png" href="assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/forms.css">
  <link rel="stylesheet" href="css/back_button.css">
  <link rel="stylesheet" href="css/fix_header.css">

  <title>Epicentro Shopping - Modificar Local</title>
</head>

<body>
  <?php include __DIR__ . '/../../includes/header.php'; ?>
  <?php include __DIR__ . '/../../includes/back_button.php'; ?>

  <div class="form">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div class="form-card">
            <h2>Modificar Local</h2>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
              <?php echo htmlspecialchars($_SESSION['error']);
								unset($_SESSION['error']); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <form method="POST" action="index.php" enctype="multipart/form-data">

              <input type="hidden" name="modulo" value="admin">
              <input type="hidden" name="accion" value="editar_local">

              <input type="hidden" name="id_local" value="<?php echo $local['id']; ?>">
              <input type="hidden" name="nombre_antiguo_local"
                value="<?php echo htmlspecialchars($local['nombre']); ?>">

              <div class="mb-3">
                <label class="form-label">Código Local</label>
                <input type="text" class="form-control" value="<?php echo $local['id']; ?>" disabled>
              </div>

              <div class="mb-3">
                <label for="nombre_local" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre_local" name="nombre_local"
                  value="<?php echo htmlspecialchars($local['nombre']); ?>" required>
              </div>

              <div class="mb-3">
                <label for="ubicacion_local" class="form-label">Ubicación</label>
                <input type="text" class="form-control" id="ubicacion_local" name="ubicacion_local"
                  value="<?php echo htmlspecialchars($local['ubicacion']); ?>" required>
              </div>

              <div class="mb-3">
                <label for="rubro_local" class="form-label">Rubro</label>
                <select class="form-select" id="rubro_local" name="rubro_local" required>
                  <option value="" disabled>Seleccione un rubro</option>
                  <?php foreach ($rubros as $label => $value): ?>
                  <option value="<?php echo htmlspecialchars($value); ?>"
                    <?php echo ($value == $local['rubro']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($label); ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label for="email_dueño" class="form-label">Email Dueño</label>
                <select class="form-select" id="email_dueño" name="id_dueño" required>
                  <option value="" disabled>Seleccione un dueño</option>
                  <?php foreach ($dueños as $dueño): ?>
                  <option value="<?php echo htmlspecialchars($dueño['id']); ?>"
                    <?php echo ($dueño['id'] == $local['idUsuario']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($dueño['email']); ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Imagen Actual</label>
                <?php if (!empty($local['imagen'])): ?>
                <img src="index.php?vista=imagen&local_id=<?php echo $local['id']; ?>" alt="Imagen del local"
                  class="preview-image d-block">
                <?php else: ?>
                <p class="text-muted text-center">No hay imagen disponible</p>
                <?php endif; ?>
              </div>

              <div class="mb-4">
                <label for="imagen_local" class="form-label">Nueva Imagen (opcional)</label>
                <input type="file" class="form-control" id="imagen_local" name="imagen_local"
                  accept=".png, .jpeg, .jpg">
                <small class="text-muted">Si no selecciona una imagen, se mantendrá la actual.</small>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-gradient">Aplicar Cambios</button>
                <button type="button" class="btn btn-secondary"
                  onclick="window.location.href='index.php?vista=admin_locales'">Cancelar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../../includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>