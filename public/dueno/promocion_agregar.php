<?php
// public/dueno/promocion_agregar.php
require_once __DIR__ . '/../../private/logic/functions/functions_dueno.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
    header("Location: index.php");
    exit();
}

// Traemos los locales del dueño para el select
$locales = get_locales_por_dueno($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/fix_header.css">
    <title>Epicentro Shopping - Agregar Promoción</title>
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    
    <div class="form-wrapper mt-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-card p-4 shadow bg-white rounded">
                        <h2 class="text-center mb-4">Agregar Nueva Promoción</h2>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="index.php" method="POST">
                            <input type="hidden" name="modulo" value="dueno">
                            <input type="hidden" name="accion" value="crear_promocion">
                            
                            <div class="mb-3">
                                <label for="local_id" class="form-label">Nombre del Local</label>
                                <select id="local_id" name="local_id" class="form-select" required>
                                    <option value="">Seleccione un local</option>
                                    <?php foreach ($locales as $local): ?>
                                        <option value="<?php echo htmlspecialchars($local['id']); ?>">
                                            <?php echo htmlspecialchars($local['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="textoPromo" class="form-label">Texto de la Promoción</label>
                                <textarea id="textoPromo" name="textoPromo" class="form-control" rows="3" required maxlength="200"></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label d-block">Días de la Semana</label>
                                <div class="row">
                                    <?php 
                                    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                    foreach ($dias as $dia): ?>
                                        <div class="col-6 col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="diasSemana[]" value="<?php echo $dia; ?>" id="check<?php echo $dia; ?>">
                                                <label class="form-check-label" for="check<?php echo $dia; ?>"><?php echo $dia; ?></label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="categoriaCliente" class="form-label">Categoría de Cliente</label>
                                <select id="categoriaCliente" name="categoriaCliente" class="form-select" required>
                                    <option value="">Seleccione una categoría</option>
                                    <option value="Inicial">Inicial</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Premium">Premium</option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Agregar Promoción</button>
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php?vista=dueno_promociones'">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>