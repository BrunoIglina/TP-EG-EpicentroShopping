<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>Validación de Cuentas de Clientes | Panel Administrativo</title>

    <link rel="icon" type="image/png" href="public/assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/footer.css">
    <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="public/css/admin.css">
    <link rel="stylesheet" href="public/css/back_button.css">
    <link rel="stylesheet" href="public/css/fix_header.css">
</head>

<body>
    <a href="#main-content" class="visually-hidden-focusable text-center d-block bg-dark text-white py-2">
        Saltar al contenido principal
    </a>

    <div class="wrapper">
        <?php include __DIR__ . '/../../includes/header.php'; ?>

        <main id="main-content" class="container-fluid py-4">
            <div class="row align-items-center mb-5 mt-3">
                <div class="col-2 col-md-1 text-start">
                    <?php include __DIR__ . '/../../includes/back_button.php'; ?>
                </div>

                <div class="col-8 col-md-10">
                    <h1 class="text-center m-0 fw-bold text-uppercase h2" style="letter-spacing: 1px;">
                        Gestión de Clientes
                    </h1>
                    <p class="text-center text-muted m-0 mt-1">Aprobación de nuevos registros</p>
                </div>

                <div class="col-2 col-md-1"></div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show text-center mb-4" role="alert">
                    <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <section class="admin-section px-lg-5">
                <form id="approvalForm" method="POST" action="index.php">
                    <input type="hidden" name="modulo" value="admin">
                    <input type="hidden" name="accion" value="estado_usuario">
                    <input type="hidden" name="estado" value="1"> 
                    <input type="hidden" name="tipo_usuario" value="cliente">

                    <div class="table-responsive rounded-4 shadow-sm">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th scope="col" class="py-3">Correo Electrónico</th>
                                    <th scope="col" class="py-3">Acción Administrativa</th>
                                </tr>
                            </thead>
                            <tbody class="text-center bg-white">
                                <?php if (!empty($clientes)): ?>
                                    <?php foreach ($clientes as $row): ?>
                                        <tr>
                                            <td class="fw-bold"><?= htmlspecialchars($row['email']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm px-4 fw-bold shadow-sm" 
                                                        data-bs-toggle="modal" data-bs-target="#confirmModal"
                                                        data-id="<?= $row['id']; ?>"
                                                        data-email="<?= htmlspecialchars($row['email']); ?>"
                                                        aria-label="Aprobar cliente <?= htmlspecialchars($row['email']); ?>">
                                                    APROBAR CUENTA
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="py-5 text-muted fs-5">
                                            <i class="bi bi-person-check"></i> No hay clientes pendientes de aprobación.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </form>

                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Navegación de páginas de clientes" class="mt-5">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($page == 1) ? 'disabled' : ''; ?>">
                                <a class="page-link shadow-sm" href="index.php?vista=admin_aprobar_clientes&page=<?= $page - 1; ?>" <?= ($page == 1) ? 'aria-disabled="true"' : ''; ?>>Anterior</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link shadow-sm" href="index.php?vista=admin_aprobar_clientes&page=<?= $i; ?>" <?= ($i == $page) ? 'aria-current="page"' : ''; ?>><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link shadow-sm" href="index.php?vista=admin_aprobar_clientes&page=<?= $page + 1; ?>" <?= ($page >= $total_pages) ? 'aria-disabled="true"' : ''; ?>>Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </section>
        </main>

        <?php include __DIR__ . '/../../includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h2 class="modal-title h5" id="confirmModalLabel">Confirmar Aprobación</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="fs-5 m-0">¿Está seguro de que desea aprobar al cliente:</p>
                    <p class="fw-bold text-primary mt-2" id="modalEmail"></p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="button" class="btn btn-success px-4 fw-bold shadow-sm" id="confirmActionBtn">CONFIRMAR</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedId = null;
        $(document).ready(function() {
            $('#confirmModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                selectedId = button.data('id');
                $('#modalEmail').text(button.data('email'));
            });

            $('#confirmActionBtn').on('click', function() {
                if (selectedId) {
                    let form = $('#approvalForm');
                    form.find('input[name="usuario_id"]').remove();
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'usuario_id',
                        value: selectedId
                    }).appendTo(form);
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>