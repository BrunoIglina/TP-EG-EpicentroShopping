<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<link rel="icon" type="image/png" href="assets/logo2.png">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/footer.css">
	<link rel="stylesheet" href="css/forms.css">
	<link rel="stylesheet" href="css/back_button.css">
	<link rel="stylesheet" href="css/fix_header.css">

	<title>Epicentro Shopping - Agregar Novedad</title>
</head>

<body>
	<?php include __DIR__ . '/../../includes/header.php'; ?>
	<?php include __DIR__ . '/../../includes/back_button.php'; ?>

	<div class="form-wrapper">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8 col-md-10">
					<div class="form-card">
						<h2>Agregar Novedad</h2>

						<?php if (isset($_SESSION['error'])): ?>
							<div class="alert alert-danger alert-dismissible fade show">
								<?php echo htmlspecialchars($_SESSION['error']);
								unset($_SESSION['error']); ?>
								<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
							</div>
						<?php endif; ?>

						<form method="POST" action="index.php" enctype="multipart/form-data">
							<input type="hidden" name="modulo" value="admin">
							<input type="hidden" name="accion" value="crear_novedad">

							<div class="mb-3">
								<label for="titulo_novedad" class="form-label">Título</label>
								<input type="text" class="form-control" id="titulo_novedad" name="titulo_novedad" required
									maxlength="100" placeholder="Ingrese el título de la novedad">
							</div>

							<div class="mb-3">
								<label for="texto_novedad" class="form-label">Descripción</label>
								<textarea class="form-control" id="texto_novedad" name="texto_novedad" rows="5" required maxlength="500"
									placeholder="Ingrese la descripción de la novedad"></textarea>
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
									<label for="fecha_desde" class="form-label">Fecha Desde</label>
									<input type="date" class="form-control" id="fecha_desde" name="fecha_desde" required>
								</div>

								<div class="col-md-6 mb-3">
									<label for="fecha_hasta" class="form-label">Fecha Hasta</label>
									<input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" required>
								</div>
							</div>

							<div class="mb-3">
								<label for="categoria" class="form-label">Categoría</label>
								<select class="form-select" id="categoria" name="categoria" required>
									<option value="" disabled selected>Seleccione una categoría</option>
									<?php foreach ($categorias as $categoria): ?>
										<option value="<?php echo htmlspecialchars($categoria); ?>">
											<?php echo htmlspecialchars($categoria); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="mb-4">
								<label for="imagen_novedad" class="form-label">Imagen (obligatoria)</label>
								<input type="file" class="form-control" id="imagen_novedad" name="imagen_novedad"
									accept="image/png, image/jpeg, image/jpg" required>
								<small class="text-muted">Formatos aceptados: PNG, JPG, JPEG</small>
							</div>

							<div class="d-grid gap-2">
								<button type="submit" class="btn btn-gradient">Crear Novedad</button>
								<button type="button" class="btn btn-secondary"
									onclick="window.location.href='index.php?vista=admin_novedades'">Cancelar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php include __DIR__ . '/../../includes/footer.php'; ?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>