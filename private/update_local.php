<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include "../env/shopping_db.php";
include "usuarios_functions.php";
include "locales_functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ids_locales = $_POST['id_local'];
    $nombres_antiguos = $_POST['nombre_antiguo_local'];
    $nombres = $_POST['nombre_local'];
    $ubicaciones = $_POST['ubicacion_local'];
    $rubros = $_POST['rubro_local'];
    $ids_dueños = $_POST['id_dueño'];

    // Actualiza cada local en la base de datos
    foreach ($ids_locales as $index => $id_local){

        $nombre_antiguo = $nombres_antiguos[$index];
        $nombre_local = $nombres[$index];
        $ubicacion = $ubicaciones[$index];
        $rubro = $rubros[$index];
        $id_dueño = $ids_dueños[$index];

        $dueño = get_dueño($id_dueño);

        if(!($dueño)){

            echo "El usuario ingresado para el local: ", $nombre_local, " no es dueño.";
            exit();

        }else{

            $local = get_local_by_nombre($nombre_local);

            if(($local) && $nombre_antiguo != $nombre_local){

                echo "Ya existe un local con éste nombre: ", $nombre_local;
                exit();

                
            }else{

                $id_dueño = $dueño['id'];
                
                $query = "UPDATE locales SET nombre = '$nombre_local', ubicacion = '$ubicacion', rubro = '$rubro', idUsuario = '$id_dueño' WHERE id = '$id_local'";

                if ($conn->query($query) === FALSE) {

                    echo "Error: " . $sql . "<br>" . $conn->error;

                }

            }

        }
    }
    echo "Locales actualizados con éxito";
    header("Location: ../public/admin_locales.php");
}
?>