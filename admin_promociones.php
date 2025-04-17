<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include './env/shopping_db.php';

$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT id, textoPromo, fecha_inicio, fecha_fin FROM promociones WHERE estadoPromo = 'Pendiente' LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$total_promociones_sql = "SELECT COUNT(*) AS total FROM promociones WHERE estadoPromo = 'Pendiente'";
$total_result = $conn->query($total_promociones_sql);
$total_row = $total_result->fetch_assoc();
$total_promociones = $total_row['total'];
$total_pages = ceil($total_promociones / $limit);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/admin_promociones.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Administración de Promociones</title>
    <style>
        .table-container {
            width: 100%;
            overflow-x: auto;
        }
        table {
            min-width: 800px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>

        <main class="container-fluid">
            <section class="admin-section">
                <h2 class="text-center my-4">Aprobar promociones pendientes</h2>
                <div class="table-container">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Texto de la Promoción</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['textoPromo'] . "</td>";
                                    echo "<td>" . $row['fecha_inicio'] . "</td>";
                                    echo "<td>" . $row['fecha_fin'] . "</td>";
                                    echo "<td>
                                        <button type='button' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#confirmModal' data-id='" . $row['id'] . "' data-action='aprobar'>Aprobar</button>
                                        <button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#confirmModal' data-id='" . $row['id'] . "' data-action='rechazar'>Rechazar</button>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No hay promociones pendientes</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Controles de paginación -->
                <div class="pagination-container mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php } ?>
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
                        </li>
                    </ul>
                </div>
            </section>
        </main>
        <?php include './includes/footer.php'; ?>
    </div>

    <form id="actionForm" method="POST" action="./private/controAcepPromo.php"></form>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea <span id="modalAction"></span> esta promoción?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#confirmModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var promocionId = button.data('id');
                var action = button.data('action');
                var modal = $(this);

                modal.find('#modalAction').text(action === 'aprobar' ? 'aprobar' : 'rechazar');

                $('#confirmActionBtn').off('click').on('click', function() {
                    var form = $('#actionForm');
                    form.empty();
                    var input = $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', action)
                        .val(promocionId);
                    form.append(input);
                    form.submit();
                });
            });
        });
    </script>
</body>
</html>
