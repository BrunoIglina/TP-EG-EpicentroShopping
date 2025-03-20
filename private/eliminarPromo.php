<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Dueno') {
        header("Location: index.php");
        exit();
    }

        // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
        include(__DIR__ . '/../env/shopping_db.php');



    $promo_id = $_POST['promo_id'];

    $sql = "DELETE FROM promociones WHERE id = ? AND local_id IN (SELECT id FROM locales WHERE idUsuario = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $promo_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Promoción eliminada exitosamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar la promoción.";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../misPromos.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
