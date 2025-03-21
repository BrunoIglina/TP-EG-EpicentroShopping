<?php
session_start();

require __DIR__ . '/../lib/vendor/autoload.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}
// include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
include(__DIR__ . '/../env/shopping_db.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $local_id = isset($_GET['local_id']) ? $_GET['local_id'] : null;
    
    if ($local_id) {
        $query = "DELETE FROM locales WHERE id = ?";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            $_SESSION['error'] = "Error en la consulta: " . $conn->error;
            header("Location: ../admin_locales.php");
            exit();
        }

        $stmt->bind_param("i", $local_id);
        if (!$stmt->execute()) {
            $_SESSION['error'] = "Error al eliminar el local: " . $stmt->error;
            header("Location: ../admin_locales.php");
            exit();
        }

        $_SESSION['success'] = "Local eliminado exitosamente.";
        header("Location: ../admin_locales.php");
        exit();
    } else {
        $_SESSION['error'] = "No se especificó un local para eliminar.";
        header("Location: ../admin_locales.php");
        exit();
    }
}
?>