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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <link rel="icon" type="image/png" href="public/assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  
  <link rel="stylesheet" href="public/css/footer.css">
  <link rel="stylesheet" href="public/css/header.css">
  <link rel="stylesheet" href="public/css/mis_promociones.css">
  <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">
  <link rel="stylesheet" href="public/css/back_button.css">
  <link rel="stylesheet" href="public/css/fix_header.css">

  <title>Mis Promociones Solicitadas | Epicentro Shopping</title>

  <style>
    /* SOLUCIÓN AL ERROR DE CONTRASTE (Pauta 1.4.3) */
    /* El texto de la promo ahora es un azul oscuro/negro para pasar el ratio 4.5:1 */
    .promo-text-highlight {
        color: #1a1a1a !important; 
        font-size: 1.2rem;
    }
    .card-title-custom {
        color: #0d6efd;
        font-weight: 700;
    }
  </style>
</head>

<body>
  <a href="#main-content" class="visually-hidden-focusable text-center d-block bg-dark text-white py-2">
      Saltar al contenido principal
  </a>

  <div class="wrapper">
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main id="main-content" class="container py-4">
      <div class="row align-items-center mb-5 mt-3">
        <div class="col-2 col-md-1 text-start">
          <?php include __DIR__ . '/../../includes/back_button.php'; ?>
        </div>

        <div class="col-8 col-md-10">
          <h1 class="text-center m-0 fw-bold text-uppercase h2" style="letter-spacing: 1px;">
            Mis Promociones
          </h1>
        </div>

        <div class="col-2 col-md-1"></div>
      </div>

      <div id="misPromocionesContainer" class="row g-4">
        <?php
        if (!empty($promos)) {
          foreach ($promos as $row) {
            ?>
            <div class="col-lg-6 col-md-12">
              <div class="card h-100 shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                  <h2 class="h4 card-title-custom mb-3"><?php echo htmlspecialchars($row["nombre"]); ?></h2>
                  
                  <p class="mb-3">
                    <strong class="promo-text-highlight">
                        <?php echo htmlspecialchars($row["textoPromo"]); ?>
                    </strong>
                  </p>
                  
                  <div class="text-muted small">
                    <p class="mb-1"><i class="bi bi-calendar-check"></i> <strong>Vigencia:</strong> <?php echo htmlspecialchars($row["fecha_inicio"]); ?> al <?php echo htmlspecialchars($row["fecha_fin"]); ?></p>
                    <p class="mb-1"><strong>Días:</strong> <?php echo htmlspecialchars(str_replace(',', ', ', $row["diasSemana"])); ?></p>
                    
                    <?php 
                      $estado_class = ($row["estado"] === 'aceptada') ? 'text-success' : 'text-primary';
                    ?>
                    <p class="mb-0"><strong>Estado:</strong> <span class="fw-bold <?php echo $estado_class; ?> text-uppercase"><?php echo htmlspecialchars($row["estado"]); ?></span></p>
                  </div>
                </div>
              </div>
            </div>
            <?php
          }
        } else {
          echo "<div class='col-12 text-center py-5'><p class='fs-5 text-muted'>Aún no has solicitado ninguna promoción.</p></div>";
        }
        ?>
      </div>

      <?php if ($total_pages > 1): ?>
      <nav aria-label="Navegación de mis promociones" class="mt-5">
        <ul class="pagination justify-content-center">
          <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?vista=cliente_promociones&page=<?php echo $page - 1; ?>" <?php echo ($page <= 1) ? 'aria-disabled="true"' : ''; ?>>Anterior</a>
          </li>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
              <a class="page-link" href="index.php?vista=cliente_promociones&page=<?= $i; ?>" <?php echo ($page == $i) ? 'aria-current="page"' : ''; ?>><?= $i; ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?vista=cliente_promociones&page=<?php echo $page + 1; ?>" <?php echo ($page >= $total_pages) ? 'aria-disabled="true"' : ''; ?>>Siguiente</a>
          </li>
        </ul>
      </nav>
      <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>