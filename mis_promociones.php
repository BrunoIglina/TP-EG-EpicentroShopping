<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Cliente') {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['user_id'];

include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');


$limit = 4; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "
    SELECT 
        locales.nombre,
        promociones.textoPromo, 
        promociones.fecha_inicio, 
        promociones.fecha_fin,
        promociones.diasSemana,
        promociones_cliente.estado
    FROM 
        promociones_cliente 
    INNER JOIN 
        promociones 
    ON 
        promociones_cliente.idPromocion = promociones.id 
    INNER JOIN 
        locales
    ON
        promociones.local_id = locales.id
    WHERE 
        promociones_cliente.idCliente = ?
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $usuario_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$total_result_sql = "
    SELECT COUNT(*) AS total
    FROM 
        promociones_cliente 
    INNER JOIN 
        promociones 
    ON 
        promociones_cliente.idPromocion = promociones.id 
    INNER JOIN 
        locales
    ON
        promociones.local_id = locales.id
    WHERE 
        promociones_cliente.idCliente = ?
";
$total_stmt = $conn->prepare($total_result_sql);
$total_stmt->bind_param("i", $usuario_id);
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
    <link rel="stylesheet" href="./css/mis_promociones.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo.png">
    <title>Epicentro Shopping - Mis Promociones</title>
</head>
<body>
    <div class="wrapper">
    <?php include './includes/header.php'; ?>
    <h2 class="text-center my-4">Mis Promociones</h2>
        <main class="container">
            
            <div id="misPromocionesContainer" class="row">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='col-lg-6 col-md-12 mb-4'>";
                        echo "<div class='card'>";
                        echo "<div class='card-body'>";
                        echo "<h2 class='card-title'>" . $row["nombre"] . "</h2>";
                        echo "<p><strong>" . $row["textoPromo"] . "</strong></p>";
                        echo "<p>Fecha de Inicio: " . $row["fecha_inicio"] . "</p>";
                        echo "<p>Fecha de Fin: " . $row["fecha_fin"] . "</p>";
                        echo "<p>Días de la Semana: " . $row["diasSemana"] . "</p>";
                        echo "<p>Estado: " . $row["estado"] . "</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No tienes promociones solicitadas.</p>";
                }
                ?>
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