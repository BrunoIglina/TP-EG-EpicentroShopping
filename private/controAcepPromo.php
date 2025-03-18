<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['aprobar'])) {
        $promocion_id = $_POST['aprobar'];

        if (!empty($promocion_id)) {
            $conn = new mysqli("127.0.0.1", "root", "", "shopping_db", 3309);
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            $sql = "UPDATE promociones SET estadoPromo = 'Aprobada' WHERE id = $promocion_id";

            if ($conn->query($sql) === TRUE) {
                echo "Promoción aprobada exitosamente.<br>";
            } else {
                echo "Error al aprobar la promoción: " . $conn->error;
            }

            $conn->close();
        } else {
            echo "ID de promoción no proporcionado.<br>";
        }
        header("Location: ../public/admin_promociones.php"); 
        exit();
    }

    if (isset($_POST['rechazar'])) {
        $promocion_id = $_POST['rechazar'];

        if (!empty($promocion_id)) {
            $conn = new mysqli("127.0.0.1", "root", "", "shopping_db", 3309);
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            $sql = "UPDATE promociones SET estadoPromo = 'Denegada' WHERE id = $promocion_id";

            if ($conn->query($sql) === TRUE) {
                echo "Promoción rechazada exitosamente.<br>";
            } else {
                echo "Error al rechazar la promoción: " . $conn->error;
            }

            $conn->close();
        } else {
            echo "ID de promoción no proporcionado.<br>";
        }
        header("Location: ../public/admin_promociones.php"); 
        exit();
    }
} else {
    echo "Método de solicitud no válido.<br>";
}
?>
