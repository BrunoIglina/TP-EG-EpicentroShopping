<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}
// include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
include(__DIR__ . '/../env/shopping_db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $novedad_id = isset($_POST['novedad_id']) ? $_POST['novedad_id'] : '';

    // Verificamos si se ha enviado una novedad válida
    if (empty($novedad_id)) {
        $_SESSION['error'] = "No se ha seleccionado ninguna novedad.";
        header("Location: ../admin_novedades.php");
        exit();
    }

    // Realizamos la acción según lo indicado
    if ($action == 'edit') {
        header("Location: ../editar_novedad.php?id=$novedad_id");
        exit();
    }

    if ($action == 'delete') {
        $stmt = $conn->prepare("DELETE FROM novedades WHERE id = ?");
        $stmt->bind_param('i', $novedad_id);
        $stmt->execute();
        $_SESSION['success'] = "Novedad eliminada con éxito.";
        header("Location: ../admin_novedades.php");
        exit();
    }

    $_SESSION['error'] = "Acción no válida.";
    header("Location: ../admin_novedades.php");
    exit();
}
?>
