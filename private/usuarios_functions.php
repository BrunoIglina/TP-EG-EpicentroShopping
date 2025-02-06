<?php

function get_dueño($idUsuario){
include '../env/shopping_db.php';
$qry_dueño = "SELECT * FROM usuarios WHERE id = '$idUsuario' AND tipo LIKE 'Dueño'";
    if(!($result_dueño = $conn->query($qry_dueño))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        return $result_dueño;
    };
}

function get_dueño_by_email($email){
    include '../env/shopping_db.php';
    $qry_dueño = "SELECT * FROM usuarios WHERE email LIKE '$email' AND tipo LIKE 'Dueño'";
    if(!($result_dueño = $conn->query($qry_dueño))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        return $result_dueño;
    };
}

?>