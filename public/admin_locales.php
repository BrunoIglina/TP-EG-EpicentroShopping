<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include '../private/functions_locales.php'; 
include '../private/functions_usuarios.php'; 

$locales = get_all_locales();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Administración de Locales</title>
</head>
<body>
    <div class="wrapper">
        <?php include '../includes/header.php'; ?>
        <main>
            <section class="admin-section">
                <h1>Administración de Locales</h1>
                <button onclick="location.href='agregar_local.php'">Agregar Local</button>
                <?php
                if(!$locales){?>
                        <b>NO HAY LOCALES CARGADOS</b>

                    <?php }else{?>                
                    <form id="localesForm" method="POST" action="../private/procesar_local.php">


                        <button type="submit" name="action" class="select-toggle" value="toggle"><?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? 'Deseleccionar Todo' : 'Seleccionar Todo'; ?></button>

                    <table>
                        <thead>
                            <tr>
                                <th>Seleccionar</th>
                                <th>Código del local</th>
                                <th>Nombre</th>
                                <th>Ubicación</th>
                                <th>Rubro</th>
                                <th>Email del dueño</th>
                                <th>Acciones</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                
                                foreach ($locales as $local){ ?>
                                <tr>
                                    <td><input type="checkbox" name="locales[]" value="<?php echo $local['id']; ?>" <?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? 'checked' : ''; ?>></td>
                                    <td><?php echo $local['id']?></td>
                                    <td><?php echo $local['nombre']?></td>
                                    <td><?php echo $local['ubicacion']?></td>
                                    <td><?php echo $local['rubro']?></td>
                                    <?php 
                                        $dueño = get_dueño($local['idUsuario']);
                                    ?>
                                    <td><?php echo $dueño['email']?></td>
                                    <td><button type="button" onclick="window.location.href='../private/generarInforme.php?local_id=<?php echo $local['id']; ?>'">Generar PDF</button></td> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    
                                    foreach ($locales as $local){ ?>
                                    <tr>
                                        <td><input type="checkbox" name="locales[]" value="<?php echo $local['id']; ?>" <?php echo (isset($_GET['select_all']) && $_GET['select_all'] == '1') ? 'checked' : ''; ?>></td>
                                        <td><?php echo $local['id']?></td>
                                        <td><?php echo $local['nombre']?></td>
                                        <td><?php echo $local['ubicacion']?></td>
                                        <td><?php echo $local['rubro']?></td>
                                        <?php 
                                            $dueño = get_dueño($local['idUsuario']);
                                        ?>
                                        <td><?php echo $dueño['email']?></td>    
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <button type="submit" name="action" class="green" value="edit">Modificar local</button>
                        <button type="submit" name="action" class="red" value="delete">Eliminar local</button>
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