<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>Modificar Local: <?= htmlspecialchars($local['nombre']) ?> | Epicentro Shopping</title>

    <link rel="icon" type="image/png" href="public/assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/footer.css">
    <link rel="stylesheet" href="public/css/forms.css">
    <link rel="stylesheet" href="public/css/back_button.css">
    <link rel="stylesheet" href="public/css/fix_header.css">
    <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">
</head>

<body>
    <a href="#main-content" class="visually-hidden-focusable text-center d-block bg-dark text-white py-2">
        Saltar al contenido principal
    </a>

    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main id="main-content" class="form py-4">
        <div class="container">
            <div class="row align-items-center mb-5 mt-3">
                <div class="col-2 col-md-1 text-start">
                    <?php include __DIR__ . '/../../includes/back_button.php'; ?>
                </div>

                <div class="col-8 col-md-10">
                    <h1 class="text-center m-0 fw-bold text-uppercase h2" style="letter-spacing: 1px;">
                        Modificar Local
                    </h1>
                </div>

                <div class="col-2 col-md-1"></div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="form-card shadow-lg p-4 rounded-4 bg-white">

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php" enctype="multipart/form-data">
                            <input type="hidden" name="modulo" value="admin">
                            <input type="hidden" name="accion" value="editar_local">
                            <input type="hidden" name="id_local" value="<?= $local['id']; ?>">
                            <input type="hidden" name="nombre_antiguo_local" value="<?= htmlspecialchars($local['nombre']); ?>">

                            <div class="mb-3">
                                <label for="codigo_local_display" class="form-label fw-bold">Código Local</label>
                                <input type="text" id="codigo_local_display" class="form-control bg-light" 
                                       value="<?= $local['id']; ?>" disabled aria-disabled="true">
                            </div>

                            <div class="mb-3">
                                <label for="nombre_local" class="form-label fw-bold">Nombre del comercio</label>
                                <input type="text" class="form-control" id="nombre_local" name="nombre_local"
                                       value="<?= htmlspecialchars($local['nombre']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="ubicacion_local" class="form-label fw-bold">Ubicación</label>
                                <input type="text" class="form-control" id="ubicacion_local" name="ubicacion_local"
                                       value="<?= htmlspecialchars($local['ubicacion']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="rubro_local" class="form-label fw-bold">Rubro</label>
                                <select class="form-select" id="rubro_local" name="rubro_local" required>
                                    <?php foreach ($rubros as $label => $value): ?>
                                        <option value="<?= htmlspecialchars($value); ?>"
                                            <?= ($value == $local['rubro']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="id_dueno_select" class="form-label fw-bold">Email Dueño</label>
                                <select class="form-select" id="id_dueno_select" name="id_dueño" required>
                                    <?php foreach ($dueños as $dueño): ?>
                                        <option value="<?= htmlspecialchars($dueño['id']); ?>"
                                            <?= ($dueño['id'] == $local['idUsuario']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($dueño['email']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <p class="form-label fw-bold">Imagen Actual</p>
                                <?php if (!empty($local['imagen'])): ?>
                                    <div class="text-center p-2 border rounded bg-light">
                                        <img src="index.php?vista=imagen&local_id=<?= $local['id']; ?>" 
                                             alt="Vista previa actual del local <?= htmlspecialchars($local['nombre']); ?>"
                                             class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small text-center">No hay imagen registrada.</p>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="imagen_local" class="form-label fw-bold">Nueva Imagen (Opcional)</label>
                                <input type="file" class="form-control" id="imagen_local" name="imagen_local"
                                       accept="image/png, image/jpeg, image/jpg">
                                <div class="form-text">Si no selecciona una, se mantendrá la imagen anterior.</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm">APLICAR CAMBIOS</button>
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="window.location.href='index.php?vista=admin_locales'">
                                    Cancelar y Volver
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>