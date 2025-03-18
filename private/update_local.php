<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');

include "functions_usuarios.php";
include "functions_locales.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ids_locales = $_POST['id_local'];
    $nombres_antiguos = $_POST['nombre_antiguo_local'];
    $nombres = $_POST['nombre_local'];
    $ubicaciones = $_POST['ubicacion_local'];
    $rubros = $_POST['rubro_local'];
    $ids_dueños = $_POST['id_dueño'];

    foreach ($ids_locales as $index => $id_local) {

        $nombre_antiguo = $nombres_antiguos[$index];
        $nombre_local = $nombres[$index];
        $ubicacion = $ubicaciones[$index];
        $rubro = $rubros[$index];
        $id_dueño = $ids_dueños[$index];

        $dueño = get_dueño($id_dueño);

        if (!($dueño)) {

            echo "El usuario ingresado para el local: " . htmlspecialchars($nombre_local, ENT_QUOTES, 'UTF-8') . " no es dueño.";
            exit();

        } else {

            $local = get_local_by_nombre($nombre_local);

            if (($local) && $nombre_antiguo != $nombre_local) {

                echo "Ya existe un local con éste nombre: " . htmlspecialchars($nombre_local, ENT_QUOTES, 'UTF-8');
                exit();

            } else {

                $id_dueño = $dueño['id'];

                $query = $conn->prepare("UPDATE locales SET nombre = ?, ubicacion = ?, rubro = ?, idUsuario = ? WHERE id = ?");
                $query->bind_param('ssssi', $nombre_local, $ubicacion, $rubro, $id_dueño, $id_local);

                if ($query->execute() === FALSE) {

                    echo "Error: " . $query->error;

                }

            }

        }
    }
    echo "Locales actualizados con éxito";
    header("Location: ../admin_locales.php");
    exit();
}
?>
