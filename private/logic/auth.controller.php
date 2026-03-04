<?php
require_once __DIR__ . '/../config/database.php';

$accion = $_POST['accion'] ?? '';

switch ($accion) {
  case 'login':
    procesar_login();
    break;
    // En el próximo paso agregaremos 'registro', 'recuperar', etc.
}

function procesar_login()
{
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  if (empty($email) || empty($password)) {
    $_SESSION['error'] = "Todos los campos son obligatorios.";
    header("Location: index.php?vista=login");
    exit();
  }

  $conn = getDB();
  $stmt = $conn->prepare("SELECT id, password, tipo, categoria, validado FROM usuarios WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ($user['validado'] == 0) {
      $_SESSION['error'] = "Tu cuenta todavía no ha sido aceptada por el administrador.";
    } else {
      if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_tipo'] = $user['tipo'];
        $_SESSION['user_categoria'] = $user['categoria'];
        $stmt->close();
        header("Location: index.php?vista=landing");
        exit();
      } else {
        $_SESSION['error'] = "Contraseña incorrecta.";
      }
    }
  } else {
    $_SESSION['error'] = "El correo electrónico no está registrado.";
  }

  $stmt->close();
  header("Location: index.php?vista=login");
  exit();
}
