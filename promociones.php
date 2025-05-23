<?php
session_start();
if (!isset($_GET['local_id'])) {
    header("Location: locales.php");
    exit;
}

include('./env/shopping_db.php');
include './private/rubros.php';
include './private/functions_locales.php';

$local = get_local($_GET['local_id']);

$categoriaCliente = isset($_SESSION['user_categoria']) ? $_SESSION['user_categoria'] : null;
$tipoUsuario = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : 'Visitante';

$categorias = ['Inicial', 'Medium', 'Premium'];
$sql = "
    SELECT 
        promociones.id AS promo_id,
        promociones.textoPromo, 
        promociones.fecha_inicio, 
        promociones.fecha_fin,
        promociones.diasSemana,
        promociones.local_id,
        promociones.categoriaCliente
    FROM
        promociones 
    WHERE 
        promociones.estadoPromo = 'Aprobada'
        AND CURRENT_DATE() BETWEEN fecha_inicio AND fecha_fin
";

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
if (isset($_GET['local_id']) && $_GET['local_id'] != '') {
    $total_result_sql .= " AND promociones.local_id = $local_id";
}
$total_result = $conn->query($total_result_sql);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);
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
    <link rel="stylesheet" href="./css/tarjetas.css">
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
            unset($_SESSION['mensaje_exito']);
        }
        ?>

        <main class="container-fluid">
            <h2><?php echo $local["nombre"]; ?></h2>  
                <?php
                if ($result->num_rows > 0) {
                    
                    while ($row = $result->fetch_assoc()) { ?>
                    <div class="row d-flex align-items-stretch">
                        <div class="col-md-2"></div>
                        <div class="col-md-8 d-flex justify-content-center">
                            <div class="card w-100 mb-4">
                                <div class="card-body">
                                    <p><strong><?php echo $row["textoPromo"]; ?></strong></p>
                                    <p><strong>Categoría del Cliente: <?php echo $row["categoriaCliente"]; ?></strong></p>
                                    <p>Fecha de Inicio: <?php echo $row["fecha_inicio"]; ?></p>
                                    <p>Fecha de Fin: <?php echo $row["fecha_fin"]; ?></p>
                                    <p>Días de la Semana: <?php echo str_replace(',', ', ', $row["diasSemana"]); ?></p>
                                    <?php
                                    if ($tipoUsuario === 'Visitante') {
                                      echo "<a href='login.php' class='btn btn-success mb-3'>Pedir Promoción</a>";
                                    } elseif ($tipoUsuario === 'Cliente') {
                                      $promoId = (int)$row["promo_id"];
                                      $promoCategoria = $row["categoriaCliente"];
                                      $clienteCategoria = $_SESSION['user_categoria'];
                                      $clienteId = (int)$_SESSION['user_id'];

                                      $indiceCliente = array_search($clienteCategoria, $categorias);
                                      $indicePromo = array_search($promoCategoria, $categorias);

                                      $checkSql = "SELECT COUNT(*) AS ya_pedido FROM promociones_cliente WHERE idCliente = $clienteId AND idPromocion = $promoId";
                                      $checkResult = $conn->query($checkSql);
                                      $yaPidio = $checkResult->fetch_assoc()['ya_pedido'] > 0;

                                      if ($indicePromo > $indiceCliente || $yaPidio) {
                                          echo "<button class='btn btn-secondary mb-3' style='background-color: gray; cursor: not-allowed;' disabled>No Disponible</button>";
                                      } else {
                                          echo "<form method='POST' action='pedir_promocion.php'>";
                                          echo "<input type='hidden' name='promo_id' value='" . $promoId . "'>";
                                          echo "<button type='submit' class='btn btn-success mb-3'>Pedir Promoción</button>";
                                          echo "</form>";
                                        }
                                    } else {
                                        // Dueño o Administrador
                                        echo "<button class='btn btn-secondary mb-3' style='background-color: gray; cursor: not-allowed;' disabled>No Disponible</button>";
                                    }?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <?php } ?>

                <?php } else { ?>
                    <p>No hay promociones de este local.</p>
                <?php } ?>

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
            
        </main>
        <?php include './includes/footer.php'; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
