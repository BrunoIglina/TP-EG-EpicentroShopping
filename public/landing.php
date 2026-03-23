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
  <link rel="icon" type="image/png" href="public/assets/logo2.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./public/css/fix_header.css">
  <link rel="stylesheet" href="./public/css/footer.css">
  <link rel="stylesheet" href="./public/css/header.css">
  <link rel="stylesheet" href="./public/css/wrapper.css">
  <link rel="stylesheet" href="./public/css/tarjetas.css">
  <link rel="stylesheet" href="./public/css/styles_fondo_and_titles.css">
  <title>Epicentro Shopping - Inicio</title>
</head>

<body>
  <div class="ratio ratio-16x9 position-relative">
    <video src="./public/assets/file.mp4" class="w-100" autoplay muted loop playsinline></video>
    <div
      class="d-flex justify-content-center align-items-center position-absolute top-0 start-0 w-100 h-100 text-white text-center"
      style="background: rgba(0, 0, 0, 0.5);">
      <h1>BIENVENIDO A SHOPPING EPICENTRO</h1>
    </div>
  </div>

  <div class="wrapper">
    <?php include __DIR__ . './../includes/header.php'; ?>

    <main class="container-fluid px-3" style="overflow-x: hidden;">

      <?php
      if (isset($_SESSION['mensaje_error'])) {
        echo "<div class='alert alert-danger text-center mt-3'>" . htmlspecialchars($_SESSION['mensaje_error']) . "</div>";
        unset($_SESSION['mensaje_error']);
      }
      ?>

      <h2 class="my-4">NUESTROS LOCALES MAS SOLICITADOS</h2>

      <div class="row g-4 m-0"
        style="background-color:rgba(32, 40, 51, 0.06); padding: 1.5rem 0.5rem; border-radius: 20px;">

        <?php foreach ($locales as $local) { ?>
          <div class="col-12 col-md-6 col-lg-3">
            <a href="promociones.php?local_id=<?php echo $local['id']; ?>&local_nombre=<?php echo urlencode($local['nombre']); ?>&local_rubro=<?php echo urlencode($local['rubro']); ?>"
              class="card-link text-decoration-none">

              <div class="card text-center h-100 shadow-sm">
                <div class="card-body d-flex flex-column">

                  <div class="card-image mb-3" style="height: 180px; overflow: hidden; border-radius: 5px;">
                    <?php echo '<img src="index.php?vista=imagen&local_id=' . $local['id'] . '" alt="Imagen de ' . htmlspecialchars($local['nombre']) . '" class="w-100 h-100" style="object-fit: cover;">'; ?>
                  </div>

                  <h4 class="card-title text-dark"><?php echo htmlspecialchars($local['nombre']); ?></h4>

                  <p class="card-text text-muted mt-auto">
                    <strong><?php echo htmlspecialchars($local['rubro']); ?></strong><br>
                    <?php echo htmlspecialchars($local['ubicacion']); ?>
                  </p>

                </div>
              </div>
            </a>
          </div>
        <?php } ?>

      </div>
    </main>

    <?php include __DIR__ . './../includes/footer.php'; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>