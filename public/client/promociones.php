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
  <link rel="stylesheet" href="./css/footer.css">
  <link rel="stylesheet" href="./css/header.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <link rel="icon" type="image/png" href="./assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
  <link rel="stylesheet" href="./css/tarjetas.css">
  <link rel="stylesheet" href="./css/back_button.css">
  <link rel="stylesheet" href="./css/fix_header.css">
  <link rel="stylesheet" href="./css/wrapper.css">

  <title>Epicentro Shopping - Promociones</title>
</head>

<body>
  <div class="wrapper">
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <main class="container-fluid" style="overflow-x: hidden;">
      <?php include __DIR__ . '/../../includes/back_button.php'; ?>

      <?php
            if (isset($_SESSION['mensaje_error'])) {
                echo "<div class='alert alert-danger text-center mt-2'>" . htmlspecialchars($_SESSION['mensaje_error']) . "</div>";
                unset($_SESSION['mensaje_error']); 
            }
            if (isset($_SESSION['mensaje_exito'])) {
                echo "<div class='alert alert-success text-center mt-2'>" . htmlspecialchars($_SESSION['mensaje_exito']) . "</div>";
                unset($_SESSION['mensaje_exito']);
            }
            ?>

      <h2 class="mb-4 text-center"><?php echo htmlspecialchars($local["nombre"]); ?></h2>

      <div class="row g-4 mb-4">
        <?php
                if (!empty($promos)) {
                    foreach ($promos as $row) { ?>

        <div class="col-12 col-md-4 d-flex">
          <div class="card w-100 shadow-sm h-100 text-center">
            <div class="card-body d-flex flex-column">
              <p class="fs-5"><strong><?php echo htmlspecialchars($row["textoPromo"]); ?></strong></p>
              <p><strong>Categoría Requerida: <?php echo htmlspecialchars($row["categoriaCliente"]); ?></strong></p>
              <p class="mb-1 text-muted">Válido del <?php echo htmlspecialchars($row["fecha_inicio"]); ?> al
                <?php echo htmlspecialchars($row["fecha_fin"]); ?></p>
              <p class="mb-3 text-muted">Días:
                <?php echo htmlspecialchars(str_replace(',', ', ', $row["diasSemana"])); ?></p>

              <div class="mt-auto">
                <?php
                                        if ($tipoUsuario === 'Visitante') {
                                            echo "<a href='index.php?vista=login' class='btn btn-success w-100'>Pedir Promoción</a>";
                                        } elseif ($tipoUsuario === 'Cliente') {
                                            $promoId = (int)$row["promo_id"];
                                            $promoCategoria = $row["categoriaCliente"];
                                            $clienteCategoria = $_SESSION['user_categoria'];

                                            $indiceCliente = array_search($clienteCategoria, $categorias);
                                            $indicePromo = array_search($promoCategoria, $categorias);

                                            $yaPidio = ya_pidio_promocion($clienteId, $promoId);

                                            if ($indicePromo > $indiceCliente || $yaPidio) {
                                                echo "<button class='btn btn-secondary w-100' style='background-color: gray; cursor: not-allowed;' disabled>No Disponible</button>";
                                            } else {
                                                echo "<form method='POST' action='index.php' class='d-inline w-100'>";
                                                echo "<input type='hidden' name='modulo' value='cliente'>";
                                                echo "<input type='hidden' name='action' value='pedir_promocion'>";
                                                echo "<input type='hidden' name='promo_id' value='" . $promoId . "'>";
                                                echo "<button type='submit' class='btn btn-success w-100'>Pedir Promoción</button>";
                                                echo "</form>";
                                            }
                                        } else {
                                            echo "<button class='btn btn-secondary w-100' style='background-color: gray; cursor: not-allowed;' disabled>No Disponible</button>";
                                        }?>
              </div>
            </div>
          </div>
        </div>
        <?php } 
                    ?>
        <?php } else { ?>
        <div class="col-12 text-center">
          <p>No hay promociones de este local.</p>
        </div>
        <?php } ?>
      </div> <?php if ($total_pages > 1) { ?>
      <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
          <li class="page-item <?php if ($page <= 1) { echo 'disabled'; } ?>">
            <a class="page-link" href="index.php?vista=promociones&local_id=<?php echo $local_id; ?>&page=<?php echo $page - 1; ?>">Anterior</a>
          </li>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?php if ($page == $i) { echo 'active'; } ?>">
            <a class="page-link" href="index.php?vista=promociones&local_id=<?php echo $local_id; ?>&page=<?= $i; ?>"><?= $i; ?></a>
          </li>
          <?php endfor; ?>
          <li class="page-item <?php if ($page >= $total_pages) { echo 'disabled'; } ?>">
            <a class="page-link" href="index.php?vista=promociones&local_id=<?php echo $local_id; ?>&page=<?php echo $page + 1; ?>">Siguiente</a>
          </li>
        </ul>
      </nav>
      <?php } ?>

    </main>
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>