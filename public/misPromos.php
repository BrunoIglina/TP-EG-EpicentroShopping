<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueño') {
    header("Location: index.php");
    exit();
}

include '../env/shopping_db.php';

// Obtener el ID del usuario autenticado
$user_id = $_SESSION['user_id'];

// Realizar la consulta para obtener las promociones
$sql = "SELECT id, textoPromo, fecha_inicio, fecha_fin, diasSemana, categoriaCliente, local_id, estadoPromo
        FROM promociones
        WHERE local_id IN (SELECT id FROM locales WHERE idUsuario = ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Mis Promociones</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <h1>Mis Promociones</h1>
        <button onclick="location.href='darAltaPromos.php'">Agregar Promoción</button>
        <table>
            <tr>
                <th>ID</th>
                <th>Texto de la Promoción</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
                <th>Días de la Semana</th>
                <th>Categoría Cliente</th>
                <th>Local ID</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
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
                    echo "<td>";
                    echo "<form action='../private/eliminarPromo.php' method='POST' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar esta promoción?\");'>";
                    echo "<input type='hidden' name='promo_id' value='" . $row['id'] . "'>";
                    echo "<input type='submit' value='Eliminar'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No hay promociones</td></tr>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </table>
    </main>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
