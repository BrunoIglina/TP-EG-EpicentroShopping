<?php

include '../env/shopping_db.php';

$qry_dueño = "SELECT * FROM usuarios WHERE id = '$idUsuario' AND tipo LIKE 'Dueño'";
if(!($result_dueño = $conn->query($qry_dueño))){
    echo "Error: " . $sql . "<br>" . $conn->error;
};

?>