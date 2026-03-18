<?php
session_start();

require_once __DIR__ . '/../includes/navigation_history.php';
require_once __DIR__ . '/../includes/security_headers.php';

// ROUTER DE ACCIONES (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $modulo = $_POST['modulo'] ?? '';

  if ($modulo === 'auth') {
    require_once __DIR__ . '/../private/logic/auth.controller.php';
    exit();
  }

  if ($modulo === 'admin') {
    require_once __DIR__ . '/../private/logic/admin.controller.php';
    exit();
  }

  if ($modulo === 'dueno') { require_once __DIR__ . '/../private/logic/dueno.controller.php'; exit(); }
  // if ($modulo === 'cliente') { require_once __DIR__ . '/../private/logic/cliente.controller.php'; exit(); }
}


// ROUTER DE VISTAS (GET)
$vista = $_GET['vista'] ?? 'landing';

switch ($vista) {
  // --- PÁGINAS PÚBLICAS ---
  case 'landing':
    require_once __DIR__ . '/landing.php';
    break;
  case 'locales':
    require_once __DIR__ . '/locales.php';
    break;
  case 'novedades':
    require_once __DIR__ . '/novedades.php';
    break;
  case 'mapadesitio':
    require_once __DIR__ . '/mapadesitio.php';
    break;

  // --- MÓDULO AUTH ---
  case 'login':
    require_once __DIR__ . '/auth/login.php';
    break;
  case 'registro':
    require_once __DIR__ . '/auth/registro.php';
    break;
  case 'recuperar':
    require_once __DIR__ . '/auth/recuperar-cuenta.php';
    break;
  case 'verificar':
    require_once __DIR__ . '/auth/codigo-verificacion.php';
    break;
  case 'cambiar_password':
    require_once __DIR__ . '/auth/cambiar-password.php';
    break;

  // --- MÓDULO ADMIN ---
  // Locales
  case 'admin_locales':
    require_once __DIR__ . '/admin/locales.php';
    break;
  case 'admin_local_agregar':
    require_once __DIR__ . '/admin/local_agregar.php';
    break;
  case 'admin_local_editar':
    require_once __DIR__ . '/admin/local_editar.php';
    break;

  // Novedades
  case 'admin_novedades':
    require_once __DIR__ . '/admin/novedades.php';
    break;
  case 'admin_novedad_agregar':
    require_once __DIR__ . '/admin/novedad_agregar.php';
    break;
  case 'admin_novedad_editar':
    require_once __DIR__ . '/admin/novedad_editar.php';
    break;

  // Promociones y Usuarios
  case 'admin_promociones':
    require_once __DIR__ . '/admin/promociones.php';
    break;
  case 'admin_aprobar_clientes':
    require_once __DIR__ . '/admin/aprobar_clientes.php';
    break;
  case 'admin_aprobar_duenos':
    require_once __DIR__ . '/admin/aprobar_duenos.php';
    break;

  // --- MÓDULO DUEÑO (Luciano) ---
  case 'dueno_promociones': 
    require_once __DIR__ . '/dueno/promociones.php'; 
    break;
  case 'dueno_promocion_agregar': 
    require_once __DIR__ . '/dueno/promocion_agregar.php'; 
    break;
  case 'dueno_solicitudes': 
    require_once __DIR__ . '/dueno/solicitudes.php'; 
    break;  
  case 'dueno_reportes': 
    require_once __DIR__ . '/dueno/reportes.php'; 
    break;
  case 'dueno_mi_local': 
    require_once __DIR__ . '/dueno/mi_local.php'; 
    break;
  // --- MÓDULO CLIENTE (Santiago) ---
  // case 'cliente_perfil': require_once __DIR__ . '/cliente/perfil.php'; break;
  // case 'cliente_promociones': require_once __DIR__ . '/cliente/promociones.php'; break;

  // --- RUTA PARA MOSTRAR IMÁGENES ---
  case 'imagen':
    require_once __DIR__ . '/../private/logic/helpers/visualizar_imagen.php';
    exit();

  default:
    // Si el usuario inventa una URL rara, lo mandamos al inicio
    require_once __DIR__ . '/landing.php';
    break;
}
