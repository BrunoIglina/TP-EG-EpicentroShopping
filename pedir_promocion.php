<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['mensaje_error'] = "Debes estar registrado para pedir una promoción.";
        header("Location: login.php");
        exit();
    }

    $usuario_id = $_SESSION['user_id'];
    $promo_id = $_POST['promo_id'];

    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include('./env/shopping_db.php');


    $referer = $_SERVER['HTTP_REFERER'] ?? 'promociones.php';

    $check_sql = "SELECT COUNT(*) AS count FROM promociones_cliente WHERE idCliente = $usuario_id AND idPromocion = $promo_id";
    $check_result = $conn->query($check_sql);

    if ($check_result) {
        $row = $check_result->fetch_assoc();
        if ($row['count'] > 0) {
            $_SESSION['mensaje_error'] = "Ya has solicitado esta promoción anteriormente.";
            header("Location: $referer");
            exit();
        }
    } else {
        $_SESSION['mensaje_error'] = "Error al verificar la promoción: " . $conn->error;
        header("Location: $referer");
        exit();
    }

    $sql = "INSERT INTO promociones_cliente (idCliente, idPromocion, fechaUsoPromo, estado) VALUES ($usuario_id, $promo_id, NOW(), 'enviada')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['mensaje_exito'] = "Promoción pedida exitosamente. La encontrarás en la sección Mis Promociones.";
    } else {
        $_SESSION['mensaje_error'] = "Error al pedir la promoción: " . $conn->error;
    }

    $conn->close();
    header("Location: $referer");
    exit();
}


?>
