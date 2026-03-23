<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon" type="image/png" href="assets/logo2.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./public/css/footer.css">
  <link rel="stylesheet" href="./public/css/header.css">
  <link rel="stylesheet" href="./public/css/styles_fondo_and_titles.css">
  <link rel="stylesheet" href="./public/css/back_button.css">
  <link rel="stylesheet" href="./public/css/fix_header.css">
  <link rel="stylesheet" href="./public/css/buttons.css">

  <title>Epicentro Shopping - Administración de Locales</title>
</head>

<body>
  <div class="wrapper">
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    <main class="container-fluid">
      <div class="row align-items-center mb-5 mt-3">
        <div class="col-2 col-md-1 text-start">
          <?php include __DIR__ . '/../../includes/back_button.php'; ?>
        </div>

        <div class="col-8 col-md-10">
          <h2 class="text-center m-0 fw-bold text-uppercase" style="letter-spacing: 1px;">
            Administración de Locales
          </h2>
        </div>

        <div class="col-2 col-md-1"></div>
      </div>
      <section class="admin-section">

        <button class="btn btn-primary btn-sm mb-3 d-block mx-auto"
          onclick="location.href='index.php?vista=admin_local_agregar'">Agregar Local</button>

        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <?php if (!$locales) { ?>
          <div class="alert alert-warning">No hay locales cargados</div>
        <?php } else { ?>

          <div class="table-responsive-lg">
            <table class="table table-striped table-bordered">
              <thead class="thead-dark">
                <tr>
                  <th>Nombre</th>
                  <th>Ubicación</th>
                  <th>Rubro</th>
                  <th>Email del dueño</th>
                  <th>Imagen</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($locales as $local) { ?>
                  <tr>
                    <td><?php echo htmlspecialchars($local['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($local['ubicacion']); ?></td>
                    <td><?php echo htmlspecialchars($local['rubro']); ?></td>
                    <td>
                      <?php
                      $dueño = get_dueño($local['idUsuario']);
                      echo $dueño ? htmlspecialchars($dueño['email']) : 'Sin dueño asignado';
                      ?>
                    </td>
                    <td>
                      <?php if (!empty($local['imagen'])) { ?>
                        <img src="index.php?vista=imagen&local_id=<?php echo $local['id']; ?>" alt="Imagen del local"
                          class="img-fluid" style="max-width: 100px;">
                      <?php } else {
                        echo "No hay imagen";
                      } ?>
                    </td>
                    <td>
                      <button class="btn btn-info btn-sm" onclick="checkAndGeneratePDF(<?= $local['id'] ?>, '<?= addslashes($local['nombre']) ?>')">PDF</button>

                      <button type="button" class="btn btn-warning btn-sm mb-1"
                        onclick="window.location.href='index.php?vista=admin_local_editar&id=<?php echo $local['id']; ?>'">Modificar</button>

                      <button type="button" class="btn btn-danger btn-sm mb-1"
                        onclick="confirmAction('delete', <?php echo $local['id']; ?>)">Eliminar</button>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <div class="pagination-container">
            <ul class="pagination justify-content-center">
              <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="index.php?vista=admin_locales&page=<?php echo $page - 1; ?>">Anterior</a>
              </li>
              <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                  <a class="page-link" href="index.php?vista=admin_locales&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
              <?php } ?>
              <li class="page-item <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="index.php?vista=admin_locales&page=<?php echo $page + 1; ?>">Siguiente</a>
              </li>
            </ul>
          </div>

        <?php } ?>
      </section>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
  </div>

  <form id="deleteForm" action="index.php" method="POST" style="display: none;">
    <input type="hidden" name="modulo" value="admin">
    <input type="hidden" name="accion" value="eliminar_local">
    <input type="hidden" name="local_id" id="delete_local_id" value="">
  </form>

  <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmModalLabel">Confirmar Acción</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          ¿Está seguro de que desea eliminar este local?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" id="confirmActionBtn">Eliminar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="errorModalLabel"> No se puede generar el PDF</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p><strong id="errorLocalName"></strong> no tiene promociones registradas.</p>
          <p>Para generar un PDF, el local debe tener al menos una promoción activa.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function confirmAction(action, localId) {
      $('#confirmModal').modal('show');

      $('#confirmActionBtn').off('click').on('click', function() {
        if (action === 'delete') {
          // En lugar de redireccionar, llenamos el form oculto y lo enviamos
          document.getElementById('delete_local_id').value = localId;
          document.getElementById('deleteForm').submit();
        }
      });
    }

    function checkAndGeneratePDF(localId, localName) {
      // Llamamos al index.php con la vista de chequeo
      fetch(`index.php?vista=check_promos&local_id=${localId}`)
        .then(res => res.json())
        .then(data => {
          if (data.has_promociones) {
            window.location.href = `index.php?vista=generar_pdf&local_id=${localId}`;
          } else {
            document.getElementById('errorLocalName').textContent = localName;
            new bootstrap.Modal(document.getElementById('errorModal')).show();
          }
        })
        .catch(err => alert('Error de conexión con el router.'));
    }
  </script>
</body>

</html>