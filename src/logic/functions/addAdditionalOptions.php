<?php
// Incluir el archivo de funciones generales y la definición de la entidad de usuario
require_once('../gnl_functions.php');
include_once('../entity/user.php');

// Iniciar la sesión para acceder a la variable de sesión 'user'
session_start();

// Verificar si la sesión del usuario está establecida
if (isset($_SESSION["user"])) {
    // Obtener el objeto de usuario de la sesión
    $user = $_SESSION["user"];

    // Verificar si se ha enviado un archivo de imagen
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {
        // Obtener la ruta temporal del archivo de imagen
        $image = $_FILES["image"]["tmp_name"];
    }else {
        $image = null;
    }
    // Obtener los datos adicionales del formulario
    $name = $_POST["name"];
    $region = $_POST["region"];
    $date = $_POST["date"];

    // Llamar al método para agregar opciones adicionales al perfil de usuario
    User::addAdditionalOptions($user,$date, $name, $region, $image);
} else {
    // Si no hay una sesión de usuario válida, redirigir a la página de inicio de sesión
    header("Location:../../index.php");
    exit(); // Salir del script
}
?>
