<?php
include '../env/shopping_db.php';
include 'usuarios_functions.php';
include 'locales_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nombre_local = $_POST['nombre_local'];
    $ubicacion_local = $_POST['ubicacion_local'];
    $rubro_local = $_POST['rubro_local'];
    $email_dueño = $_POST['email_dueño'];

    $result_dueño = get_dueño_by_email($email_dueño);

    if ($result_dueño -> num_rows > 0){

        $result_local = get_local_by_nombre($nombre_local);
        if(!($result_local -> num_rows > 0)){

            $dueño = $result_dueño -> fetch_assoc();
            $id_dueño = $dueño['id'];

            $qry_alta = "INSERT INTO locales (nombre, ubicacion, rubro, idUsuario) VALUES ('$nombre_local','$ubicacion_local','$rubro_local','$id_dueño')";

            if ($conn->query($qry_alta) === TRUE) {
                echo "Local dado de alta con éxito";
                header("Location: ../public/admin_locales.php");
                exit();

            }else{
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

        }else{

            echo "Ya existe un local con éste nombre: ", $nombre_local;

        }

    }else{

        echo "No se encontró ningún dueño con este mail: ", $email_dueño;

    }
    
}
$conn->close();

?>
