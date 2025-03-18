<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novedades = isset($_POST['novedades']) ? $_POST['novedades'] : [];
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $select_all = isset($_POST['select_all']) ? $_POST['select_all'] : '0';

    if ($action == 'toggle') {
        header("Location: ../admin_novedades.php?select_all=$select_all");
        exit();
    }

    if (empty($novedades) && $select_all != '1') {
        echo "Por favor, selecciona al menos una novedad.";
        exit();
    }

    if (empty($novedades)) {
        echo "Por favor, selecciona al menos una novedad.";
        exit();
    }

    if ($action == 'edit' && count($novedades) < 2) {
        $id = $novedades[0];
        header("Location: ../public/editar_novedad.php?id=$id");
        exit();

    }elseif ($action == 'delete') {
        foreach ($novedades as $novedad_id) {
            $query = "DELETE FROM novedades WHERE id = '$novedad_id'";
            $conn->query($query);
        }

        echo "Novedades eliminadas con éxito.";
        header("Location: ../admin_novedades.php");
        exit();
    } else {
        die("Se puede seleccionar una sola novedad para modificar");
    }
}
?>
