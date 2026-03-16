<?php
require_once __DIR__ . '/../config/database.php';

$accion = $_POST['accion'] ?? '';


switch ($accion) {
  case 'login':
    procesar_login();
    break;
  case 'registro':
    procesar_registro();
    break;
  case 'recuperar':
    procesar_recuperar();
    break;
  case 'verificar':
    procesar_verificar();
    break;
  case 'cambiar_password':
    procesar_cambiar_password();
    break;
  default:

    header("Location: index.php");
    exit();
}

function procesar_login()
{
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');

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

function procesar_registro()
{
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $confirm_password = trim($_POST['confirm_password'] ?? '');
  $tipo = trim($_POST['tipo'] ?? '');

  if (empty($email) || empty($password) || empty($confirm_password) || empty($tipo)) {
    $_SESSION['error'] = "Todos los campos son obligatorios.";
    header("Location: index.php?vista=registro");
    exit();
  }

  if ($password !== $confirm_password) {
    $_SESSION['error'] = "Las contraseñas no coinciden.";
    header("Location: index.php?vista=registro");
    exit();
  }

  $conn = getDB();

  $checkStmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
  $checkStmt->bind_param("s", $email);
  $checkStmt->execute();
  $checkResult = $checkStmt->get_result();

  if ($checkResult->num_rows > 0) {
    $_SESSION['error'] = "El correo electrónico '$email' ya está registrado. Intenta con otro o inicia sesión.";
    $checkStmt->close();
    header("Location: index.php?vista=registro");
    exit();
  }
  $checkStmt->close();

  $_POST['email'] = $email;
  $_POST['password'] = $password;

  if ($tipo === 'Cliente') {
    $_POST['action'] = 'registrar_cliente';
  } else {
    $_POST['action'] = 'registrar_dueno';
  }

  ob_start();
  include(__DIR__ . '/crud/usuarios.php');
  $response = ob_get_clean();

  if (stripos($response, "exitoso") !== false) {
    $_SESSION['success'] = $response;
  } else {
    $_SESSION['error'] = $response;
  }

  header("Location: index.php?vista=registro");
  exit();
}

function procesar_recuperar()
{
  require_once __DIR__ . '/helpers/email.php';

  $email = trim($_POST['email'] ?? '');

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Correo electrónico inválido.";
    header("Location: index.php?vista=recuperar");
    exit();
  }

  $conn = getDB();

  $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $stmt->close();

  if ($user) {
    $resultado = enviar_codigo_verificacion($email);

    if ($resultado === true) {
      header("Location: index.php?vista=verificar&email=" . urlencode($email));
      exit();
    } else {
      $_SESSION['error'] = $resultado;
      header("Location: index.php?vista=recuperar");
      exit();
    }
  } else {
    $_SESSION['error'] = "Correo electrónico no registrado.";
    header("Location: index.php?vista=recuperar");
    exit();
  }
}

function procesar_verificar()
{
  $entered_code = trim($_POST['verification_code'] ?? '');
  $email = trim($_POST['email'] ?? '');

  if (isset($_SESSION['verification_code']) && $entered_code == $_SESSION['verification_code']) {
    unset($_SESSION['verification_code']);
    $_SESSION['code_verified'] = true;

    header("Location: index.php?vista=cambiar_password&email=" . urlencode($email));
    exit();
  } else {
    $_SESSION['error'] = "Código de verificación incorrecto.";
    header("Location: index.php?vista=verificar&email=" . urlencode($email));
    exit();
  }
}

function procesar_cambiar_password()
{
  $new_password_raw = trim($_POST['new_password'] ?? '');
  $confirm_password = trim($_POST['confirm_password'] ?? '');
  $email = trim($_POST['email'] ?? '');

  if ($new_password_raw !== $confirm_password) {
    $_SESSION['error'] = "Las contraseñas no coinciden.";
    header("Location: index.php?vista=cambiar_password&email=" . urlencode($email));
    exit();
  }

  $conn = getDB();
  $new_password = password_hash($new_password_raw, PASSWORD_BCRYPT);

  // Si viene desde la sesión iniciada
  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
    $stmt->bind_param('si', $new_password, $user_id);
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['code_verified']);
    $_SESSION['success'] = "Contraseña cambiada exitosamente.";

    header('Location: index.php?vista=cliente_perfil');
    exit();
  }
  // Si viene del flujo de recuperación de email
  elseif ($email) {
    $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
    $stmt->bind_param('ss', $new_password, $email);
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['verification_code']);
    $_SESSION['success'] = "Contraseña cambiada exitosamente. Por favor, inicia sesión.";

    header('Location: index.php?vista=login');
    exit();
  } else {
    $_SESSION['error'] = "Correo electrónico no proporcionado.";
    header("Location: index.php?vista=cambiar_password");
    exit();
  }
}
