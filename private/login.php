<?php
session_start();
include '../env/shopping_db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Buscar el usuario en la base de datos
$sql = "SELECT * FROM usuarios WHERE email = '$email' AND validado = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verificar la contraseña
    if (password_verify($password, $row['password'])) {
        // Establecer variables de sesión
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_email'] = $row['email'];
        $_SESSION['user_tipo'] = $row['tipo'];
        
        header("Location: ../public/index.php");
        exit();
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "No se encontró una cuenta con ese correo electrónico o la cuenta no está validada.";
}

$conn->close();
?>