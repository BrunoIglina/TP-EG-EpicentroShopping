<?php
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "mod_rewrite: " . (in_array('mod_rewrite', $modules) ? 'Activo' : 'Inactivo') . "<br>";
    echo "mod_headers: " . (in_array('mod_headers', $modules) ? 'Activo' : 'Inactivo') . "<br>";
} else {
    echo "No se puede verificar módulos de Apache";
}

echo "<br><br>";
echo "HTTPS: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'Activo' : 'Inactivo') . "<br>";
echo "Servidor: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
?>