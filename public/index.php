<?php
// public/index.php
session_start();

// ==========================================
// 1. ROUTER DE ACCIONES (Procesa Formularios POST)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $modulo = $_POST['modulo'] ?? '';

  // Derivamos el tráfico al controlador correspondiente
  if ($modulo === 'auth') {
    require_once __DIR__ . '/../private/logic/auth_controller.php';
    exit();
  }
  // En el futuro acá irán: if ($modulo === 'admin') { ... }
}

// ==========================================
// 2. ROUTER DE VISTAS (Muestra las páginas GET)
// ==========================================
$vista = $_GET['vista'] ?? 'landing';

switch ($vista) {
  case 'landing':
    require_once __DIR__ . '/landing.php';
    break;
  case 'login':
    require_once __DIR__ . '/auth/login.php';
    break;
  case 'registro':
    require_once __DIR__ . '/auth/registro.php';
    break;
  // Agregaremos el resto de vistas luego...
  default:
    require_once __DIR__ . '/landing.php';
    break;
}
