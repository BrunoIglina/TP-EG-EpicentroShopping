<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'Administrador') {
    header("Location: index.php");
    exit();
}

include '../private/functions_usuarios.php'; 

$dueños = get_all_dueños();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Epicentro Shopping - Agregar Local</title>
    <?php ?>
</head>
<body>
    <div class="wrapper">   
    <?php include '../includes/header.php'; ?>
        <main>
            <section class="admin-section">
                <h1>Agregar local</h1>
                <form action="../private/alta_local.php" method="post">

                    <label for="nombre_local">Nombre del local:</label>
                    <input type="text" id="nombre_local" name="nombre_local" required>

                    <label for="ubicacion_local">Ubicación del local:</label>
                    <input type="text" id="ubicacion_local" name="ubicacion_local" required>

                    <label for="rubro_local">Rubro del local:</label>
                    <input type="text" id="rubro_local" name="rubro_local" required>

                    <label for="email_dueño">Email dueño del local:</label>
                    <select id="email_dueño" name="id_dueño" required>
                    <?php
                        foreach ($dueños as $dueño) {
                            echo "<option value='{$dueño['id']}'>{$dueño['email']}</option>";
                        }
                    ?>
                    </select>
                    <button type="submit">Registrar</button>
                </form>
            </section>
        </main>
        <?php include '../includes/footer.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>