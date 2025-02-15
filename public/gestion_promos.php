<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Debes estar registrado para gestionar las promociones.";
    exit();
}

$usuario_id = $_SESSION['user_id'];

include '../env/shopping_db.php';


$items_per_page = 5;
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
    <!--    <link rel="stylesheet" href="../css/styles.css"> -->
    <link rel="stylesheet" href="../css/gestion_promos.css">
    <title>Epicentro Shopping - Gestión de Promociones</title>
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
    <?php include '../includes/header.php'; ?>
    <main class="container my-4">
        <h1>Gestión de Promociones</h1>
        <div id="promocionesContainer">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card'>";
                    echo "<div class='card-body'>";
                    echo "<h2>" . $row["local_nombre"] . "</h2>";
                    echo "<p><strong>" . $row["textoPromo"] . "</strong></p>";
                    echo "<p>Fecha de Inicio: " . $row["fecha_inicio"] . "</p>";
                    echo "<p>Fecha de Fin: " . $row["fecha_fin"] . "</p>";
                    echo "<p>Días de la Semana: " . $row["diasSemana"] . "</p>";
                    echo "<p>Estado: " . $row["estado"] . "</p>";
                    echo "<p>Email: " . $row["email"] . "</p>";
                    if ($row["estado"] == 'enviada') {
                        echo "<div class='button-container'>";
                        echo "<form id='promoForm_" . $row["idPromocion"] . "_aceptar' method='POST' action='../private/gestionar_promocion.php'>";
                        echo "<input type='hidden' name='promo_id' value='" . $row["idPromocion"] . "'>";
                        echo "<input type='hidden' name='accion' value='aceptar'>";
                        echo "<button type='button' class='accept' onclick='confirmarAccion(" . $row["idPromocion"] . ", \"aceptar\")'>Aceptar</button>";
                        echo "</form>";
                        echo "<form id='promoForm_" . $row["idPromocion"] . "_rechazar' method='POST' action='../private/gestionar_promocion.php'>";
                        echo "<input type='hidden' name='promo_id' value='" . $row["idPromocion"] . "'>";
                        echo "<input type='hidden' name='accion' value='rechazar'>";
                        echo "<button type='button' class='reject' onclick='confirmarAccion(" . $row["idPromocion"] . ", \"rechazar\")'>Rechazar</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay promociones solicitadas para tus locales.</p>";
            }
            ?>
        </div>
        </div>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Anterior</a>
            <?php else: ?>
                <a class="disabled">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Siguiente</a>
            <?php else: ?>
                <a class="disabled">Siguiente</a>
            <?php endif; ?>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>