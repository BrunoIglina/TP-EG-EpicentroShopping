<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: login.php");
    exit();
}

// include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
include('./env/shopping_db.php');

$limit = 6; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 

$query = "SELECT id, email FROM usuarios WHERE tipo = 'Dueño' AND validado = 0 LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);


$total_query = "SELECT COUNT(*) AS total FROM usuarios WHERE tipo = 'Dueño' AND validado = 0";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_dueños = $total_row['total'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/admin_aprobar_dueños.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Aprobar Dueños de Locales</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        
        <main class="container">
        <h2 class="text-center my-4">Aprobar Dueños de Locales</h2>
            
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
                                    <form action="./private/dueños_pendientes_aprobacion.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <button type="submit" class="btn btn-success">Aprobar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No hay dueños pendientes de aprobación</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Controles de paginación -->
            <div class="pagination-container mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Botón "Anterior" -->
                    <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                    </li>
                    
                    <!-- Números de página -->
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Botón "Siguiente" -->
                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
                    </li>
                </ul>
            </div>
        </main>
        <?php include './includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Aprobación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea aprobar al dueño con email <span id="modalEmail"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmApprovalBtn">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <form id="approvalForm" method="POST" action="./private/dueños_pendientes_aprobacion.php">
        <input type="hidden" id="ownerId" name="id">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    function confirmApproval(ownerId, email) {
        $('#modalEmail').text(email);
        $('#confirmModal').modal('show');
        
        $('#confirmApprovalBtn').off('click').on('click', function() {
            $('#ownerId').val(ownerId);
            $('#approvalForm').submit();
        });
    }
    </script>
</body>
</html>
