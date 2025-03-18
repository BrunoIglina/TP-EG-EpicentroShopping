<?php
session_start();
if(!isset($_GET['local_id'])) {
    header("Location: locales.php");
}

include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
include './private/rubros.php';

$categoriaCliente = isset($_SESSION['user_categoria']) ? $_SESSION['user_categoria'] : null;

$categorias = ['Inicial', 'Medium', 'Premium'];
$sql = "
    SELECT 
        promociones.id AS promo_id,
        promociones.textoPromo, 
        promociones.fecha_inicio, 
        promociones.fecha_fin,
        promociones.diasSemana,
        promociones.local_id
    FROM
        promociones 
    WHERE 
        promociones.estadoPromo = 'Aprobada'
";

if ($categoriaCliente) {
    $indice_categoria_cliente = array_search($categoriaCliente, $categorias);
    $sql .= " AND promociones.categoriaCliente IN (";
    for ($i = 0; $i <= $indice_categoria_cliente; $i++) {
        $sql .= "'" . $categorias[$i] . "'";
        if ($i < $indice_categoria_cliente) {
            $sql .= ", ";
        }
    }
    $sql .= ")";
}

if (isset($_GET['local_id']) && $_GET['local_id'] != '') {
    $local_id = (int)$_GET['local_id'];
    $sql .= " AND promociones.local_id = $local_id";
}

$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql .= " LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}


$total_result_sql = "
    SELECT COUNT(*) AS total
    FROM 
        promociones 
    WHERE 
        promociones.estadoPromo = 'Aprobada'
";
if ($categoriaCliente) {
    $total_result_sql .= " AND promociones.categoriaCliente IN (";
    for ($i = 0; $i <= $indice_categoria_cliente; $i++) {
        $total_result_sql .= "'" . $categorias[$i] . "'";
        if ($i < $indice_categoria_cliente) {
            $total_result_sql .= ", ";
        }
    }
    $total_result_sql .= ")";
}

if (isset($_GET['local_id']) && $_GET['local_id'] != '') {
    $local_id = (int)$_GET['local_id'];
    $total_result_sql .= " AND promociones.local_id = $local_id";
}

$total_result = $conn->query($total_result_sql);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo.png">
    <title>Epicentro Shopping - Promociones</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        <main class="container-fluid">
            <div class="row">
                <div class="col 12">
                    <div id="promocionesContainer">
                        <?php
                        $currentLocal = '';
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($currentLocal != $_GET["local_nombre"]) {
                                    if ($currentLocal != '') {
                                        echo "</div>";
                                    }
                                    echo "<div class='card mb-3'>";
                                    echo "<div class='card-header'><h2 class='card-title'>" . $_GET["local_nombre"] . "</h2></div>";
                                    $currentLocal = $_GET["local_nombre"];
                                }
                                echo "<div class='card-body'>";
                                echo "<p><strong>" . $row["textoPromo"] . "</strong></p>";
                                echo "<p>Fecha de Inicio: " . $row["fecha_inicio"] . "</p>";
                                echo "<p>Fecha de Fin: " . $row["fecha_fin"] . "</p>";
                                echo "<p>Días de la Semana: " . $row["diasSemana"] . "</p>";
                                echo "<p>Rubro: " . $_GET["local_rubro"] . "</p>";
                                echo "<p>ID del Local: " . $_GET["local_id"] . "</p>";
                                echo "<form method='POST' action='pedir_promocion.php'>";
                                echo "<input type='hidden' name='promo_id' value='" . $row["promo_id"] . "'>";
                                echo "<button type='submit' class='btn btn-success'>Pedir Promoción</button>";
                                echo "</form>";
                                echo "</div>";
                            }
                            echo "</div>";
                        } else {
                            echo "<p>No hay promociones de este local.</p>";
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
                </div>
            </div>
        </main>
        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
