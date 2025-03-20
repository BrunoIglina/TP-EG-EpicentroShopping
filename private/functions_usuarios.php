<?php

function get_dueño($idUsuario){
        // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
        include(__DIR__ . '/../env/shopping_db.php');


    $qry_dueño = "SELECT * FROM usuarios WHERE id = '$idUsuario' AND tipo LIKE 'Dueno'";
    if(!($result_dueño = $conn->query($qry_dueño))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        $dueño = $result_dueño -> fetch_assoc();
        return $dueño;
    };
}

function get_dueño_by_email($email) {
    global $conn; 

    $query = "SELECT id FROM usuarios WHERE email = ? AND tipo = 'Dueno'";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc(); 
}

function get_all_dueños(){
        // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
        include(__DIR__ . '/../env/shopping_db.php');


    $qry_dueño = "SELECT * FROM usuarios WHERE tipo LIKE 'Dueno'";
    if(!($result_dueño = $conn->query($qry_dueño))){
        echo "Error: " . $sql . "<br>" . $conn->error;
    }else{
        $dueños = $result_dueño -> fetch_all(MYSQLI_ASSOC);
        return $dueños;
    };
}

function get_usuario($id){
        // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
        include(__DIR__ . '/../env/shopping_db.php');

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