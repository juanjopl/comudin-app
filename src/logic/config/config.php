<?php
/*
Este archivo recoge los errores posibles en la pagina de inicio de sesión o registro. 
Cuando sea necesario, en la llamada al array $error, se cogerá la clave correspondiente y se mostrará el contenido
*/
$error = [
    'NOT_EXIST' => 'Usuario o contraseña incorrectos',
    'AUTH_USERNAME_EXIST' => 'El usuario ya existe',
    'AUTH_EMAIL_EXIST' => 'El email ya existe'
]
?>