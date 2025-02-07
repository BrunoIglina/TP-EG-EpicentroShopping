<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}
include '../private/novedades_functions.php';
$novedades = get_all_novedades();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Administración de Novedades</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="admin-section">
            <h1>Administración de Novedades</h1>
            <button onclick="location.href='agregar_novedad.php'">Agregar novedad</button>
            <?php
                if(!$novedades){?>
                    <b>NO HAY NOVEDADES CARGADAS</b>
                <?php }else{?>                
                <form id="novedadesForm" method="POST" action="../private/procesar_novedad.php">
                    <button type="submit" name="action" class="select-toggle" value="toggle">
                        <?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? 'Deseleccionar Todo' : 'Seleccionar Todo'; ?>
                    </button>
                    <table>
                        <thead>
                            <tr>
                                <th>Seleccionar</th>
                                <th>Código novedad</th>
                                <th>Novedad</th>
                                <th>Fecha desde novedad</th>
                                <th>Fecha hasta novedad</th>
                                <th>Categoria</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($novedades as $novedad){ ?>
                                <tr>
                                    <td><input type="checkbox" name="novedades[]" value="<?php echo $novedad['id']; ?>" <?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? 'checked' : ''; ?>></td>
                                    <td><?php echo $novedad['id']?></td>
                                    <td><?php echo $novedad['textoNovedad']?></td>
                                    <td><?php echo $novedad['fecha_desde']?></td>
                                    <td><?php echo $novedad['fecha_hasta']?></td>
                                    <td><?php echo $novedad['categoria']?></td> 
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <button type="submit" name="action" class="green" value="edit">Modificar novedad</button>
                    <button type="submit" name="action" class="red" value="delete">Eliminar novedad</button>
                    <input type="hidden" name="select_all" value="<?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? '0' : '1'; ?>">
                </form>
            <?php }?>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>