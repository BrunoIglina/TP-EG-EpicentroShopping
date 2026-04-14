<?php
$user_tipo_footer = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : 'Visitante';
?>
<link rel="stylesheet" href="public/css/floating_contact_button.css">

<footer class="mega-footer pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row">
            
            <div class="col-6 col-md-3 mb-4">
                <h5 class="footer-heading">Explorar</h5>
                
                <h6 class="footer-subheading">Principal</h6>
                <a href="index.php?vista=landing" class="footer-link">Inicio</a>
                <a href="index.php?vista=locales" class="footer-link">Locales Comerciales</a>
                <a href="index.php?vista=novedades" class="footer-link">Novedades</a>
                
                <h6 class="footer-subheading">Soporte</h6>
                <a href="index.php?vista=contacto" class="footer-link">Contacto</a>
                <a href="#" class="footer-link">Preguntas Frecuentes</a>
            </div>

            <div class="col-6 col-md-3 mb-4">
                <h5 class="footer-heading">Mi Cuenta</h5>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <h6 class="footer-subheading">Perfil</h6>
                    <a href="index.php?vista=cliente_perfil" class="footer-link">Ver Mi Perfil</a>
                    <a href="index.php?vista=cliente_mod_perfil" class="footer-link">Editar Datos</a>
                    
                    <h6 class="footer-subheading">Seguridad</h6>
                    <a href="index.php?vista=cambiar_password" class="footer-link">Cambiar Contraseña</a>
                    <a href="public/logout.php" class="footer-link text-danger">Cerrar Sesión</a>
                <?php else: ?>
                    <h6 class="footer-subheading">Accesos</h6>
                    <a href="index.php?vista=login" class="footer-link">Iniciar Sesión</a>
                    <a href="index.php?vista=registro" class="footer-link">Registrarse</a>
                    <a href="index.php?vista=recuperar" class="footer-link">Recuperar Contraseña</a>
                <?php endif; ?>
            </div>

            <?php if ($user_tipo_footer == 'Administrador'): ?>
                <div class="col-6 col-md-3 mb-4">
                    <h5 class="footer-heading">Administración</h5>
                    
                    <h6 class="footer-subheading">Gestión Comercial</h6>
                    <a href="index.php?vista=admin_locales" class="footer-link">Gestión de Locales</a>
                    <a href="index.php?vista=admin_novedades" class="footer-link">Gestión de Novedades</a>
                    <a href="index.php?vista=admin_promociones" class="footer-link">Gestión de Promociones</a>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <h5 class="footer-heading">Usuarios</h5>
                    <h6 class="footer-subheading">Auditoría</h6>
                    <a href="index.php?vista=admin_aprobar_clientes" class="footer-link">Aprobar Clientes</a>
                    <a href="index.php?vista=admin_aprobar_duenos" class="footer-link">Aprobar Dueños</a>
                </div>

            <?php elseif ($user_tipo_footer == 'Dueno'): ?>
                <div class="col-6 col-md-3 mb-4">
                    <h5 class="footer-heading">Mi Local</h5>
                    
                    <h6 class="footer-subheading">Marketing</h6>
                    <a href="index.php?vista=dueno_promociones" class="footer-link">Mis Promociones</a>
                    <a href="index.php?vista=dueno_promocion_agregar" class="footer-link">Crear Nueva Promoción</a>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <h5 class="footer-heading">Gestión</h5>
                    
                    <h6 class="footer-subheading">Métricas</h6>
                    <a href="index.php?vista=dueno_solicitudes" class="footer-link">Administrar Solicitudes</a>
                    <a href="index.php?vista=dueno_reportes" class="footer-link">Ver Reportes (PDF)</a>
                </div>

            <?php elseif ($user_tipo_footer == 'Cliente'): ?>
                <div class="col-6 col-md-3 mb-4">
                    <h5 class="footer-heading">Actividad</h5>
                    
                    <h6 class="footer-subheading">Promociones</h6>
                    <a href="index.php?vista=cliente_promociones" class="footer-link">Mis Solicitudes</a>
                    <a href="index.php?vista=locales" class="footer-link">Buscar Nuevas Promos</a>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    </div>
            <?php endif; ?>

        </div> <hr class="footer-divider">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="text-white-50 small m-0">
                    &copy; <?php echo date('Y'); ?> Epicentro Shopping. Todos los derechos reservados.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="text-white-50 small m-0">
                    Soporte Técnico: 
                    <a href="mailto:admin@epicentroshopping.com" class="text-white text-decoration-none">
                        admin@epicentroshopping.com
                    </a>
                </p>
            </div>
        </div>
    </div>
</footer>

<a href="index.php?vista=contacto" class="floating-contact-btn">
    <span class="floating-contact-btn-icon">✉️</span>
    <span>Contacto</span>
</a>