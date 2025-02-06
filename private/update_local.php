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

    $ids = $_POST['id_local'];
    $nombres_antiguos = $_POST['nombre_antiguo_local'];
    $nombres = $_POST['nombre_local'];
    $ubicaciones = $_POST['ubicacion_local'];
    $rubros = $_POST['rubro_local'];
    $emails = $_POST['email'];

    // Actualiza cada local en la base de datos
    foreach ($ids as $index => $id_local){
        $nombre_antiguo = $nombres_antiguos[$index];
        $nombre_local = $nombres[$index];
        $ubicacion = $ubicaciones[$index];
        $rubro = $rubros[$index];
        $email = $emails[$index];
        
        $result_dueño = get_dueño_by_email($email);

        if(!($result_dueño -> num_rows > 0)){

            echo "El usuario ingresado para el local: ", $nombre_local, " no es dueño.";
            exit();

        }else{

            $result_local = get_local_by_nombre($nombre_local);

            if(($result_local -> num_rows > 0) && $nombre_antiguo == $nombre_local){

                $dueño = $result_dueño -> fetch_assoc();
                $idDueño = $dueño['id'];
                $query = "UPDATE locales SET nombre = '$nombre_local', ubicacion = '$ubicacion', rubro = '$rubro', idUsuario = '$idDueño' WHERE id = '$id_local'";

                if ($conn->query($query) === FALSE) {

                    echo "Error: " . $sql . "<br>" . $conn->error;

                }
            }else{

                echo "Ya existe un local con éste nombre: ", $nombre_local;
                exit();

            }

        }
    }
    echo "Locales actualizados con éxito";
    header("Location: ../public/admin_locales.php");
    exit();
}
?>