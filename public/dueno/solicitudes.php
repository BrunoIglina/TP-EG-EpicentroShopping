<?php
// public/dueno/solicitudes.php

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="css/wrapper.css">
    <link rel="stylesheet" href="css/back_button.css">
    <link rel="stylesheet" href="css/fix_header.css">
    <title>Gestión de Promociones</title>
</head>

<body>
    <div class="wrapper">
        <?php include __DIR__ . '/../../includes/header.php'; ?>

        <div class="row align-items-center mb-5 mt-3">
            <div class="col-2 col-md-1 text-start">
                <?php include __DIR__ . '/../../includes/back_button.php'; ?>
            </div>

            <div class="col-8 col-md-10">
                <h2 class="text-center m-0 fw-bold text-uppercase" style="letter-spacing: 1px;">
                    Gestión de Solicitudes de Promociones
                </h2>
            </div>

            <div class="col-2 col-md-1"></div>
        </div>

        <div class="row">
            <?php if (!empty($solicitudes)): ?>
                <?php foreach ($solicitudes as $row): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row["local_nombre"]); ?></h5>
                                <p class="card-text"><strong><?php echo htmlspecialchars($row["textoPromo"]); ?></strong></p>
                                <p>Inicio: <?php echo htmlspecialchars($row["fecha_inicio"]); ?></p>
                                <p>Fin: <?php echo htmlspecialchars($row["fecha_fin"]); ?></p>
                                <p>Días: <?php echo htmlspecialchars(str_replace(',', ', ', $row["diasSemana"])); ?></p>
                                <p><strong>Estado:</strong> <?php echo htmlspecialchars($row["estado"]); ?></p>
                                <p>Email: <?php echo htmlspecialchars($row["email"]); ?></p>

                                <?php if ($row["estado"] == 'enviada'): ?>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmModal"
                                            data-promo-id="<?php echo $row["idPromocion"]; ?>"
                                            data-cliente-id="<?php echo $row["idCliente"]; ?>"
                                            data-accion="aceptar"
                                            data-email="<?php echo htmlspecialchars($row["email"]); ?>">
                                            Aceptar
                                        </button>
                                        <button type="button" class="btn btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmModal"
                                            data-promo-id="<?php echo $row["idPromocion"]; ?>"
                                            data-cliente-id="<?php echo $row["idCliente"]; ?>"
                                            data-accion="rechazar"
                                            data-email="<?php echo htmlspecialchars($row["email"]); ?>">
                                            Rechazar
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center my-4">No hay solicitudes pendientes.</p>
            <?php endif; ?>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?vista=dueno_solicitudes&page=<?php echo $page - 1; ?>">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="index.php?vista=dueno_solicitudes&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $page == $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?vista=dueno_solicitudes&page=<?php echo $page + 1; ?>">Siguiente</a>
                </li>
            </ul>
        </nav>
    </div>
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="actionForm" action="index.php" method="POST" style="display: inline;">
                        <input type="hidden" name="modulo" value="dueno">
                        <input type="hidden" name="accion" value="gestionar_solicitud">
                        <input type="hidden" name="promo_id" id="modalPromoId">
                        <input type="hidden" name="cliente_id" id="modalClienteId">
                        <input type="hidden" name="estado_nuevo" id="modalAccion">
                        <button type="submit" class="btn" id="modalConfirmBtn">Confirmar</button>
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
            document.getElementById('modalPromoId').value = button.getAttribute('data-promo-id');
            document.getElementById('modalClienteId').value = button.getAttribute('data-cliente-id');
            const accion = button.getAttribute('data-accion');
            document.getElementById('modalAccion').value = accion;

            const email = button.getAttribute('data-email');
            const confirmBtn = document.getElementById('modalConfirmBtn');
            const message = document.getElementById('modalMessage');

            if (accion === 'aceptar') {
                message.textContent = '¿Estás seguro de que deseas aceptar la solicitud de ' + email + '?';
                confirmBtn.className = 'btn btn-success';
                confirmBtn.textContent = 'Confirmar Aceptación';
            } else {
                message.textContent = '¿Estás seguro de que deseas rechazar la solicitud de ' + email + '?';
                confirmBtn.className = 'btn btn-danger';
                confirmBtn.textContent = 'Confirmar Rechazo';
            }
        });
    </script>
</body>

</html>