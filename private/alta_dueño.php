<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include '../env/shopping_db.php';

$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$tipo = 'Dueño';

// Chequeamos que el email no esté registrado en la base de datos
$sql_chequeo = "SELECT * FROM usuarios WHERE email ='$email'";
$result = $conn->query($sql_chequeo);
if ($result->num_rows > 0) {
    echo "El mail ya existe, por favor pruebe con otro";
} else {
    // Se inserta al dueño en la base de datos con validado = 0 (false)
    $sql = "INSERT INTO usuarios (email, password, tipo, validado) VALUES ('$email', '$password', '$tipo', 0)";
    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso, el Administrador debe validar su cuenta";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
