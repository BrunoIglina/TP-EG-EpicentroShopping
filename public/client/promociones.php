<?php
if (!isset($_GET['local_id'])) {
  header("Location: index.php?vista=locales");
  exit;
}

$categoriaCliente = $_SESSION['user_categoria'] ?? null;
$tipoUsuario = $_SESSION['user_tipo'] ?? 'Visitante';
$clienteId = $_SESSION['user_id'] ?? 0;
$categorias = ['Inicial', 'Medium', 'Premium']; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Promociones de <?php echo htmlspecialchars($local["nombre"]); ?> | Epicentro Shopping</title>
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
      
      <div class="row align-items-center mb-5">
        <div class="col-auto">

        </div>
        <div class="col">
          <h2 class="text-center m-0 fw-bold text-uppercase h1"><?php echo htmlspecialchars($local["nombre"]); ?></h2>
          <p class="text-center text-muted m-0 mt-2 fs-5">Catálogo de Promociones Vigentes</p>
        </div>
        <div class="col-auto" style="width: 100px;"></div> </div>
                    <?php include __DIR__ . '/../../includes/back_button.php'; ?>
      <?php if (isset($_SESSION['mensaje_error'])): ?>
        <div class='alert alert-danger alert-dismissible fade show text-center shadow-sm' role='alert'>
          <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($_SESSION['mensaje_error']); ?>
          <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
        </div>
        <?php unset($_SESSION['mensaje_error']); ?>
      <?php endif; ?>

      <div class="row g-4 justify-content-center">
        <?php if (!empty($promos)): ?>
          <?php foreach ($promos as $row): ?>
            <?php 
              $promoCategoria = $row["categoriaCliente"];
              $indiceCliente = array_search($categoriaCliente, $categorias);
              $indicePromo = array_search($promoCategoria, $categorias);
              
              $isLocked = ($tipoUsuario === 'Cliente' && $indicePromo > $indiceCliente);
              $cardClass = $isLocked ? 'border-danger-subtle bg-white opacity-75' : 'border-primary-subtle bg-white border-top border-4';
            ?>
            <div class="col-12 col-md-6 col-lg-4 d-flex">
              <div class="card w-100 shadow-sm rounded-4 overflow-hidden h-100 <?= $cardClass ?>">
                
                <div class="card-header <?= $isLocked ? 'bg-danger text-white' : 'bg-primary text-white' ?> text-center py-3 border-0">
                  <span class="fw-bold text-uppercase" style="letter-spacing: 1px;">
                    <?php if($isLocked): ?>
                      <i class="bi bi-lock-fill"></i> Solo Nivel <?= htmlspecialchars($promoCategoria) ?>+
                    <?php else: ?>
                      <i class="bi bi-unlock-fill"></i> Nivel <?= htmlspecialchars($promoCategoria) ?>
                    <?php endif; ?>
                  </span>
                </div>

                <div class="card-body d-flex flex-column text-center p-4">
                  <h4 class="fw-bold mb-4" style="color: #2b3440;"><?php echo htmlspecialchars($row["textoPromo"]); ?></h4>
                  
                  <ul class="list-unstyled mb-4 text-start small text-muted bg-light p-3 rounded-3">
                    <li class="mb-2"><i class="bi bi-calendar-event text-primary"></i> <strong>Válido:</strong> <?= date('d/m/Y', strtotime($row["fecha_inicio"])) ?> al <?= date('d/m/Y', strtotime($row["fecha_fin"])) ?></li>
                    <li><i class="bi bi-clock text-primary"></i> <strong>Días:</strong> <?= htmlspecialchars(str_replace(',', ', ', $row["diasSemana"])) ?></li>
                  </ul>

                  <div class="mt-auto">
                    <?php
                    if ($tipoUsuario === 'Visitante') {
                      echo "<a href='index.php?vista=login' class='btn btn-outline-primary w-100 fw-bold py-2'>Iniciar Sesión para Pedir</a>";
                    } elseif ($tipoUsuario === 'Cliente') {
                      $yaPidio = ya_pidio_promocion($clienteId, $row["promo_id"]); 

                      if ($isLocked) {
                        echo "<button class='btn btn-danger w-100 py-2 fw-bold' disabled><i class='bi bi-x-circle'></i> Nivel Insuficiente</button>";
                      } elseif ($yaPidio) {
                        echo "<button class='btn btn-success w-100 py-2 fw-bold' disabled><i class='bi bi-check-circle-fill'></i> Ya Solicitada</button>";
                      } else {
                        echo "<form method='POST' action='index.php'>";
                        echo "<input type='hidden' name='modulo' value='cliente'>";
                        echo "<input type='hidden' name='action' value='pedir_promocion'>";
                        echo "<input type='hidden' name='promo_id' value='" . (int)$row["promo_id"] . "'>";
                        echo "<button type='submit' class='btn btn-primary w-100 fw-bold py-2 shadow-sm'><i class='bi bi-bag-plus-fill'></i> Solicitar Promoción</button>";
                        echo "</form>";
                      }
                    } else {
                      echo "<button class='btn btn-secondary w-100 py-2' disabled>Vista Previa Administrador</button>";
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            <h4 class="text-muted mt-3">Este local no tiene promociones vigentes por el momento.</h4>
          </div>
        <?php endif; ?>
      </div>
    </main>
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>