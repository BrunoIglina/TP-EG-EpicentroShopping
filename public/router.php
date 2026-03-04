<?php

session_start();

$entidad = $_POST['entidad'] ?? $_GET['entidad'] ?? '';

$rutas_permitidas = [
  'locales'     => __DIR__ . '/../private/logic/crud/locales.php',
  'novedades'   => __DIR__ . '/../private/logic/crud/novedades.php',
  'promociones' => __DIR__ . '/../private/logic/crud/promociones.php',
  'usuarios'    => __DIR__ . '/../private/logic/crud/usuarios.php'
];


if (array_key_exists($entidad, $rutas_permitidas)) {
  require_once $rutas_permitidas[$entidad];
} else {
  $_SESSION['error'] = "Petición no válida.";
  header("Location: index.php");
  exit();
}