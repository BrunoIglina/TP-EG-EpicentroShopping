<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Inicio | Epicentro Shopping - Tu Centro de Compras y Promociones</title>

  <link rel="icon" type="image/png" href="public/assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="public/css/fix_header.css">
  <link rel="stylesheet" href="public/css/footer.css">
  <link rel="stylesheet" href="public/css/header.css">
  <link rel="stylesheet" href="public/css/wrapper.css">
  <link rel="stylesheet" href="public/css/tarjetas.css">
  <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">
</head>

<body>
  <a href="#main-content" class="visually-hidden-focusable text-center d-block bg-dark text-white py-2">
    Saltar al contenido principal
  </a>

  <header class="ratio ratio-16x9 position-relative">
    <video src="public/assets/file.mp4" class="w-100" autoplay muted loop playsinline aria-hidden="true"></video>
    <div class="d-flex justify-content-center align-items-center position-absolute top-0 start-0 w-100 h-100 text-white text-center"
      style="background: rgba(0, 0, 0, 0.5);">
      <h1 class="display-4 fw-bold">BIENVENIDO A SHOPPING EPICENTRO</h1>
    </div>
  </header>

  <div class="wrapper">
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main id="main-content" class="container-fluid px-4 py-5" style="overflow-x: hidden;">

      <?php if (isset($_SESSION['mensaje_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show text-center mb-4" role="alert">
          <?= htmlspecialchars($_SESSION['mensaje_error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
        <?php unset($_SESSION['mensaje_error']); ?>
      <?php endif; ?>

      <h2 class="text-center mb-5 fw-bold text-uppercase" style="letter-spacing: 2px;">
        Nuestros Locales más solicitados
      </h2>

      <div class="row g-4 m-0 p-4" style="background-color:rgba(32, 40, 51, 0.04); border-radius: 30px;">

        <?php foreach ($locales as $local): ?>
          <div class="col-12 col-md-6 col-lg-3">
            <a href="index.php?vista=promociones&local_id=<?= $local['id'] ?>"
              class="card-link text-decoration-none h-100 d-block">

              <div class="card text-center h-100 shadow-sm border-0 rounded-4 overflow-hidden card-hover">
                <div class="card-image" style="height: 200px; overflow: hidden;">
                  <img src="index.php?vista=imagen&local_id=<?= $local['id'] ?>"
                    alt="Logotipo del local <?= htmlspecialchars($local['nombre']) ?>"
                    class="w-100 h-100" style="object-fit: cover;">
                </div>

                <div class="card-body d-flex flex-column p-4">
                  <h3 class="h5 card-title text-dark fw-bold mb-3"><?= htmlspecialchars($local['nombre']) ?></h3>

                  <div class="mt-auto">
                    <span class="badge bg-primary text-uppercase mb-2" style="font-size: 0.75rem;">
                      <?= htmlspecialchars($local['rubro']) ?>
                    </span>
                    <p class="card-text text-muted small m-0">
                      <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($local['ubicacion']) ?>
                    </p>
                  </div>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>

      </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>