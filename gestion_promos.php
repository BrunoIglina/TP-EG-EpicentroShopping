<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
    header("Location: login.php");
    exit();
}
$usuario_id = $_SESSION['user_id'];

    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include('./env/shopping_db.php');


$items_per_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$total_sql = "
    SELECT COUNT(*) as total
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
    INNER JOIN
        usuarios
    ON
        promociones_cliente.idCliente = usuarios.id  
    WHERE 
        locales.idUsuario = $usuario_id
";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $items_per_page);

$sql = "
    SELECT 
        promociones_cliente.idPromocion,
        promociones_cliente.estado,
        promociones.textoPromo, 
        promociones.fecha_inicio, 
        promociones.fecha_fin,
        promociones.diasSemana,
        locales.nombre AS local_nombre,
        usuarios.email AS email
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
    INNER JOIN
        usuarios
    ON
        promociones_cliente.idCliente = usuarios.id  
    WHERE 
        locales.idUsuario = $usuario_id
    LIMIT $items_per_page OFFSET $offset
";

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <title>Gestión de Promociones</title>
    <script>
        function confirmarAccion(promoId, accion) {
            let mensaje = "¿Estás seguro de que deseas " + accion + " esta promoción?";
            if (confirm(mensaje)) {
                document.getElementById('promoForm_' + promoId + '_' + accion).submit();
            }
        }
    </script>
</head>
<body>
    <?php  include './includes/header.php'; ?> 
    
    <div class="container my-4">
      
        
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row["local_nombre"]; ?></h5>
                                <p class="card-text"><strong><?php echo $row["textoPromo"]; ?></strong></p>
                                <p>Inicio: <?php echo $row["fecha_inicio"]; ?></p>
                                <p>Fin: <?php echo $row["fecha_fin"]; ?></p>
                                <p>Días: <?php echo $row["diasSemana"]; ?></p>
                                <p><strong>Estado:</strong> <?php echo $row["estado"]; ?></p>
                                <p>Email: <?php echo $row["email"]; ?></p>
                                <?php if ($row["estado"] == 'enviada'): ?>
                                    <div class="d-flex justify-content-between">
                                        <form id="promoForm_<?php echo $row["idPromocion"]; ?>_aceptar" method="POST" action="./private/gestionar_promocion.php">
                                            <input type="hidden" name="promo_id" value="<?php echo $row["idPromocion"]; ?>">
                                            <input type="hidden" name="accion" value="aceptar">
                                            <button type="button" class="btn btn-success" onclick="confirmarAccion(<?php echo $row["idPromocion"]; ?>, 'aceptar')">Aceptar</button>
                                        </form>
                                        <form id="promoForm_<?php echo $row["idPromocion"]; ?>_rechazar" method="POST" action="./private/gestionar_promocion.php">
                                            <input type="hidden" name="promo_id" value="<?php echo $row["idPromocion"]; ?>">
                                            <input type="hidden" name="accion" value="rechazar">
                                            <button type="button" class="btn btn-danger" onclick="confirmarAccion(<?php echo $row["idPromocion"]; ?>, 'rechazar')">Rechazar</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No hay promociones disponibles.</p>
            <?php endif; ?>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $page == $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
                </li>
            </ul>
        </nav>
    </div>
    <?php include './includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
