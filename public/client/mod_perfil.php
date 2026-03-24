<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?vista=login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $user['email'];

    if (enviar_codigo_verificacion($email)) {
        header('Location: index.php?vista=verificar');
        exit();
    } else {
        $error = "No se pudo enviar el código. Intente nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="icon" type="image/png" href="public/assets/logo2.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./public/css/header.css">
    <link rel="stylesheet" href="./public/css/footer.css">
    <link rel="stylesheet" href="./public/css/auth.css">
    <link rel="stylesheet" href="./public/css/styles_fondo_and_titles.css">
    <link rel="stylesheet" href="./public/css/back_button.css">
    <link rel="stylesheet" href="./public/css/fix_header.css">


    <title>Editar Perfil</title>
</head>

<body class="auth-page">
    <div class="wrapper">
        <?php include __DIR__ . '/../../includes/header.php'; ?>

        <main>
            <div class="auth-container">
                <section class="form-container">
                    <h1>Editar Perfil</h1>
                    <?php if (isset($error)) echo "<p class='text-danger'>" . htmlspecialchars($error) . "</p>"; ?>
                    <form method="POST">
                        <button type="submit">Enviar Código de Verificación</button>
                    </form>
                </section>
            </div>
        </main>
        <?php include __DIR__ . '/../../includes/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>