<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>Gestión de Mis Promociones | Epicentro Shopping</title>

    <link rel="icon" type="image/png" href="public/assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="public/css/fix_header.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/footer.css">
    <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="public/css/back_button.css">
    <link rel="stylesheet" href="public/css/buttons.css">
</head>

<body>
    <a href="#main-content" class="visually-hidden-focusable text-center d-block bg-dark text-white py-2">
        Saltar al contenido principal
    </a>

    <div class="wrapper">
        <?php include __DIR__ . '/../../includes/header.php'; ?>

        <main id="main-content" class="container-fluid px-4 py-4">
            <div class="row align-items-center mb-5 mt-3">
                <div class="col-2 col-md-1 text-start">
                    <?php include __DIR__ . '/../../includes/back_button.php'; ?>
                </div>

                <div class="col-8 col-md-10">
                    <h1 class="text-center m-0 fw-bold text-uppercase h2" style="letter-spacing: 1px;">
                        Mis Promociones
                    </h1>
                </div>

                <div class="col-2 col-md-1"></div>
            </div>

            <section class="admin-section">
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <button class="btn btn-primary px-4 shadow-sm" onclick="location.href='index.php?vista=dueno_promocion_agregar'" aria-label="Agregar nueva promoción">
                        <i class="bi bi-plus-circle"></i> Agregar Promoción
                    </button>
                    <button class="btn btn-secondary px-4 shadow-sm" onclick="location.href='index.php?vista=dueno_reportes'" aria-label="Ver reportes de promociones">
                        <i class="bi bi-graph-up"></i> Ver Reportes
                    </button>
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['success']);
                        unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <?php if (empty($promociones)): ?>
                    <div class="alert alert-warning text-center border-0 shadow-sm py-4">
                        <h3 class="h5 m-0">No hay promociones registradas en este momento.</h3>
                    </div>
                <?php else: ?>
                    <div class="table-responsive rounded-4 shadow-sm">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th scope="col">Texto Promoción</th>
                                    <th scope="col">Vigencia</th>
                                    <th scope="col">Días</th>
                                    <th scope="col">Categoría</th>
                                    <th scope="col">Local</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Usos</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-center bg-white">
                                <?php foreach ($promociones as $row): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($row['textoPromo']) ?></td>
                                        <td class="small">
                                            <?= htmlspecialchars($row['fecha_inicio']) ?><br>al<br><?= htmlspecialchars($row['fecha_fin']) ?>
                                        </td>
                                        <td class="small text-muted"><?= htmlspecialchars($row['diasSemana']) ?></td>
                                        <td>
                                            <span class="badge bg-primary px-3 py-2">
                                                <?= htmlspecialchars($row['categoriaCliente']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($row['local_nombre']) ?></td>
                                        <td>
                                            <?php
                                            $estadoClass = match ($row['estadoPromo']) {
                                                'Aprobada' => 'bg-success',
                                                'Pendiente' => 'bg-warning text-dark',
                                                'Denegada' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $estadoClass ?> px-3 py-2">
                                                <?= htmlspecialchars($row['estadoPromo']) ?>
                                            </span>
                                        </td>
                                        <td class="fw-bold fs-5"><?= htmlspecialchars($row['totalPromos']) ?></td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm px-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmModal"
                                                data-id="<?= $row['id'] ?>"
                                                data-texto="<?= htmlspecialchars($row['textoPromo']) ?>"
                                                aria-label="Eliminar promoción <?= htmlspecialchars($row['textoPromo']) ?>">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <nav aria-label="Navegación de páginas de promociones" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="index.php?vista=dueno_promociones&page=<?= $page - 1 ?>" <?= ($page <= 1) ? 'aria-disabled="true"' : '' ?>>Anterior</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="index.php?vista=dueno_promociones&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                <a class="page-link" href="index.php?vista=dueno_promociones&page=<?= $page + 1 ?>" <?= ($page >= $total_pages) ? 'aria-disabled="true"' : '' ?>>Siguiente</a>
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
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h2 class="modal-title h5" id="confirmModalLabel">Confirmar Eliminación</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="fs-5">¿Está seguro de que desea eliminar esta promoción?</p>
                    <div class="alert alert-secondary mt-3">
                        <strong id="modalTexto"></strong>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" action="index.php" method="POST" style="display: inline;">
                        <input type="hidden" name="modulo" value="dueno">
                        <input type="hidden" name="accion" value="eliminar_promo">
                        <input type="hidden" name="promo_id" id="modalPromoId">
                        <button type="submit" class="btn btn-danger px-4 fw-bold">Confirmar Eliminación</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const confirmModal = document.getElementById('confirmModal');
        confirmModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const texto = button.getAttribute('data-texto');

            document.getElementById('modalPromoId').value = id;
            document.getElementById('modalTexto').textContent = texto;
        });
    </script>
</body>

</html>