<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include './private/functions_novedades.php';

$limit = 6;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$novedades = get_all_novedades($limit, $offset);
$total_novedades = count(get_all_novedades()); 
$total_pages = ceil($total_novedades / $limit);
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
    <title>Epicentro Shopping - Administración de Novedades</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        
        <main class="container">
            <section class="admin-section">
                <h2 class="text-center my-4">Administración de Novedades</h2>

                <button class="btn btn-primary btn-limited mb-3" onclick="location.href='agregar_novedad.php'">Agregar novedad</button>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (!$novedades) { ?>
                    <div class="alert alert-warning">No hay novedades cargadas</div>
                <?php } else { ?>

                    <div class="table-responsive-lg">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Código novedad</th>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th>Fecha desde</th>
                                    <th>Fecha hasta</th>
                                    <th>Imagen</th>
                                    <th>Categoría</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($novedades as $novedad) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($novedad['id']); ?></td>
                                        <td><?php echo htmlspecialchars($novedad['tituloNovedad']); ?></td>
                                        <td><?php echo htmlspecialchars($novedad['textoNovedad']); ?></td>
                                        <td><?php echo htmlspecialchars($novedad['fecha_desde']); ?></td>
                                        <td><?php echo htmlspecialchars($novedad['fecha_hasta']); ?></td>
                                        <td>
                                            <?php if (!empty($novedad['imagen'])) { ?>
                                                <img src="./private/visualizar_imagen.php?novedad_id=<?php echo $novedad['id']; ?>" alt="Imagen de la novedad" class="img-fluid" style="max-width: 100px;">
                                            <?php } else { echo "No hay imagen"; } ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($novedad['categoria']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm mb-1" onclick="confirmAction('edit', <?php echo $novedad['id']; ?>)">Modificar</button>
                                            <button type="button" class="btn btn-danger btn-sm mb-1" onclick="confirmAction('delete', <?php echo $novedad['id']; ?>)">Eliminar</button>
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
                    ¿Está seguro de que desea <span id="modalAction"></span> esta novedad?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <form id="novedadesForm" method="POST" action="./private/procesar_novedad.php">
        <input type="hidden" id="actionInput" name="action">
        <input type="hidden" id="novedadIdInput" name="novedad_id">
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function confirmAction(action, novedadId) {
            $('#confirmModal').modal('show');
            $('#modalAction').text(action === 'edit' ? 'modificar' : 'eliminar');

            $('#confirmActionBtn').off('click').on('click', function() {
                var form = $('#novedadesForm');
                var inputAction = $('#actionInput');
                var inputNovedadId = $('#novedadIdInput');
                
                inputAction.val(action);
                inputNovedadId.val(novedadId);
                
                form.submit();
            });
        }
    </script>
</body>
</html>
