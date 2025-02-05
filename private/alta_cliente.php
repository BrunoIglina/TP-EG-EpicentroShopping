<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include '../env/shopping_db.php';
require 'envio_mail.php';

$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$tipo = 'Cliente';
$token = bin2hex(random_bytes(16)); // Generar un token de validación
$categoria = 'Inicial';

// Chequeamos que el email no esté registrado en la base de datos
$sql_chequeo = "SELECT * FROM usuarios WHERE email ='$email'";
$result = $conn->query($sql_chequeo);
if ($result->num_rows > 0) {
    echo "El mail ya existe, por favor pruebe con otro";
} else {

// Insertar el cliente en la base de datos con validado = 0 (FALSE) y un token de validación
$sql = "INSERT INTO usuarios (email, password, tipo, validado, token_validacion, categoria) VALUES ('$email', '$password', '$tipo', 0, '$token', '$categoria')";

if ($conn->query($sql) === TRUE) {
    // Enviar email de validación
    sendValidationEmail($email, $token);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}
$conn->close();
?>