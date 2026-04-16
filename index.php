<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/private/lib/vendor/autoload.php';
session_start();

require_once __DIR__ . '/includes/navigation_history.php';
require_once __DIR__ . '/includes/security_headers.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $modulo = $_POST['modulo'] ?? '';

  if ($modulo === 'auth') {
    require_once __DIR__ . '/private/logic/auth.controller.php';
    exit();
  }
  if ($modulo === 'admin') {
    require_once __DIR__ . '/private/logic/admin.controller.php';
    exit();
  }
  if ($modulo === 'cliente') {
    require_once __DIR__ . '/private/logic/cliente.controller.php';
    exit();
  }
  if ($modulo === 'dueno') {
    require_once __DIR__ . '/private/logic/dueno.controller.php';
    exit();
  }
}


$vista = $_GET['vista'] ?? 'landing';

switch ($vista) {

  case 'landing':
    require_once __DIR__ . '/private/logic/locales.read.php';
    require_once __DIR__ . '/public/landing.php';
    break;
  case 'locales':
    require_once __DIR__ . '/private/logic/locales.read.php';
    require_once __DIR__ . '/public/locales.php';
    break;
  case 'novedades':
    require_once __DIR__ . '/private/logic/novedades.read.php';
    require_once __DIR__ . '/public/novedades.php';
    break;
  case 'mapadesitio':
    require_once __DIR__ . '/public/mapadesitio.php';
    break;
  case 'contacto':
    $env_path = __DIR__ . '/.env';
    if (file_exists($env_path)) {
      $env = parse_ini_file($env_path);
      $site_key = $env['RECAPTCHA_SITE_KEY'] ?? '';
    }
    require_once __DIR__ . '/public/contacto.php';
    break;

  // --- MÓDULO AUTH ---
  case 'login':
    require_once __DIR__ . '/public/auth/login.php';
    break;
  case 'registro':
    require_once __DIR__ . '/public/auth/registro.php';
    break;
  case 'recuperar':
    require_once __DIR__ . '/public/auth/recuperar-cuenta.php';
    break;
  case 'verificar':
    require_once __DIR__ . '/public/auth/codigo-verificacion.php';
    break;
  case 'cambiar_password':
    require_once __DIR__ . '/public/auth/cambiar-password.php';
    break;

  // --- MÓDULO ADMIN ---
  // Locales
  case 'admin_locales':
    require_once __DIR__ . '/private/logic/locales.read.php';
    require_once __DIR__ . '/private/logic/usuarios.read.php';
    require_once __DIR__ . '/public/admin/locales.php';
    break;
  case 'admin_local_agregar':
    require_once __DIR__ . '/private/logic/locales.read.php';
    require_once __DIR__ . '/public/admin/local_agregar.php';
    break;
  case 'admin_local_editar':
    require_once __DIR__ . '/private/logic/locales.read.php';
    require_once __DIR__ . '/public/admin/local_editar.php';
    break;

  // Novedades
  case 'admin_novedades':
    require_once __DIR__ . '/private/logic/novedades.read.php';
    require_once __DIR__ . '/public/admin/novedades.php';
    break;
  case 'admin_novedad_agregar':
    require_once __DIR__ . '/private/logic/novedades.read.php';
    require_once __DIR__ . '/public/admin/novedad_agregar.php';
    break;
  case 'admin_novedad_editar':
    require_once __DIR__ . '/private/logic/novedades.read.php';
    require_once __DIR__ . '/public/admin/novedad_editar.php';
    break;

  // Promociones y Usuarios
  case 'admin_promociones':
    require_once __DIR__ . '/private/logic/promociones.read.php';
    require_once __DIR__ . '/public/admin/promociones.php';
    break;
  case 'admin_aprobar_clientes':
    require_once __DIR__ . '/private/logic/usuarios.read.php';
    require_once __DIR__ . '/public/admin/aprobar_clientes.php';
    break;
  case 'admin_aprobar_duenos':
    require_once __DIR__ . '/private/logic/usuarios.read.php';
    require_once __DIR__ . '/public/admin/aprobar_duenos.php';
    break;

  // --- MÓDULO DUEÑO ---
  case 'dueno_promociones':
    require_once __DIR__ . '/private/logic/promociones.read.php';
    require_once __DIR__ . '/public/dueno/promociones.php';
    break;
  case 'dueno_promocion_agregar':
    require_once __DIR__ . '/private/logic/locales.read.php'; // Para elegir el local
    require_once __DIR__ . '/public/dueno/promocion_agregar.php';
    break;
  case 'dueno_solicitudes':
    require_once __DIR__ . '/private/logic/promociones.read.php';
    require_once __DIR__ . '/public/dueno/solicitudes.php';
    break;
  case 'dueno_reportes':
    require_once __DIR__ . '/private/logic/promociones.read.php';
    require_once __DIR__ . '/public/dueno/reportes.php';
    break;
  case 'dueno_mi_local':
    require_once __DIR__ . '/public/dueno/mi_local.php';
    break;

  // --- MÓDULO CLIENTE ---

  
  case 'promociones_general':

    require_once __DIR__ . '/private/logic/promociones.read.php'; 
    require_once __DIR__ . '/public/client/promociones_general.php';
    break;

  case 'cliente_perfil':
    require_once __DIR__ . '/private/logic/usuarios.read.php';
    require_once __DIR__ . '/public/client/miperfil.php';
    break;

  // ... (el resto de tus cases de cliente)
  case 'cliente_mod_perfil':
    require_once __DIR__ . '/private/logic/usuarios.read.php';
    require_once __DIR__ . '/public/client/mod_perfil.php';
    break;
  case 'promociones': // Lista de promos por local
    require_once __DIR__ . '/private/logic/promociones.read.php';
    require_once __DIR__ . '/public/client/promociones.php';
    break;
  case 'cliente_promociones': // Mis promociones (solicitadas)
    require_once __DIR__ . '/private/logic/promociones.read.php';
    require_once __DIR__ . '/public/client/mis_promociones.php';
    break;

  // --- AUXILIARES E IMÁGENES ---
  case 'imagen':
    require_once __DIR__ . '/private/logic/helpers/visualizar_imagen.php';
    exit();
  case 'check_promos':
    header('Content-Type: application/json');
    require_once __DIR__ . '/private/logic/reports/check_promociones.php';
    exit();
  case 'generar_pdf':
    require_once __DIR__ . '/private/logic/reports/generarInforme.php';
    exit();

  default:
    // Fallback a landing si la vista no existe
    require_once __DIR__ . '/private/logic/locales.read.php';
    require_once __DIR__ . '/public/landing.php';
    break;
}
