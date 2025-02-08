<?php

function get_dueño($idUsuario){
    include '../env/shopping_db.php';
    $qry_dueño = "SELECT * FROM usuarios WHERE id = '$idUsuario' AND tipo LIKE 'Dueño'";
    if(!($result_dueño = $conn->query($qry_dueño))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        $dueño = $result_dueño -> fetch_assoc();
        return $dueño;
    };
}

function get_dueño_by_email($email){
    include '../env/shopping_db.php';
    $qry_dueño = "SELECT * FROM usuarios WHERE email LIKE '$email' AND tipo LIKE 'Dueño'";
    if(!($result_dueño = $conn->query($qry_dueño))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        $dueño = $result_dueño -> fetch_assoc();
        return $dueño;
    };
}

function get_all_dueños(){
    include '../env/shopping_db.php';
    $qry_dueño = "SELECT * FROM usuarios WHERE tipo LIKE 'Dueño'";
    if(!($result_dueño = $conn->query($qry_dueño))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        $dueños = $result_dueño -> fetch_all(MYSQLI_ASSOC);
        return $dueños;
    };
}

function get_usuario($id){
    include '../en/shopping_db.php';
    $qry = "SELECT * FROM usuarios WHERE id = '$id'";
    if(!($result_usuario = $conn->query($qry))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        $usuario = $result_usuario -> fetch_assoc();
        return $usuario;
    }
}

function get_categorias() {
    return ['Inicial', 'Medium', 'Premium'];
}


?>