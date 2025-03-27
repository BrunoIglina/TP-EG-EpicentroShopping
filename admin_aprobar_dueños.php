<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: login.php");
    exit();
}
// include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
include('./env/shopping_db.php');

$query = "SELECT * FROM usuarios WHERE tipo = 'Dueño' and validado = 0";
$result = mysqli_query($conn, $query);
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
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <button type="button" class="btn btn-success" onclick="confirmApproval(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['email']); ?>')">Aprobar</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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
