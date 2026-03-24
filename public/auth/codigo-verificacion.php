<?php
$email = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

  <link rel="icon" type="image/png" href="./public/assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./public/css/header.css">
  <link rel="stylesheet" href="./public/css/footer.css">
  <link rel="stylesheet" href="./public/css/auth.css">
  <link rel="stylesheet" href="./public/css/back_button.css">
  <link rel="stylesheet" href="./public/css/fix_header.css">

  <title>Verificar Código</title>
</head>

<body class="auth-page">
  <div class="wrapper">
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    <?php include __DIR__ . '/../../includes/back_button.php'; ?>

    <main>
      <div class="auth-container">
        <section class="auth-form">
          <h2>Verificar Código</h2>

          <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
              <?php echo htmlspecialchars($_SESSION['error']);
              unset($_SESSION['error']); ?>
            </div>
          <?php endif; ?>

          <form action="index.php" method="POST">
            <input type="hidden" name="modulo" value="auth">
            <input type="hidden" name="accion" value="verificar">

            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

            <div class="form-group">
              <label for="verification_code">Código de Verificación:</label>
              <input type="text" id="verification_code" name="verification_code" required>
            </div>
            <button type="submit" class="btn-primary">Verificar</button>
          </form>
        </section>
      </div>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>