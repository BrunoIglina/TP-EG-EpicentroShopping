<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Cliente') {
  header("Location: index.php?vista=login");
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="./css/footer.css">
  <link rel="stylesheet" href="./css/header.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <link rel="icon" type="image/png" href="./assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/mis_promociones.css">
  <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
  <link rel="stylesheet" href="./css/back_button.css">
  <link rel="stylesheet" href="./css/fix_header.css">

  <title>Epicentro Shopping - Mis Promociones</title>
</head>

<body>
  <div class="wrapper">
    <?php include __DIR__ . '/../../includes/header.php'; ?>


    <main class="container">
      <div class="row align-items-center mb-5 mt-3">
        <div class="col-2 col-md-1 text-start">
          <?php include __DIR__ . '/../../includes/back_button.php'; ?>
        </div>

        <div class="col-8 col-md-10">
          <h2 class="text-center m-0 fw-bold text-uppercase" style="letter-spacing: 1px;">
            Mis Promociones
          </h2>
        </div>

        <div class="col-2 col-md-1"></div>
      </div>
      <div id="misPromocionesContainer" class="row">
        <?php
        if (!empty($promos)) {
          foreach ($promos as $row) {
            echo "<div class='col-lg-6 col-md-12 mb-4'>";
            echo "<div class='card'>";
            echo "<div class='card-body'>";
            echo "<h2 class='card-title'>" . htmlspecialchars($row["nombre"]) . "</h2>";
            echo "<p><strong>" . htmlspecialchars($row["textoPromo"]) . "</strong></p>";
            echo "<p>Fecha de Inicio: " . htmlspecialchars($row["fecha_inicio"]) . "</p>";
            echo "<p>Fecha de Fin: " . htmlspecialchars($row["fecha_fin"]) . "</p>";
            echo "<p>Días de la Semana: " . htmlspecialchars(str_replace(',', ', ', $row["diasSemana"])) . "</p>";
            echo "<p>Estado: " . htmlspecialchars($row["estado"]) . "</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
          }
        } else {
          echo "<p>No tienes promociones solicitadas.</p>";
        }
        ?>
      </div>

      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <li class="page-item <?php if ($page <= 1) {
                                  echo 'disabled';
                                } ?>">
            <a class="page-link" href="index.php?vista=cliente_promociones&page=<?php echo $page - 1; ?>">Anterior</a>
          </li>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if ($page == $i) {
                                    echo 'active';
                                  } ?>">
              <a class="page-link" href="index.php?vista=cliente_promociones&page=<?= $i; ?>"><?= $i; ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?php if ($page >= $total_pages) {
                                  echo 'disabled';
                                } ?>">
            <a class="page-link" href="index.php?vista=cliente_promociones&page=<?php echo $page + 1; ?>">Siguiente</a>
          </li>
        </ul>
      </nav>
    </main>
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>