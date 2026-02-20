<?php
require_once './includes/navigation_history.php';
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
    header("Location: login.php");
    exit();
}
$usuario_id = $_SESSION['user_id'];

require_once './config/database.php';
$conn = getDB();

$items_per_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$stmt_total = $conn->prepare("
    SELECT COUNT(*) as total
    FROM promociones_cliente 
    INNER JOIN promociones ON promociones_cliente.idPromocion = promociones.id 
    INNER JOIN locales ON promociones.local_id = locales.id 
    INNER JOIN usuarios ON promociones_cliente.idCliente = usuarios.id  
    WHERE locales.idUsuario = ?
");
$stmt_total->bind_param("i", $usuario_id);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_row = $result_total->fetch_assoc();
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $items_per_page);
$stmt_total->close();

$stmt = $conn->prepare("
    SELECT 
        promociones_cliente.idPromocion,
        promociones_cliente.estado,
        promociones.textoPromo, 
        promociones.fecha_inicio, 
        promociones.fecha_fin,
        promociones.diasSemana,
        locales.nombre AS local_nombre,
        usuarios.id AS idCliente,
        usuarios.email AS email
    FROM promociones_cliente 
    INNER JOIN promociones ON promociones_cliente.idPromocion = promociones.id 
    INNER JOIN locales ON promociones.local_id = locales.id 
    INNER JOIN usuarios ON promociones_cliente.idCliente = usuarios.id  
    WHERE locales.idUsuario = ?
    LIMIT ? OFFSET ?
");
$stmt->bind_param("iii", $usuario_id, $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./css/wrapper.css"> 
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/fix_header.css">
    <title>Gestión de Promociones</title>
</head>
<body>
    <div class="wrapper">
            <?php include './includes/header.php'; ?>
        
        <div class="container my-4">
                <?php include './includes/back_button.php'; ?> 
            <h2 class="text-center my-4">Gestión de Solicitudes de Promociones</h2>
            
            <div class="row">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
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
                                        <div class="d-flex justify-content-between">
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
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center my-4">No hay promociones disponibles.</p>
                <?php endif; ?>
                <?php $stmt->close(); ?>
            </div>

            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo $page == $total_pages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php include './includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="actionForm" action="./private/crud/promociones.php" method="post" style="display: inline;">
                        <input type="hidden" name="action" value="gestionar_solicitud">
                        <input type="hidden" name="promo_id" id="modalPromoId">
                        <input type="hidden" name="cliente_id" id="modalClienteId">
                        <input type="hidden" name="accion" id="modalAccion">
                        <button type="submit" class="btn" id="modalConfirmBtn">Confirmar</button>
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
            const promoId = button.getAttribute('data-promo-id');
            const clienteId = button.getAttribute('data-cliente-id');
            const accion = button.getAttribute('data-accion');
            const email = button.getAttribute('data-email');
            
            document.getElementById('modalPromoId').value = promoId;
            document.getElementById('modalClienteId').value = clienteId;
            document.getElementById('modalAccion').value = accion;
            
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