<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    
    <title>Modificar Local: <?php echo htmlspecialchars($local['nombre']); ?> | Epicentro Shopping</title>
    
    <link rel="icon" type="image/png" href="./public/assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="./public/css/header.css">
    <link rel="stylesheet" href="./public/css/footer.css">
    <link rel="stylesheet" href="./public/css/forms.css">
    <link rel="stylesheet" href="./public/css/back_button.css">
    <link rel="stylesheet" href="./public/css/fix_header.css">
    <link rel="stylesheet" href="./public/css/styles_fondo_and_titles.css">
</head>

<body>
    <div class="wrapper">
        <?php include __DIR__ . '/../../includes/header.php'; ?>

        <main id="main-content" class="form py-4 container-fluid">
            <div class="container">
                <div class="row align-items-center mb-5 mt-3">
                    <div class="col-2 col-md-1 text-start">
                        <?php include __DIR__ . '/../../includes/back_button.php'; ?>
                    </div>
                    <div class="col-8 col-md-10">
                        <h2 class="text-center m-0 fw-bold text-uppercase" style="letter-spacing: 1px;">Modificar Local</h2>
                    </div>
                    <div class="col-2 col-md-1"></div>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_SESSION['error']);
                        unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <div class="form-card shadow-lg p-4 rounded-4 bg-white">
                            
                            <form method="POST" action="index.php" enctype="multipart/form-data">
                                <input type="hidden" name="modulo" value="admin">
                                <input type="hidden" name="accion" value="editar_local">
                                
                                <input type="hidden" name="id_local" value="<?php echo $local['id']; ?>">
                                <input type="hidden" name="nombre_antiguo_local" value="<?php echo htmlspecialchars($local['nombre']); ?>">

                                <div class="mb-3">
                                    <label class="form-label">Código Local</label>
                                    <input type="text" class="form-control" value="<?php echo $local['id']; ?>" disabled>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nombre_local" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre_local" name="nombre_local" value="<?php echo htmlspecialchars($local['nombre']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="ubicacion_local" class="form-label">Ubicación</label>
                                    <input type="text" class="form-control" id="ubicacion_local" name="ubicacion_local" value="<?php echo htmlspecialchars($local['ubicacion']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="rubro_local" class="form-label">Rubro</label>
                                    <select class="form-select" id="rubro_local" name="rubro_local" required>
                                        <option value="" disabled>Seleccione un rubro</option>
                                        <?php 
                                        $rubros = ['Ropa', 'Electrónica', 'Joyería', 'Calzado', 'Librería', 'Alimentos', 'Bebidas', 'Farmacia', 'Deportes', 'Muebles', 'Hogar', 'Automóviles', 'Belleza', 'Viajes', 'Otros'];
                                        foreach ($rubros as $rubro) {
                                            $selected = ($local['rubro'] === $rubro) ? 'selected' : '';
                                            echo "<option value=\"$rubro\" $selected>$rubro</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="id_dueno_select" class="form-label fw-bold">Email Dueño</label>
                                    <select class="form-select" id="id_dueno_select" name="id_dueño" required>
                                        <option value="" disabled>Seleccione un dueño</option>
                                        <?php 
                                        if (!empty($dueños)) {
                                            foreach ($dueños as $dueno) {
                                                $selected = ($local['idUsuario'] == $dueno['id']) ? 'selected' : '';
                                                echo "<option value=\"{$dueno['id']}\" $selected>" . htmlspecialchars($dueno['email']) . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <p class="form-label fw-bold">Imagen Actual</p>
                                    <div class="text-center p-2 border rounded bg-light">
                                        <img src="index.php?vista=imagen&local_id=<?php echo $local['id']; ?>" alt="Vista previa actual del local <?php echo htmlspecialchars($local['nombre']); ?>" class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="imagen_local" class="form-label fw-bold">Nueva Imagen (Opcional)</label>
                                    <input type="file" class="form-control" id="imagen_local" name="imagen_local" accept="image/png, image/jpeg, image/jpg">
                                    <div class="form-text">Si no selecciona una, se mantendrá la imagen anterior.</div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm">APLICAR CAMBIOS</button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='index.php?vista=admin_locales'">Cancelar y Volver</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php include __DIR__ . '/../../includes/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>