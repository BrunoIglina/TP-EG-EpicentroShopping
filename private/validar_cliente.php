<?php
include '../env/shopping_db.php';
$token = $_GET['token'];

$sql = "UPDATE usuarios SET validado = 1 WHERE token_validacion = '$token'";

if ($conn->query($sql) === TRUE) {
    echo "Cuenta validada correctamente, inicie sesión para continuar.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>