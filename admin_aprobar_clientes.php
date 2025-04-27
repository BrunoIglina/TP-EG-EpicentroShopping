<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: login.php");
    exit();
}

include('./env/shopping_db.php');

$limit = 6; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 

$query = "SELECT id, email FROM usuarios WHERE tipo = 'Cliente' AND validado = 0 LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

$total_query = "SELECT COUNT(*) AS total FROM usuarios WHERE tipo = 'Cliente' AND validado = 0";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_clientes = $total_row['total'];
$total_pages = ceil($total_clientes / $limit);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Aprobar Clientes</title>
</head>
<body>
    <div class="wrapper">
    <?php include './includes/header.php'; ?>
        
        <main class="container-fluid">
            <h2 class="text-center my-4">Aprobar Clientes</h2>
            
            <form id="approvalForm" method="POST" action="./private/aceptar_clientes.php">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="<?php echo $row['id']; ?>" data-email="<?php echo $row['email']; ?>">Aprobar</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No hay clientes pendientes de aprobación</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>

            <!-- Paginación -->
            <div class="pagination-container mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
                    </li>
                </ul>
            </div>
        </main>

    <?php include './includes/footer.php'; ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Aprobación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea aprobar al cliente con email <span id="modalEmail"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedId = null;
        $(document).ready(function() {
            $('#confirmModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                selectedId = button.data('id');
                let email = button.data('email');
                $('#modalEmail').text(email);
            });

            $('#confirmActionBtn').on('click', function () {
                if (selectedId) {
                    let form = $('#approvalForm');
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'id',
                        value: selectedId
                    }).appendTo(form);
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
