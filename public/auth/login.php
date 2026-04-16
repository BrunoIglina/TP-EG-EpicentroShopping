<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon" type="image/png" href="assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./public/css/header.css">
  <link rel="stylesheet" href="./public/css/footer.css">
  <link rel="stylesheet" href="./public/css/auth.css">
  <link rel="stylesheet" href="./public/css/sytles_fondo_and_titles.css">
  <link rel="stylesheet" href="./public/css/back_button.css">
  <link rel="stylesheet" href="./public/css/fix_header.css">
  <link rel="stylesheet" href="./public/css/login.css">

  <title>Epicentro Shopping - Iniciar Sesión</title>

</head>

<body class="auth-page">
  <div class="wrapper">
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    <?php include __DIR__ . '/../../includes/back_button.php'; ?>

    <main>
      <div class="auth-container">
        <section class="auth-form">
          <h2>Iniciar Sesión</h2>

          <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
              <?php echo htmlspecialchars($_SESSION['success']);
              unset($_SESSION['success']); ?>
            </div>
          <?php endif; ?>

          <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
              <?php echo htmlspecialchars($_SESSION['error']);
              unset($_SESSION['error']); ?>
            </div>
          <?php endif; ?>

          <form action="index.php" method="post">
            <input type="hidden" name="modulo" value="auth">
            <input type="hidden" name="accion" value="login">

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <div class="password-wrapper">
              <input type="checkbox" id="toggle_login_pwd" class="toggle-checkbox">
              
              <input type="text" id="password" name="password" class="masked-input form-control" required>
              
              <label for="toggle_login_pwd" class="toggle-label" title="Mostrar/Ocultar">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                </svg>
              </label>
            </div>

            <button type="submit" class="btn btn-login">Ingresar</button>
          </form>

          <button class="btn btn-register"
            onclick="window.location.href='index.php?vista=registro'">Registrarse</button>
          <a href="index.php?vista=recuperar" aria-label="Recuperar contraseña">¿Olvidaste tu contraseña?</a>
        </section>
      </div>
    </main>
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
  </div>
</body>

</html>