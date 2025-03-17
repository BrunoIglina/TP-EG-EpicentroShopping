<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include '../private/functions_locales.php';
include '../private/functions_usuarios.php';

$locales = get_all_locales();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_locales.css">
    <link rel="stylesheet" href="../css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="../assets/logo.png">
    <title>Epicentro Shopping - Administración de Locales</title>
</head>
<body>
    <div class="wrapper">
        <?php include '../includes/header.php'; ?>
        <main>
            <section class="admin-section">
                <h2>Administración de Locales</h2>
                <p>
                <button onclick="location.href='agregar_local.php'" class="btn btn-primary mb-3">Agregar Local</button>
                </p>

                <?php if (!$locales) { ?>
                    <p><b>No hay locales cargados.</b></p>
                <?php } else { ?>
                    <form id="localesForm" method="POST" action="../private/procesar_local.php">
                        <button type="submit" name="action" class="btn btn-secondary mb-3" value="toggle">
                            <?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? 'Deseleccionar Todo' : 'Seleccionar Todo'; ?>
                        </button>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Seleccionar</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Ubicación</th>
                                        <th>Rubro</th>
                                        <th>Email del dueño</th>
                                        <th>Imagen</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($locales as $local) { ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="locales[]" value="<?php echo $local['id']; ?>" 
                                                <?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? 'checked' : ''; ?>>
                                            </td>
                                            <td><?php echo htmlspecialchars($local['id']); ?></td>
                                            <td><?php echo htmlspecialchars($local['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($local['ubicacion']); ?></td>
                                            <td><?php echo htmlspecialchars($local['rubro']); ?></td>
                                            <td>
                                                <?php 
                                                    $dueño = get_dueño($local['idUsuario']);
                                                    echo htmlspecialchars($dueño['email']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($local['imagen'])) { ?>
                                                    <img src="../private/visualizar_imagen.php?local_id=<?php echo $local['id']; ?>" alt="Imagen del local">
                                                <?php } else { echo "No hay imagen"; } ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-info" 
                                                    onclick="window.location.href='../private/generarInforme.php?local_id=<?php echo $local['id']; ?>'">
                                                    Generar PDF
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" name="action" class="btn btn-success" value="edit">Modificar local</button>
                        <button type="submit" name="action" class="btn btn-danger" value="delete">Eliminar local</button>
                        <input type="hidden" name="select_all" value="<?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? '0' : '1'; ?>">
                    </form>
                <?php } ?>
            </section>
        </main>
        <?php include '../includes/footer.php'; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
