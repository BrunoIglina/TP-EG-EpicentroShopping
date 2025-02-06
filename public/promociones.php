<?php
// Conexión a la base de datos
$conn = new mysqli("127.0.0.1", "root", "", "shopping_db", 3309);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultamos los locales con promociones aprobadas, incluyendo días de la semana
$sql = "
    SELECT 
        locales.nombre AS local_nombre, 
        promociones.textoPromo, 
        promociones.fecha_inicio, 
        promociones.fecha_fin,
        promociones.diasSemana
    FROM 
        locales 
    INNER JOIN 
        promociones 
    ON 
        locales.id = promociones.local_id 
    WHERE 
        promociones.estadoPromo = 'Aprobada'
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
    <title>Epicentro Shopping - Promociones</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <?php
        $currentLocal = '';
        if ($result->num_rows > 0) {
            // Mostramos cada promoción aprobada agrupada por local
            while ($row = $result->fetch_assoc()) {
                if ($currentLocal != $row["local_nombre"]) {
                    if ($currentLocal != '') {
                        echo "</div>"; // Cerrar el div del local anterior
                    }
                    echo "<div>";
                    echo "<h2>" . $row["local_nombre"] . "</h2>";
                    $currentLocal = $row["local_nombre"];
                }
                echo "<div>";
                echo "<p><strong>" . $row["textoPromo"] . "</strong></p>";
                echo "<p>Fecha de Inicio: " . $row["fecha_inicio"] . "</p>";
                echo "<p>Fecha de Fin: " . $row["fecha_fin"] . "</p>";
                echo "<p>Días de la Semana: " . $row["diasSemana"] . "</p>"; // Mostrar los días de la semana
                echo "</div>";
            }
            echo "</div>"; // Cerrar el div del último local
        } else {
            echo "<p>No hay promociones aprobadas en este momento.</p>";
        }
        ?>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
