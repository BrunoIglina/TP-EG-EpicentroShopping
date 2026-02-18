<?php
session_start();
require_once './config/database.php';
require_once './private/helpers/email.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo electrónico inválido.";
    } else {
        $conn = getDB();
        
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            $resultado = enviar_codigo_verificacion($email);
            
            if ($resultado === true) {
                header("Location: cod_verif.php?email=" . urlencode($email));
                exit();
            } else {
                $error = $resultado;
            }
        } else {
            $error = "Correo electrónico no registrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/header.css">
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once './config/database.php';
require_once './lib/vendor/autoload.php'; 
require_once './private/helpers/email.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = getDB();
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $user['email']; 
    
    if (enviar_codigo_verificacion($email)) {
        header('Location: cod_verif.php'); 
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/auth.css">
    <link rel="stylesheet" href="./css/styles_fondo_and_titles.css">
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Editar Perfil</title>
</head>
<body class="auth-page">
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
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
        <?php include './includes/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    <link rel="icon" type="image/png" href="./assets/logo2.png">
    <title>Epicentro Shopping - Recuperar Cuenta</title>
</head>
<body class="auth-page">
    <div class="wrapper">
        <?php include './includes/header.php'; ?>
        
        <main>
            <section class="auth-form">
                <h2>Recuperar Cuenta</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form action="recuperar_cuenta.php" method="post">
                    <label for="email">Correo Electrónico:</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="tu@email.com"
                        required 
                        autofocus
                    >

                    <button type="submit">Enviar Instrucciones</button>
                </form>
            </section>
        </main>
        
        <?php include './includes/footer.php'; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>