<?php
include '../env/shopping_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nombre_local = $_POST['nombre_local'];
    $ubicacion_local = $_POST['ubicacion_local'];
    $rubro_local = $_POST['rubro_local'];
    $email_dueño = $_POST['email_dueño'];

    $qry_dueño = "SELECT * FROM usuarios WHERE email LIKE '$email_dueño' AND tipo like 'Dueño'";

    if($result_dueño = $conn->query($qry_dueño)){

        if ($result_dueño -> num_rows > 0){

            $dueño = $result_dueño -> fetch_assoc();
            $id_dueño = $dueño['id'];
            $qry_alta = "INSERT INTO locales (nombre, ubicacion, rubro, idUsuario) VALUES ('$nombre_local','$ubicacion_local','$rubro_local','$id_dueño')";

            if ($conn->query($qry_alta) === TRUE) {
                // Enviar email de validación
                echo "Local dado de alta con éxito";
                header("Location: ../public/admin_locales.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
        }else{
            echo "No se encontró ningún dueño con este mail: ", $email_dueño;
            }
    }else{
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
