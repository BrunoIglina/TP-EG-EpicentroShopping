<?php
$password = 'dueno';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "Hash generado: " . $hashed_password . "\n";
?>