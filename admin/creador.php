<?php
$password = '1234'; // Cambia esto por la contraseña que quieras usar
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;
?>