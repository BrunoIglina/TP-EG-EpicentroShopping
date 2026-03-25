<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="public/assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./public/css/footer.css">
    <link rel="stylesheet" href="./public/css/header.css">
    <link rel="stylesheet" href="./public/css/admin_aprobar_dueños.css">
    <link rel="stylesheet" href="./public/css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./public/css/back_button.css">
    <link rel="stylesheet" href="./public/css/fix_header.css">
    <title>Epicentro Shopping - Aprobar Dueños de Locales</title>
</head>

<body>
    <a href="#main-content" class="visually-hidden-focusable text-center d-block bg-dark text-white py-2">
        Saltar al contenido principal
    </a>

    <div class="wrapper">
        <?php include __DIR__ . '/../../includes/header.php'; ?>
        <main id="main-content" class="container">
            <div class="row align-items-center mb-5 mt-3">
                <div class="col-2 col-md-1 text-start">
                    <?php include __DIR__ . '/../../includes/back_button.php'; ?>
                </div>

                <div class="col-8 col-md-10">
                    <h1 class="text-center m-0 fw-bold text-uppercase h2" style="letter-spacing: 1px;">
                        Gestión de Dueños de Locales
                    </h1>
                </div>

                <div class="col-2 col-md-1"></div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success text-center">
                    <?php echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Email</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($duenos)): ?>
                        <?php foreach ($duenos as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal"
                                        data-id="<?php echo htmlspecialchars($row['id']); ?>"
                                        data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                        aria-label="Aprobar dueño <?php echo htmlspecialchars($row['email']); ?>">Aprobar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">No hay dueños pendientes de aprobación</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="pagination-container mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="index.php?vista=admin_aprobar_duenos&page=<?php echo $page - 1; ?>">Anterior</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link"
                                href="index.php?vista=admin_aprobar_duenos&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="index.php?vista=admin_aprobar_duenos&page=<?php echo $page + 1; ?>">Siguiente</a>
                    </li>
                </ul>
            </div>
        </main>
        <?php include __DIR__ . '/../../includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="confirmModalLabel">Confirmar Aprobación</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas aprobar al dueño <strong id="modalEmail"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="approveForm" action="index.php" method="post" style="display: inline;">
                        <input type="hidden" name="modulo" value="admin">
                        <input type="hidden" name="accion" value="estado_usuario">
                        <input type="hidden" name="estado" value="1">
                        <input type="hidden" name="tipo_usuario" value="dueno">
                        <input type="hidden" name="usuario_id" id="modalId">
                        <button type="submit" class="btn btn-success">Confirmar Aprobación</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('confirmModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('modalId').value = button.getAttribute('data-id');
            document.getElementById('modalEmail').textContent = button.getAttribute('data-email');
        });
    </script>
</body>

</html>