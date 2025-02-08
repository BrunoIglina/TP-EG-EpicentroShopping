<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Debes estar registrado para gestionar las promociones.";
    exit();
}

$usuario_id = $_SESSION['user_id'];

include '../env/shopping_db.php';

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
    <main>
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
                        echo "<form id='promoForm_" . $row["idPromocion"] . "_aceptar' method='POST' action='../private/gestionar_promocion.php'>";
                        echo "<input type='hidden' name='promo_id' value='" . $row["idPromocion"] . "'>";
                        echo "<input type='hidden' name='accion' value='aceptar'>";
                        echo "<button type='button' onclick='confirmarAccion(" . $row["idPromocion"] . ", \"aceptar\")'>Aceptar</button>";
                        echo "</form>";
                        echo "<form id='promoForm_" . $row["idPromocion"] . "_rechazar' method='POST' action='../private/gestionar_promocion.php'>";
                        echo "<input type='hidden' name='promo_id' value='" . $row["idPromocion"] . "'>";
                        echo "<input type='hidden' name='accion' value='rechazar'>";
                        echo "<button type='button' onclick='confirmarAccion(" . $row["idPromocion"] . ", \"rechazar\")'>Rechazar</button>";
                        echo "</form>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay promociones solicitadas para tus locales.</p>";
            }
            ?>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>