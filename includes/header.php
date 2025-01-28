<?php
session_start();
$user_tipo = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : 'Visitante';
?>

<header>
    <div class="logo">
        <img src="../assets/logo.png" alt="Epicentro Shopping Logo">
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="promociones.php">Promociones</a></li>
            <li><a href="novedades.php">Novedades</a></li>
            <li><a href="locales.php">Locales</a></li>
            <li><a href="miperfil.php">Mi Perfil</a></li>
            <?php if ($user_tipo == 'Administrador'): ?>
                <li><a href="admin_locales.php">Gestionar Locales</a></li>
                <li><a href="admin_novedades.php">Gestionar Novedades</a></li>
                <li><a href="admin_promociones.php">Gestionar Promociones</a></li>
                <li><a href="admin_aprobar_dueños.php">Aprobar Dueños</a></li>
            <?php elseif ($user_tipo == 'Dueño'): ?>
                <li><a href="mis_promociones.php">Mis Promociones</a></li>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="login.php">Iniciar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>