<?php
require_once './includes/navigation_history.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['mensaje_error'] = "Debes estar registrado para pedir una promoción.";
        header("Location: login.php");
        exit();
    }

    $usuario_id = $_SESSION['user_id'];
    $promo_id = filter_input(INPUT_POST, 'promo_id', FILTER_VALIDATE_INT);

    if (!$promo_id) {
        $_SESSION['mensaje_error'] = "ID de promoción inválido.";
        header("Location: promociones.php");
        exit();
    }

    require_once './config/database.php';
    $conn = getDB();

    $referer = $_SERVER['HTTP_REFERER'] ?? 'promociones.php';

    $stmt_check = $conn->prepare("SELECT COUNT(*) AS count FROM promociones_cliente WHERE idCliente = ? AND idPromocion = ?");
    $stmt_check->bind_param("ii", $usuario_id, $promo_id);
    $stmt_check->execute();
    $check_result = $stmt_check->get_result();
    $row = $check_result->fetch_assoc();
    $stmt_check->close();

    if ($row['count'] > 0) {
        $_SESSION['mensaje_error'] = "Ya has solicitado esta promoción anteriormente.";
        header("Location: $referer");
        exit();
    }

    $stmt_insert = $conn->prepare("INSERT INTO promociones_cliente (idCliente, idPromocion, fechaUsoPromo, estado) VALUES (?, ?, NOW(), 'enviada')");
    $stmt_insert->bind_param("ii", $usuario_id, $promo_id);

    if ($stmt_insert->execute()) {
        $_SESSION['mensaje_exito'] = "Promoción pedida exitosamente. La encontrarás en la sección Mis Promociones.";
    } else {
        error_log("Error al insertar promoción: " . $stmt_insert->error);
        $_SESSION['mensaje_error'] = "Error al pedir la promoción. Intente nuevamente.";
    }

    $stmt_insert->close();
    header("Location: $referer");
    exit();
}
?>