<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "Debes estar registrado para pedir una promoción.";
        exit();
    }

    $usuario_id = $_SESSION['user_id'];
    $promo_id = $_POST['promo_id'];

    include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');

    $sql = "INSERT INTO promociones_cliente (idCliente, idPromocion, fechaUsoPromo, estado) VALUES ($usuario_id, $promo_id, NOW(), 'enviada')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Promoción pedida exitosamente. El local debe aprobar tu promoción. La encontrarás en la sección Mis Promociones.'); window.location.href = 'promociones.php';</script>";
    } else {
        echo "Error al pedir la promoción: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Método de solicitud no válido.";
}
?>