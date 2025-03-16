<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include '../env/shopping_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locales = isset($_POST['locales']) ? $_POST['locales'] : [];
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $select_all = isset($_POST['select_all']) ? $_POST['select_all'] : '0';

    if ($action == 'toggle') {
        header("Location: ../public/admin_locales.php?select_all=$select_all");
        exit();
    }

    if (empty($locales) && $select_all != '1') {
        echo "Por favor, selecciona al menos un local.";
        exit();
    }

    if (empty($locales)) {
        echo "Por favor, selecciona al menos un local.";
        exit();
    }

    if ($action == 'edit') {
        $ids = implode(',', $locales);
        header("Location: ../public/editar_local.php?ids=$ids");
        exit();
    } elseif ($action == 'delete') {
        foreach ($locales as $local_id) {
            $query = "DELETE FROM locales WHERE id = '$local_id'";
            if ($conn->query($query) === TRUE) {
                echo "Local eliminado con éxito.";
            } else {
                echo "Error al eliminar el local: " . $conn->error;
            }
        }
        header("Location: ../public/admin_locales.php");
        exit();
    }
}
?>