<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
    header("Location: index.php");
    exit();
}

    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include('./env/shopping_db.php');

$user_id = $_SESSION['user_id'];

$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT p.id, p.textoPromo, p.fecha_inicio, p.fecha_fin, p.diasSemana, p.categoriaCliente, p.local_id, p.estadoPromo,
               (SELECT COUNT(*) FROM promociones_cliente pc WHERE pc.idPromocion = p.id AND pc.estado = 'aceptada') AS totalPromos
        FROM promociones p
        WHERE p.local_id IN (SELECT id FROM locales WHERE idUsuario = ?)
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$total_result_sql = "SELECT COUNT(*) AS total
                     FROM promociones
                     WHERE local_id IN (SELECT id FROM locales WHERE idUsuario = ?)";
$total_stmt = $conn->prepare($total_result_sql);
$total_stmt->bind_param("i", $user_id);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$stmt->close();
$total_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/mispromos.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Mis Promociones</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <main class="container my-4">
            <h2 class="text-center my-4">Mis Promociones</h2>
            
            <p>
            <button class="btn btn-primary mb-3" onclick="location.href='darAltaPromos.php'">Agregar Promoción</button>
            <button class="btn btn-secondary mb-3" onclick="location.href='reportesDueño.php'">Ver Reportes</button>
            </p>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Texto de la Promoción</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                            <th>Días de la Semana</th>
                            <th>Categoría Cliente</th>
                            <th>Local ID</th>
                            <th>Estado</th>
                            <th>Total de Usos</th>
                            <th>Acciones</th>
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
                                echo "<td>" . $row['diasSemana'] . "</td>";
                                echo "<td>" . $row['categoriaCliente'] . "</td>";
                                echo "<td>" . $row['local_id'] . "</td>";
                                echo "<td>" . $row['estadoPromo'] . "</td>";
                                echo "<td>" . $row['totalPromos'] . "</td>";
                                echo "<td>";
                                echo "<form action='../private/eliminarPromo.php' method='POST' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar esta promoción?\");'>";
                                echo "<input type='hidden' name='promo_id' value='" . $row['id'] . "'>";
                                echo "<input type='submit' value='Eliminar' class='btn btn-danger'>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No hay promociones</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($page > 1){ echo "?page=" . ($page - 1); } ?>">Anterior</a>
                    </li>
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if($page == $i){ echo 'active'; } ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if($page >= $total_pages){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($page < $total_pages){ echo "?page=" . ($page + 1); } ?>">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </main>
        
        <?php include './includes/footer.php'; ?>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>