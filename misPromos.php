<?php
require_once './includes/navigation_history.php';
require_once './includes/security_headers.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
    header("Location: index.php");
    exit();
}

require_once './config/database.php';
$conn = getDB();
$user_id = $_SESSION['user_id'];

$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$stmt = $conn->prepare("SELECT p.id, p.textoPromo, p.fecha_inicio, p.fecha_fin, p.diasSemana, p.categoriaCliente, p.local_id, p.estadoPromo,
               (SELECT COUNT(*) FROM promociones_cliente pc WHERE pc.idPromocion = p.id AND pc.estado = 'aceptada') AS totalPromos
        FROM promociones p
        WHERE p.local_id IN (SELECT id FROM locales WHERE idUsuario = ?)
        LIMIT ? OFFSET ?");

$stmt->bind_param("iii", $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$total_stmt = $conn->prepare("SELECT COUNT(*) AS total
                     FROM promociones
                     WHERE local_id IN (SELECT id FROM locales WHERE idUsuario = ?)");
$total_stmt->bind_param("i", $user_id);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$stmt->close();
$total_stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <link rel="stylesheet" href="./css/fix_header.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/buttons.css">

    <title>Epicentro Shopping - Mis Promociones</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <?php include './includes/back_button.php'; ?>
        
        <main class="container-fluid">
            <section class="admin-section">
                <h2 class="text-center my-4">Mis Promociones</h2>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <button class="btn btn-primary btn-sm" onclick="location.href='darAltaPromos.php'">
                        Agregar Promoción
                    </button>
                    <button class="btn btn-secondary btn-sm" onclick="location.href='reportesDueño.php'">
                         Ver Reportes
                    </button>
                </div>

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

                <?php if ($result->num_rows == 0): ?>
                    <div class="alert alert-warning">No hay promociones registradas</div>
                <?php else: ?>
                    <div class="table-responsive-lg">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Texto Promoción</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Días Semana</th>
                                    <th>Categoría</th>
                                    <th>Local ID</th>
                                    <th>Estado</th>
                                    <th>Usos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['textoPromo']); ?></td>
                                        <td><?php echo htmlspecialchars($row['fecha_inicio']); ?></td>
                                        <td><?php echo htmlspecialchars($row['fecha_fin']); ?></td>
                                        <td><?php echo htmlspecialchars($row['diasSemana']); ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo htmlspecialchars($row['categoriaCliente']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['local_id']); ?></td>
                                        <td>
                                            <?php 
                                            $estadoClass = '';
                                            switch($row['estadoPromo']) {
                                                case 'Aprobada':
                                                    $estadoClass = 'bg-success';
                                                    break;
                                                case 'Pendiente':
                                                    $estadoClass = 'bg-warning';
                                                    break;
                                                case 'Denegada':
                                                    $estadoClass = 'bg-danger';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?php echo $estadoClass; ?>">
                                                <?php echo htmlspecialchars($row['estadoPromo']); ?>
                                            </span>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($row['totalPromos']); ?></strong></td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#confirmModal"
                                                    data-id="<?php echo $row['id']; ?>"
                                                    data-texto="<?php echo htmlspecialchars($row['textoPromo']); ?>">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                            </li>
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </section>
        </main>
        
        <?php include './includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea eliminar la promoción?
                    <div class="alert alert-info mt-3">
                        <strong id="modalTexto"></strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" action="./private/crud/promociones.php" method="post" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="promo_id" id="modalPromoId">
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
            const texto = button.getAttribute('data-texto');
            
            document.getElementById('modalPromoId').value = id;
            document.getElementById('modalTexto').textContent = texto;
        });
    </script>
</body>
</html>