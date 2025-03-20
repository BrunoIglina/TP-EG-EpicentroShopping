<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: login.php");
    exit();
}

    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include(__DIR__ . '/../env/shopping_db.php');



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $query = "UPDATE usuarios SET validado = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: ../admin_aprobar_dueños.php");
    exit();
}
?>