<?php
include '../env/shopping_db.php';

$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$tipo = 'Dueño';

//se inserta al dueño en la base de datos con validado = 0 (false)
$sql = "INSERT INTO usuarios (email, password, tipo, validado) VALUES ('$email', '$password', '$tipo', 0)";

if ($conn->query($sql) === TRUE) {
    echo "Registro exitoso, el Administrador debe validar su cuenta";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>