<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_tipo = $_SESSION['user_tipo'];

switch ($user_tipo) {
    case 'Cliente':
        header("Location: cliente_vista.php");
        break;
    case 'Administrador':
        header("Location: admin_vista.php");
        break;
    case 'Dueño':
        header("Location: dueño_vista.php");
        break;
    default:
        echo "Tipo de usuario no válido.";
        break;
}

?>