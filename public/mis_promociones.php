<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Debes estar registrado para ver tus promociones.";
    exit();
}

$usuario_id = $_SESSION['user_id'];

include '../env/shopping_db.php';

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
        promociones_cliente.idCliente = $usuario_id
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
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Mis Promociones</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <h1>Mis Promociones</h1>
        <div id="misPromocionesContainer">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card'>";
                    echo "<div class='card-body'>";
                    echo "<h2>" . $row["nombre"] . "</h2>";
                    echo "<p><strong>" . $row["textoPromo"] . "</strong></p>";
                    echo "<p>Fecha de Inicio: " . $row["fecha_inicio"] . "</p>";
                    echo "<p>Fecha de Fin: " . $row["fecha_fin"] . "</p>";
                    echo "<p>Días de la Semana: " . $row["diasSemana"] . "</p>";
                    echo "<p>Estado: " . $row["estado"] . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No tienes promociones solicitadas.</p>";
            }
            ?>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>