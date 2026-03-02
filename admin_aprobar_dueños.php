<?php
require_once './includes/navigation_history.php';
require_once './includes/security_headers.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: login.php");
    exit();
}

require_once './config/database.php';
$conn = getDB();

$limit = 6; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 

$stmt = $conn->prepare("SELECT id, email FROM usuarios WHERE tipo = 'Dueno' AND validado = 0 LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$stmt_total = $conn->prepare("SELECT COUNT(*) AS total FROM usuarios WHERE tipo = 'Dueno' AND validado = 0");
$stmt_total->execute();
$total_result = $stmt_total->get_result();
$total_row = $total_result->fetch_assoc();
$total_dueños = $total_row['total'];
$total_pages = ceil($total_dueños / $limit);

$stmt_total->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/admin_aprobar_dueños.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./css/back_button.css">
    <link rel="stylesheet" href="./css/fix_header.css">
    <title>Epicentro Shopping - Aprobar Dueños de Locales</title>
</head>
<body>
    <div class="wrapper">
            <?php include './includes/header.php'; ?>
        
        <main class="container">
                    <?php include './includes/back_button.php'; ?>
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
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <button type="button" class="btn btn-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#confirmModal" 
                                            data-id="<?php echo htmlspecialchars($row['id']); ?>"
                                            data-email="<?php echo htmlspecialchars($row['email']); ?>">
                                        Aprobar
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No hay dueños pendientes de aprobación</td>
                        </tr>
                    <?php endif; ?>
                    <?php $stmt->close(); ?>
                </tbody>
            </table>

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

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Aprobación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas aprobar al dueño <strong id="modalEmail"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="approveForm" action="./private/crud/usuarios.php" method="post" style="display: inline;">
                        <input type="hidden" name="action" value="aprobar_dueno">
                        <input type="hidden" name="id" id="modalId">
                        <button type="submit" class="btn btn-success">Confirmar Aprobación</button>
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
            const email = button.getAttribute('data-email');
            
            document.getElementById('modalId').value = id;
            document.getElementById('modalEmail').textContent = email;
        });
    </script>
</body>
</html>