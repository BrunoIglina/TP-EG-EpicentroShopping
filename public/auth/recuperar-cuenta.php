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

  <title>Epicentro Shopping - Recuperar Cuenta</title>
</head>

<body class="auth-page">
  <div class="wrapper">
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    <?php include __DIR__ . '/../../includes/back_button.php'; ?>

    <main>
      <div class="auth-container">
        <section class="auth-form">
          <h2>Recuperar Cuenta</h2>

          <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
              <?php echo htmlspecialchars($_SESSION['error']);
              unset($_SESSION['error']); ?>
            </div>
          <?php endif; ?>

          <form action="index.php" method="post">
            <input type="hidden" name="modulo" value="auth">
            <input type="hidden" name="accion" value="recuperar">

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" placeholder="tu@email.com" required autofocus>

            <button type="submit">Enviar Instrucciones</button>
          </form>
        </section>
      </div>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>