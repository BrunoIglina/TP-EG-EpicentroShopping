<?php
session_start();

require_once __DIR__ . '/../includes/navigation_history.php';
require_once __DIR__ . '/../includes/security_headers.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $modulo = $_POST['modulo'] ?? '';

  if ($modulo === 'auth') {
    require_once __DIR__ . '/../private/logic/auth.controller.php';
    exit();
  }

  // -- Esqueleto para los demás módulos --
  // if ($modulo === 'admin') { require_once __DIR__ . '/../private/logic/admin_controller.php'; exit(); }
  // if ($modulo === 'dueno') { require_once __DIR__ . '/../private/logic/dueno_controller.php'; exit(); }
  // if ($modulo === 'cliente') { require_once __DIR__ . '/../private/logic/cliente_controller.php'; exit(); }
}

// ==========================================
// ROUTER DE VISTAS (Muestra las páginas GET)
// ==========================================
$vista = $_GET['vista'] ?? 'landing';

switch ($vista) {
  // --- PÁGINAS PÚBLICAS ---
  case 'landing':
    require_once __DIR__ . '/landing.php';
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

  // --- MÓDULO ADMIN (Bruno) ---
  // case 'admin_locales': require_once __DIR__ . '/admin/locales.php'; break;
  // case 'admin_novedades': require_once __DIR__ . '/admin/novedades.php'; break;

  // --- MÓDULO DUEÑO (Luciano) ---
  // case 'dueno_promociones': require_once __DIR__ . '/dueno/promociones.php'; break;
  // case 'dueno_reportes': require_once __DIR__ . '/dueno/reportes.php'; break;

  // --- MÓDULO CLIENTE (Santiago) ---
  // case 'cliente_perfil': require_once __DIR__ . '/cliente/perfil.php'; break;
  // case 'cliente_promociones': require_once __DIR__ . '/cliente/promociones.php'; break;

  default:
    // Si el usuario inventa una URL rara, lo mandamos al inicio
    //Crear una página de error 404 personalizada sería ideal, pero por ahora esto es suficiente
    require_once __DIR__ . '/landing.php';
    break;
}
