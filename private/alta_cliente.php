<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');

require 'envio_mail.php';

$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$tipo = 'Cliente';
$token = bin2hex(random_bytes(16)); 
$categoria = 'Inicial';

$sql_chequeo = "SELECT * FROM usuarios WHERE email ='$email'";
$result = $conn->query($sql_chequeo);
if ($result->num_rows > 0) {
    echo "El mail ya existe, por favor pruebe con otro";
} else {

$sql = "INSERT INTO usuarios (email, password, tipo, validado, token_validacion, categoria) VALUES ('$email', '$password', '$tipo', 0, '$token', '$categoria')";

if ($conn->query($sql) === TRUE) {
    sendValidationEmail($email, $token);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}
$conn->close();
?>