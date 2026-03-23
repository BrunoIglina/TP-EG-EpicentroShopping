<?php
$email = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

  <link rel="icon" type="image/png" href="assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/auth.css">
  <link rel="stylesheet" href="css/back_button.css">
  <link rel="stylesheet" href="css/fix_header.css">

  <title>Cambiar Contraseña</title>
</head>

<body class="auth-page">
  <div class="wrapper">
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    <?php include __DIR__ . '/../../includes/back_button.php'; ?>

    <main>
      <div class="auth-container">
        <section class="auth-form">
          <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
              <?php echo htmlspecialchars($_SESSION['error']);
              unset($_SESSION['error']); ?>
            </div>
          <?php endif; ?>

          <h2>Cambiar Contraseña</h2>

          <div id="passwordMatch" class="mt-2" style="display: none;"></div>

          <form action="index.php" method="POST" id="passwordForm">
            <input type="hidden" name="modulo" value="auth">
            <input type="hidden" name="accion" value="cambiar_password">

            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

            <label for="new_password">Nueva Contraseña:</label>
            <input type="password" id="new_password" name="new_password" required minlength="6">

            <label for="confirm_password">Confirmar Nueva Contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">

            <button type="submit" id="submitBtn" class="btn-primary" disabled>Cambiar Contraseña</button>
          </form>
        </section>
      </div>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordMatchDiv = document.getElementById('passwordMatch');
    const submitBtn = document.getElementById('submitBtn');

    function validatePasswords() {
      const newPassword = newPasswordInput.value;
      const confirmPassword = confirmPasswordInput.value;

      if (newPassword && confirmPassword) {
        if (newPassword === confirmPassword) {
          passwordMatchDiv.innerHTML = '<div class="alert alert-success mb-0">✓ Las contraseñas coinciden</div>';
          passwordMatchDiv.style.display = 'block';
          submitBtn.disabled = false;
        } else {
          passwordMatchDiv.innerHTML = '<div class="alert alert-danger mb-0">✗ Las contraseñas no coinciden</div>';
          passwordMatchDiv.style.display = 'block';
          submitBtn.disabled = true;
        }
      } else {
        passwordMatchDiv.style.display = 'none';
        submitBtn.disabled = true;
      }
    }

    newPasswordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);
  </script>
</body>

</html>