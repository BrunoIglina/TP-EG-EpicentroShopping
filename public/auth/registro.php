<?php
session_start();
require_once __DIR__ . '/../../private/config/database.php';
require_once __DIR__ . '/../../includes/navigation_history.php';
require_once __DIR__ . '/../../includes/security_headers.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);
	$tipo = trim($_POST['tipo']);

	if (empty($email) || empty($password) || empty($confirm_password) || empty($tipo)) {
		$_SESSION['error'] = "Todos los campos son obligatorios.";
		header("Location: registro.php");
		exit();
	}

	if ($password !== $confirm_password) {
		$_SESSION['error'] = "Las contraseñas no coinciden.";
		header("Location: registro.php");
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
		header("Location: registro.php");
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
	include(__DIR__ . '/../../private/logic/crud/usuarios.php');
	$response = ob_get_clean();

	if (stripos($response, "exitoso") !== false) {
		$_SESSION['success'] = $response;
	} else {
		$_SESSION['error'] = $response;
	}

	header("Location: registro.php");
	exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

	<link rel="icon" type="image/png" href="../assets/logo2.png">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/header.css">
	<link rel="stylesheet" href="../css/footer.css">
	<link rel="stylesheet" href="../css/auth.css">
	<link rel="stylesheet" href="../css/styles_fondo_and_titles.css">
	<link rel="stylesheet" href="../css/back_button.css">
	<link rel="stylesheet" href="../css/fix_header.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

	<title>Epicentro Shopping - Registrarse</title>
</head>

<body class="auth-page">
	<div class="wrapper">
		<?php include __DIR__ . '/../../includes/header.php'; ?>
		<?php include __DIR__ . '/../../includes/back_button.php'; ?>
		<main>
			<div class="auth-container">
				<section class="auth-form">
					<h2 style="font-family: 'Poppins', sans-serif;">Registrarse</h2>
					<?php
					if (isset($_SESSION['error'])) {
						echo "<p class='text-danger'>" . htmlspecialchars($_SESSION['error']) . "</p>";
						unset($_SESSION['error']);
					}
					if (isset($_SESSION['success'])) {
						echo "<p class='text-success'>" . htmlspecialchars($_SESSION['success']) . "</p>";
						unset($_SESSION['success']);
					}
					?>
					<form action="registro.php" method="post">
						<label for="email">Correo Electrónico:</label>
						<input type="email" id="email" name="email" required>

						<label for="password">Contraseña:</label>
						<input type="password" id="password" name="password" required>

						<label for="confirm-password">Confirmar Contraseña:</label>
						<input type="password" id="confirm-password" name="confirm_password" required>

						<label for="tipo">Tipo:</label>
						<select id="tipo" name="tipo" required>
							<option value="" disabled selected>Selecciona un tipo</option>
							<option value="Cliente">Cliente</option>
							<option value="Dueno">Dueño</option>
						</select>

						<button type="submit" class="btn btn-register">Registrarse</button>
					</form>
				</section>
			</div>
		</main>
		<?php include __DIR__ . '/../../includes/footer.php'; ?>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		document.querySelector('form').addEventListener('submit', function(event) {
			const password = document.getElementById('password').value;
			const confirmPassword = document.getElementById('confirm-password').value;

			if (password !== confirmPassword) {
				event.preventDefault();
				alert('Las contraseñas no coinciden. Por favor, verifica que ambas sean iguales.');
				return false;
			}
		});
	</script>
</body>

</html>