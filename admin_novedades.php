<?php
require_once './includes/navigation_history.php';
require_once './includes/security_headers.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

require_once './private/functions/functions_novedades.php';

$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$novedades = get_all_novedades($limit, $offset);
$total_novedades = get_total_novedades();
$total_pages = ceil($total_novedades / $limit);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/fix_header.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/buttons.css">
    <title>Epicentro Shopping - Administración de Novedades</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <?php include './includes/back_button.php'; ?>
        
        <main class="container-fluid">
            <section class="admin-section">
                <h2 class="text-center my-4">Administración de Novedades</h2>

                <button class="btn btn-primary btn-sm mb-3 d-block mx-auto" onclick="location.href='agregar_novedad.php'">
                    ➕ Agregar Novedad
                </button>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!$novedades): ?>
                    <div class="alert alert-warning">No hay novedades cargadas</div>
                <?php else: ?>
                    <div class="table-responsive-lg">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Código</th>
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
                                <?php foreach ($novedades as $novedad): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($novedad['id']); ?></td>
                                        <td><?php echo htmlspecialchars($novedad['tituloNovedad']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($novedad['textoNovedad'], 0, 100)) . '...'; ?></td>
                                        <td><?php echo htmlspecialchars($novedad['fecha_desde']); ?></td>
                                        <td><?php echo htmlspecialchars($novedad['fecha_hasta']); ?></td>
                                        <td>
                                            <?php if (!empty($novedad['imagen'])): ?>
                                                <img src="./private/helpers/visualizar_imagen.php?novedad_id=<?php echo $novedad['id']; ?>" 
                                                     alt="Imagen de la novedad" 
                                                     class="img-fluid" 
                                                     style="max-width: 100px;">
                                            <?php else: ?>
                                                Sin imagen
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($novedad['categoria']); ?></td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-warning btn-sm mb-1" 
                                                    onclick="window.location.href='editar_novedad.php?id=<?php echo $novedad['id']; ?>'">
                                                 Modificar
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm mb-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#confirmModal"
                                                    data-id="<?php echo $novedad['id']; ?>"
                                                    data-titulo="<?php echo htmlspecialchars($novedad['tituloNovedad']); ?>">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </section>
        </main>
        
        <?php include './includes/footer.php'; ?>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea eliminar la novedad <strong id="modalTitulo"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" action="./private/crud/novedades.php" method="post" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="novedad_id" id="modalNovedadId">
                        <button type="submit" class="btn btn-danger">Confirmar Eliminación</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const confirmModal = document.getElementById('confirmModal');
        confirmModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const titulo = button.getAttribute('data-titulo');
            
            document.getElementById('modalNovedadId').value = id;
            document.getElementById('modalTitulo').textContent = titulo;
        });
    </script>
</body>
</html>