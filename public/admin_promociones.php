<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_promociones.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Administración de Promociones</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <h1>Aprobar Promociones Pendientes</h1>
        <form action="../private/controAcepPromo.php" method="POST">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Texto de la Promoción</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("127.0.0.1", "root", "", "shopping_db", 3309);
                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    $sql = "SELECT id, textoPromo, fecha_inicio, fecha_fin FROM promociones WHERE estadoPromo = 'Pendiente'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['textoPromo'] . "</td>";
                            echo "<td>" . $row['fecha_inicio'] . "</td>";
                            echo "<td>" . $row['fecha_fin'] . "</td>";
                            echo "<td><button type='submit' name='aprobar' value='" . $row['id'] . "' class='btn btn-success'>Aprobar</button></td>";
                            echo "<td><button type='submit' name='rechazar' value='" . $row['id'] . "' class='btn btn-danger'>Rechazar</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No hay promociones pendientes</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </form>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>