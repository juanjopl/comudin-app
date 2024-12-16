<?php
// Incluir el archivo de funciones generales y la definición de la entidad de usuario
require_once('../gnl_functions.php');
include_once('../entity/user.php');

// Iniciar sesión para acceder al usuario actual
session_start();

// Obtener el objeto de usuario de la sesión
$user = $_SESSION['user'];

// Obtener la ruta temporal del archivo de imagen cargado
$image = $_FILES["image"]["tmp_name"];

// Intentar cambiar la imagen de perfil del usuario
if (User::changePic($user, $image)) {
    // Si se cambió exitosamente, redirigir al perfil del usuario
    header("Location:../../profile.php");
} else {
    // Si hubo un error de formato al cargar la imagen, redirigir al perfil con un mensaje de error
    header("Location:../../profile.php?err=FORMAT");
}
?>
