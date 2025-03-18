<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "<p>Debes estar registrado para pedir una promoción.</p>";
        exit();
    }

    $usuario_id = $_SESSION['user_id'];
    $promo_id = $_POST['promo_id'];

    include '../env/shopping_db.php';

    $check_sql = "SELECT COUNT(*) AS count FROM promociones_cliente WHERE idCliente = $usuario_id AND idPromocion = $promo_id";
    $check_result = $conn->query($check_sql);

    if ($check_result) {
        $row = $check_result->fetch_assoc();
        if ($row['count'] > 0) {
            echo "<div style='color: red; font-weight: bold;'>Ya se solicitó esta promoción con anterioridad.</div>";
            exit();
        }
    } else {
        echo "<p>Error al verificar la promoción: " . $conn->error . "</p>";
        exit();
    }

    $sql = "INSERT INTO promociones_cliente (idCliente, idPromocion, fechaUsoPromo, estado) VALUES ($usuario_id, $promo_id, NOW(), 'enviada')";

    if ($conn->query($sql) === TRUE) {
        echo "<div style='color: green; font-weight: bold;'>Promoción pedida exitosamente. El local debe aprobar tu promoción. La encontrarás en la sección Mis Promociones.</div>";
    } else {
        echo "<p>Error al pedir la promoción: " . $conn->error . "</p>";
    }

    $conn->close();
} else {
    echo "<p>Método de solicitud no válido.</p>";
}
?>
