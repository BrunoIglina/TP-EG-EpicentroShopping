<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}
include '../private/functions_novedades.php';
$novedades = get_all_novedades();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_novedades.css">
    <title>Epicentro Shopping - Administración de Novedades</title>
</head>
<body>
    <div class="wrapper">
        <?php include '../includes/header.php'; ?>
        <main class="container">
            <section class="admin-section">
                <h1 class="text-center my-4">Administración de Novedades</h1>
                <button class="btn btn-primary mb-3" onclick="location.href='agregar_novedad.php'">Agregar novedad</button>
                <?php
                    if(!$novedades){?>
                        <b>NO HAY NOVEDADES CARGADAS</b>
                    <?php }else{?>                
                    <form id="novedadesForm" method="POST" action="../private/procesar_novedad.php">
                        <button type="submit" name="action" class="btn btn-secondary mb-3" value="toggle">
                            <?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? 'Deseleccionar Todo' : 'Seleccionar Todo'; ?>
                        </button>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Seleccionar</th>
                                    <th>Código novedad</th>
                                    <th>Titulo</th>
                                    <th>Descripcion</th>
                                    <th>Fecha desde</th>
                                    <th>Fecha hasta</th>
                                    <th>Categoria</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($novedades as $novedad){ ?>
                                    <tr>
                                        <td><input type="checkbox" name="novedades[]" value="<?php echo $novedad['id']; ?>" <?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? 'checked' : ''; ?>></td>
                                        <td><?php echo $novedad['id']?></td>
                                        <td><?php echo $novedad['tituloNovedad']?></td>
                                        <td><?php echo $novedad['textoNovedad']?></td>
                                        <td><?php echo $novedad['fecha_desde']?></td>
                                        <td><?php echo $novedad['fecha_hasta']?></td>
                                        <td><?php echo $novedad['categoria']?></td> 
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <button type="submit" name="action" class="btn btn-success" value="edit">Modificar novedad</button>
                        <button type="submit" name="action" class="btn btn-danger" value="delete">Eliminar novedad</button>
                        <input type="hidden" name="select_all" value="<?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? '0' : '1'; ?>">
                    </form>
                <?php }?>
            </section>
        </main>
        <?php include '../includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>