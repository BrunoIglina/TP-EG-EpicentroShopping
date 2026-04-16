<?php
$categoriaCliente = $_SESSION['user_categoria'] ?? 'Inicial';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Promociones Exclusivas | Epicentro Shopping</title>
  <link rel="icon" type="image/png" href="public/assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="public/css/header.css">
  <link rel="stylesheet" href="public/css/footer.css">
  <link rel="stylesheet" href="public/css/wrapper.css">
  <link rel="stylesheet" href="./public/css/back_button.css">
</head>
<body class="bg-light">
  <div class="wrapper">
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main class="container py-5">
    <?php include __DIR__ . '/../../includes/back_button.php'; ?>
      <div class="text-center mb-5">
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm">
           <i class="bi bi-star-fill"></i> Eres Cliente Nivel: <?= htmlspecialchars($categoriaCliente) ?>
        </span>
        <h1 class="fw-bold text-uppercase h2">Promociones para tu Nivel</h1>
        <p class="text-muted fs-5">Explora todas las ofertas disponibles en el shopping que aplican a tu categoría.</p>
      </div>
 
      <div class="row g-4 justify-content-center">
        <?php if (!empty($promos_disponibles)): ?>
          <?php foreach ($promos_disponibles as $row): ?>
            <div class="col-12 col-md-6 col-lg-4 d-flex">
              <div class="card w-100 shadow border-warning-subtle bg-white border-top border-4 rounded-4 overflow-hidden h-100">
                <div class="card-header bg-warning text-dark text-center py-3 border-0">
                  <h5 class="m-0 fw-bold"><i class="bi bi-shop"></i> <?= htmlspecialchars($row["nombre_local"]); ?></h5>
                </div>
                <div class="card-body d-flex flex-column text-center p-4">
                  <h4 class="fw-bold mb-4"><?php echo htmlspecialchars($row["textoPromo"]); ?></h4>
                  
                  <ul class="list-unstyled mb-4 text-start small text-muted bg-light p-3 rounded-3">
                    <li class="mb-2"><i class="bi bi-calendar-check text-warning"></i> <strong>Válido:</strong> <?= date('d/m/Y', strtotime($row["fecha_inicio"])) ?> al <?= date('d/m/Y', strtotime($row["fecha_fin"])) ?></li>
                    <li><i class="bi bi-clock text-warning"></i> <strong>Días:</strong> <?= htmlspecialchars(str_replace(',', ', ', $row["diasSemana"])) ?></li>
                  </ul>

                  <div class="mt-auto">
                    <a href="index.php?vista=promociones&local_id=<?= $row['local_id']; ?>" class="btn btn-outline-dark w-100 py-2 fw-bold shadow-sm">
                      Ir al Local para Solicitar
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center py-5">
            <i class="bi bi-emoji-frown text-muted" style="font-size: 3rem;"></i>
            <h4 class="text-muted mt-3">No hay promociones globales disponibles para tu nivel actualmente.</h4>
            <a href="index.php?vista=locales" class="btn btn-primary mt-3">Volver a Locales</a>
          </div>
        <?php endif; ?>
      </div>
    </main>
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>