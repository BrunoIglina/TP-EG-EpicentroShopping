<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include './private/functions_locales.php';
include './private/functions_usuarios.php';

$limit = 5; 
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$locales = get_all_locales($limit, $offset);
$total_locales = get_total_locales();
$total_pages = ceil($total_locales / $limit);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Administración de Locales</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        
        <main class="container-fluid">
            <section class="admin-section">
                <h2 class="text-center my-4">Administración de Locales</h2>

                <button class="btn btn-primary btn-sm mb-3 d-block mx-auto" onclick="location.href='agregar_local.php'">Agregar Local</button>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (!$locales) { ?>
                    <div class="alert alert-warning">No hay locales cargados</div>
                <?php } else { ?>

                    <div class="table-responsive-lg">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
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
                                                <img src="./private/visualizar_imagen.php?local_id=<?php echo $local['id']; ?>" alt="Imagen del local" class="img-fluid" style="max-width: 100px;">
                                            <?php } else { echo "No hay imagen"; } ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm mb-1" 
                                                onclick="window.location.href='./private/generarInforme.php?local_id=<?php echo $local['id']; ?>'">
                                                Generar PDF
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm mb-1" 
                                                onclick="confirmAction('edit', <?php echo $local['id']; ?>)">Modificar</button>
                                            <button type="button" class="btn btn-danger btn-sm mb-1" 
                                                onclick="confirmAction('delete', <?php echo $local['id']; ?>)">Eliminar</button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-container">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                            <li class="page-item <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
                            </li>
                        </ul>
                    </div>

                <?php } ?>
            </section>
        </main>
        
        <?php include './includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Acción</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea <span id="modalAction"></span> este local?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <form id="localesForm" method="POST" action="agregar_local.php">
        <input type="hidden" id="actionInput" name="action">
        <input type="hidden" id="localIdInput" name="local_id">
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function confirmAction(action, localId) {
            $('#confirmModal').modal('show');
            $('#modalAction').text(action === 'edit' ? 'modificar' : 'eliminar');

            $('#confirmActionBtn').off('click').on('click', function() {
                // Si es 'edit', redirige a la página de edición con el ID del local
                if (action === 'edit') {
                    window.location.href = './editar_local.php?id=' + localId;
                } 
                // Si es 'delete', redirige al archivo procesar_local.php para eliminar el local
                else if (action === 'delete') {
                    // Confirmación de eliminación solo una vez
                    $('#confirmModal').modal('hide');  // Cerrar modal antes de redirigir
                    window.location.href = './private/procesar_local.php?action=delete&local_id=' + localId;
                }
            });
        }
    </script>


</body>
</html>
