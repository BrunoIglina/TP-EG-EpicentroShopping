<?php
if (!isset($_GET['local_id'])) {
  header("Location: index.php?vista=locales");
  exit;
}

$categoriaCliente = $_SESSION['user_categoria'] ?? null;
$tipoUsuario = $_SESSION['user_tipo'] ?? 'Visitante';
$clienteId = $_SESSION['user_id'] ?? 0;


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
  <link rel="stylesheet" href="public/css/styles_fondo_and_titles.css">
  <link rel="stylesheet" href="public/css/tarjetas.css">
  <link rel="stylesheet" href="public/css/back_button.css">
  <link rel="stylesheet" href="public/css/fix_header.css">
  <link rel="stylesheet" href="public/css/wrapper.css">

  <title>Promociones - <?php echo htmlspecialchars($local["nombre"]); ?></title>
</head>

<body>
  <div class="wrapper">
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main class="container py-4">
      <div class="row align-items-center mb-5 mt-2">
        <div class="col-2 col-md-1 text-start">
          <?php include __DIR__ . '/../../includes/back_button.php'; ?>
        </div>
        <div class="col-8 col-md-10">
          <h2 class="text-center m-0 fw-bold text-uppercase" style="letter-spacing: 1px;">
            <?php echo htmlspecialchars($local["nombre"]); ?>
          </h2>
          <p class="text-center text-muted m-0 mt-1">Promociones Disponibles</p>
        </div>
        <div class="col-2 col-md-1"></div>
      </div>

      <?php
      if (isset($_SESSION['mensaje_error'])) {
        echo "<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>"
          . htmlspecialchars($_SESSION['mensaje_error']) .
          "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        unset($_SESSION['mensaje_error']);
      }
      if (isset($_SESSION['mensaje_exito'])) {
        echo "<div class='alert alert-success alert-dismissible fade show text-center' role='alert'>"
          . htmlspecialchars($_SESSION['mensaje_exito']) .
          "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        unset($_SESSION['mensaje_exito']);
      }
      ?>

      <div class="row g-4 mb-4 justify-content-center">
        <?php if (!empty($promos)): ?>
          <?php foreach ($promos as $row): ?>
            <div class="col-12 col-md-6 col-lg-4 d-flex">
              <div class="card w-100 shadow-sm border-0 rounded-4 overflow-hidden h-100">
                <div class="card-header bg-dark text-white text-center py-3">
                  <span class="badge bg-primary px-3 py-2 text-uppercase">
                    Nivel: <?php echo htmlspecialchars($row["categoriaCliente"]); ?>
                  </span>
                </div>
                <div class="card-body d-flex flex-column text-center p-4">
                  <h4 class="fw-bold mb-3"><?php echo htmlspecialchars($row["textoPromo"]); ?></h4>

                  <div class="mb-3 small text-muted">
                    <div class="mb-1"><strong>Vigencia:</strong></div>
                    <div><?php echo htmlspecialchars($row["fecha_inicio"]); ?> al <?php echo htmlspecialchars($row["fecha_fin"]); ?></div>
                  </div>

                  <div class="mb-4 small">
                    <strong>Días:</strong> <?php echo htmlspecialchars(str_replace(',', ', ', $row["diasSemana"])); ?>
                  </div>

                  <div class="mt-auto pt-3 border-top">
                    <?php
                    if ($tipoUsuario === 'Visitante') {
                      echo "<a href='index.php?vista=login' class='btn btn-primary w-100 fw-bold py-2'>INICIAR SESIÓN PARA PEDIR</a>";
                    } elseif ($tipoUsuario === 'Cliente') {
                      $promoId = (int)$row["promo_id"];
                      $promoCategoria = $row["categoriaCliente"];

                      // Lógica de comparación de niveles
                      $indiceCliente = array_search($categoriaCliente, $categorias);
                      $indicePromo = array_search($promoCategoria, $categorias);
                      $yaPidio = ya_pidio_promocion($clienteId, $promoId);

                      if ($indicePromo > $indiceCliente) {
                        // Caso: No tiene nivel suficiente
                        echo "<button class='btn btn-secondary w-100 opacity-50 py-2' disabled style='cursor: not-allowed;'>NIVEL INSUFICIENTE</button>";
                      } elseif ($yaPidio) {
                        // Caso: Ya la pidió
                        echo "<button class='btn btn-success w-100 opacity-75 py-2' disabled style='cursor: not-allowed;'>YA SOLICITADA</button>";
                      } else {
                        // Caso: Puede pedirla
                        echo "<form method='POST' action='index.php' class='w-100'>";
                        echo "<input type='hidden' name='modulo' value='cliente'>";
                        echo "<input type='hidden' name='action' value='pedir_promocion'>";
                        echo "<input type='hidden' name='promo_id' value='" . $promoId . "'>";
                        echo "<button type='submit' class='btn btn-primary w-100 fw-bold py-2 shadow-sm'>PEDIR PROMOCIÓN</button>";
                        echo "</form>";
                      }
                    } else {
                      // Admin o Dueño no pueden "pedir" promos como clientes
                      echo "<button class='btn btn-outline-secondary w-100 py-2' disabled>VISTA PREVIA</button>";
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center py-5">
            <h4 class="text-muted">Este local no tiene promociones vigentes por el momento.</h4>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($total_pages > 1): ?>
        <nav class="mt-4">
          <ul class="pagination justify-content-center">
            <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
              <a class="page-link" href="index.php?vista=promociones&local_id=<?= $local_id ?>&page=<?= $current_page - 1 ?>">Anterior</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                <a class="page-link" href="index.php?vista=promociones&local_id=<?= $local_id ?>&page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
              <a class="page-link" href="index.php?vista=promociones&local_id=<?= $local_id ?>&page=<?= $current_page + 1 ?>">Siguiente</a>
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