<?php
session_start();
if (!isset($_GET['local_id'])) {
    header("Location: locales.php");
}

// include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
include('./env/shopping_db.php');
include './private/rubros.php';
include './private/functions_locales.php';

$local = get_local($_GET['local_id']);

$categoriaCliente = isset($_SESSION['user_categoria']) ? $_SESSION['user_categoria'] : null;
$tipoUsuario = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : null;

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
        AND CURRENT_DATE() BETWEEN fecha_inicio AND fecha_fin
";


if ($categoriaCliente) {
    $indice_categoria_cliente = array_search($categoriaCliente, $categorias);


    if ($indice_categoria_cliente !== false) {
        $categorias_permitidas = array_slice($categorias, 0, $indice_categoria_cliente + 1);
        $categorias_permitidas_sql = implode("', '", $categorias_permitidas);
        $sql .= " AND promociones.categoriaCliente IN ('$categorias_permitidas_sql')";
    } else {
        $sql .= " AND 1=0"; 
    }
}

if (isset($_GET['local_id']) && $_GET['local_id'] != '') {
    $local_id = (int)$_GET['local_id'];
    $sql .= " AND promociones.local_id = $local_id";
}

$limit = 9; 
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
        AND CURRENT_DATE() BETWEEN fecha_inicio AND fecha_fin
";
if ($categoriaCliente) {
    $total_result_sql .= " AND promociones.categoriaCliente IN ('$categorias_permitidas_sql')";
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
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./css/promociones.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Promociones</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
                    <?php
            if (isset($_SESSION['mensaje_error'])) {
                echo "<div class='alert alert-danger text-center'>" . $_SESSION['mensaje_error'] . "</div>";
                unset($_SESSION['mensaje_error']); 
            }
            if (isset($_SESSION['mensaje_exito'])) {
                echo "<div class='alert alert-success text-center'>" . $_SESSION['mensaje_exito'] . "</div>";
                unset($_SESSION['mensaje_exito']); }
            ?>

        <main class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div id="promocionesContainer">
                        <?php
                        $currentLocal = '';
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($currentLocal != $local["id"]) {
                                    if ($currentLocal != '') {
                                        echo "</div>";
                                    }
                                    echo "<div class='card mb-3'>";
                                    echo "<div class='card-header'><h2 class='card-title'>" . htmlspecialchars($local["nombre"]) . "</h2></div>";
                                    $currentLocal = $local["nombre"];
                                }
                                echo "<div class='card-body'>";
                                echo "<p><strong>" . htmlspecialchars($row["textoPromo"]) . "</strong></p>";
                                echo "<p>Fecha de Inicio: " . htmlspecialchars($row["fecha_inicio"]) . "</p>";
                                echo "<p>Fecha de Fin: " . htmlspecialchars($row["fecha_fin"]) . "</p>";
                                echo "<p>Días de la Semana: " . htmlspecialchars($row["diasSemana"]) . "</p>";


                                if ($tipoUsuario !== 'Dueno' && $tipoUsuario !== 'Administrador') {
                                    if ($tipoUsuario === 'Visitante') {
                                        echo "<a href='login.php' class='btn btn-success'>Pedir Promoción</a>";
                                    } else {
                                        echo "<form method='POST' action='pedir_promocion.php'>";
                                        echo "<input type='hidden' name='promo_id' value='" . (int)$row["promo_id"] . "'>";
                                        echo "<button type='submit' class='btn btn-success'>Pedir Promoción</button>";
                                        echo "</form>";
                                    }
                                }
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
                            <li class="page-item <?php if ($page <= 1) { echo 'disabled'; } ?>">
                                <a class="page-link" href="<?php if ($page > 1) { echo "?page=" . ($page - 1); } ?>">Anterior</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php if ($page == $i) { echo 'active'; } ?>">
                                    <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php if ($page >= $total_pages) { echo 'disabled'; } ?>">
                                <a class="page-link" href="<?php if ($page < $total_pages) { echo "?page=" . ($page + 1); } ?>">Siguiente</a>
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
