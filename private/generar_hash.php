
<?php
//este es un script para generar una contraseña hasheada de un administrador y almacenarla manualmente en la bd por ahora
$password = '123456'; 
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;
?>