<?php
// Incluye la clase de usuario
require_once("../entity/user.php");

// Define un array de datos con los valores del formulario de registro
$datos = array (
    'idUser' => null,
    'username' => $_POST['userReg'],
    'email'=> $_POST['emailReg'],
    'password'=> $_POST['passReg'],
    'name' => null,
    'date' => null,
    'region' => null,
    'foto' => null,
    'followers' => 0,
    'following' => 0,
    'posts'=> 0
);

// Parsea los datos en un objeto de usuario
$obj = User::parse($datos);

// Intenta agregar el usuario a la base de datos
if(User::addUser($obj)) {
    // Si se agrega correctamente, redirige a la página de opciones adicionales
    header("Location:../../additionalOptions.php");
}
?>
