<?php
// Asumimos que $novedad y $categorias ya vienen del controlador
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <link rel="icon" type="image/png" href="assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./public/css/header.css">
  <link rel="stylesheet" href="./public/css/footer.css">
  <link rel="stylesheet" href="./public/css/forms.css">
  <link rel="stylesheet" href="./public/css/back_button.css">
  <link rel="stylesheet" href="./public/css/fix_header.css">
  <link rel="stylesheet" href="./public/css/buttons.css">
  <link rel="stylesheet" href="./public/css/styles_fondo_and_titles.css">
  <title>Epicentro Shopping - Modificar Novedad</title>
</head>

<body>
  <?php include __DIR__ . '/../../includes/header.php'; ?>

  <div class="form-wrapper py-4">
    <div class="container">
      <div class="row align-items-center mb-4 mt-2">
        <div class="col-2 col-md-1">
          <?php include __DIR__ . '/../../includes/back_button.php'; ?>
        </div>
        <div class="col-8 col-md-10">
          <h2 class="text-center m-0 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 1.8rem;">
            Modificar Novedad
          </h2>
        </div>
        <div class="col-2 col-md-1"></div>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
          <div class="form-card shadow-sm border-0 p-4 bg-white rounded-4">

            <?php if (isset($_SESSION['error'])): ?>
              <div class="alert alert-danger alert-dismissible fade show">
                <?php echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>

            <form method="POST" action="index.php" enctype="multipart/form-data">
              <input type="hidden" name="modulo" value="admin">
              <input type="hidden" name="accion" value="editar_novedad">
              <input type="hidden" name="id_novedad" value="<?php echo htmlspecialchars($novedad['id']); ?>">

              <div class="mb-3">
                <label for="codigo_novedad" class="form-label fw-bold text-muted small">ID NOVEDAD</label>
                <input type="text" class="form-control bg-light" id="codigo_novedad"
                  value="<?php echo htmlspecialchars($novedad['id']); ?>" readonly disabled>
              </div>

              <div class="mb-3">
                <label for="titulo_novedad" class="form-label fw-bold">Título</label>
                <input type="text" class="form-control" id="titulo_novedad" name="titulo_novedad"
                  value="<?php echo htmlspecialchars($novedad['tituloNovedad']); ?>" required maxlength="100">
              </div>

              <div class="mb-3">
                <label for="texto_novedad" class="form-label fw-bold">Descripción</label>
                <textarea class="form-control" id="texto_novedad" name="texto_novedad" rows="4" required
                  maxlength="500"><?php echo htmlspecialchars($novedad['textoNovedad']); ?></textarea>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="fecha_desde" class="form-label fw-bold">Fecha Desde</label>
                  <input type="date" class="form-control" id="fecha_desde" name="fecha_desde"
                    value="<?php echo htmlspecialchars($novedad['fecha_desde']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="fecha_hasta" class="form-label fw-bold">Fecha Hasta</label>
                  <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta"
                    value="<?php echo htmlspecialchars($novedad['fecha_hasta']); ?>" required>
                </div>
              </div>

              <div class="mb-3">
                <label for="categoria" class="form-label fw-bold">Categoría</label>
                <select class="form-select" id="categoria" name="categoria" required>
                  <option value="" disabled>Seleccione una categoría</option>
                  <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo htmlspecialchars($categoria); ?>"
                      <?php echo ($categoria == $novedad['categoria']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($categoria); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3 p-3 border rounded bg-light text-center">
                <label class="form-label d-block fw-bold mb-3">Imagen Actual</label>
                <?php if (!empty($novedad['imagen'])): ?>
                  <img src="index.php?vista=imagen&novedad_id=<?php echo $novedad['id']; ?>"
                    alt="Imagen actual" class="img-thumbnail shadow-sm mx-auto d-block"
                    style="max-height: 150px; object-fit: cover;">
                <?php else: ?>
                  <p class="text-muted m-0 small">No hay imagen cargada</p>
                <?php endif; ?>
              </div>

              <div class="mb-4">
                <label for="imagen_novedad" class="form-label fw-bold">Nueva Imagen <span class="text-muted font-monospace" style="font-size: 0.8rem;">(Opcional)</span></label>
                <input type="file" class="form-control" id="imagen_novedad" name="imagen_novedad"
                  accept="image/png, image/jpeg, image/jpg">
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-gradient py-2 fw-bold">💾 Aplicar Cambios</button>
                <button type="button" class="btn btn-outline-secondary py-2"
                  onclick="window.location.href='index.php?vista=admin_novedades'">Cancelar</button>
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