<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../env/shopping_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        echo "Faltan datos en el formulario.";
        exit;
    }
    
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Verificar si el email tiene un formato válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato de correo inválido.";
        exit;
    }
    
    // Preparar la consulta con Prepared Statements
    $stmt = $conn->prepare("SELECT id, email, password, tipo, categoria FROM usuarios WHERE email = ? AND validado = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_tipo'] = $row['tipo'];
            $_SESSION['user_categoria'] = $row['categoria'];
            
            echo "Inicio de sesión exitoso.";
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "No se encontró una cuenta con ese correo electrónico o la cuenta no está validada.";
    }

    $stmt->close();
}
$conn->close();
?>
