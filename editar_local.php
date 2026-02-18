<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

require_once './private/functions/functions_locales.php';
require_once './private/functions/functions_usuarios.php';
require_once './config/rubros.php';

if (isset($_GET['id'])) {
    $local = get_local($_GET['id']);
    $dueños = get_all_dueños();
} else {
    $_SESSION['error'] = "ID de local no proporcionado.";
    header("Location: admin_locales.php");
    exit();
}

if (!$local) {
    $_SESSION['error'] = "Local no encontrado.";
    header("Location: admin_locales.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/forms.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Modificar Local</title>
</head>
<body>
    <?php include './includes/header.php'; ?>
    
    <div class="form-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="form-card">
                        <h2>Modificar Local</h2>
                        
                        <form method="POST" action="./private/crud/locales.php" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="nombre_antiguo_local" value="<?php echo htmlspecialchars($local['nombre']); ?>">
                            
                            <div class="mb-3">
                                <label for="codigo_local" class="form-label">Código Local</label>
                                <input type="text" class="form-control" id="codigo_local" name="id_local" 
                                       value="<?php echo htmlspecialchars($local['id']); ?>" readonly disabled>
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
                                    <img src="./private/helpers/visualizar_imagen.php?local_id=<?php echo $local['id']; ?>" 
                                         alt="Imagen del local" class="preview-image d-block">
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
                                        onclick="window.location.href='admin_locales.php'">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include './includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>