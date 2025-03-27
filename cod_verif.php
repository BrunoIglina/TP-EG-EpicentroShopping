<?php
session_start();
    // include($_SERVER['DOCUMENT_ROOT'] . '/env/shopping_db.php');
    include('./env/shopping_db.php');

$email = isset($_GET['email']) ? $_GET['email'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_code = $_POST['verification_code'];

    if (isset($_SESSION['verification_code']) && $entered_code == $_SESSION['verification_code']) {
        unset($_SESSION['verification_code']); 
        $_SESSION['code_verified'] = true;

        header("Location: cambiar_contraseña.php?email=" . urlencode($email));
        exit();
    } else {
        $error = "Código de verificación incorrecto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/cod_verif.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Verificar Código</title>
</head>
<body>
    <div class="wrapper">
        <?php include './includes/header.php'; ?>  
            
        <div class="d-flex flex-column align-items-center justify-content-center">
            <h2 class="text-center my-4">Verificar Código</h2>

            <form method="POST" class="p-4 border rounded shadow-sm bg-white w-100" style="max-width: 400px;">
                <div class="form-group">
                    <label for="verification_code">Código de Verificación:</label>
                    <input type="text" id="verification_code" name="verification_code" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Verificar</button>
            </form>

            <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
        </div>

        <?php include './includes/footer.php'; ?> 
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
