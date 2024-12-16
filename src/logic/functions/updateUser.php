<?php
// Requiere el archivo de funciones generales
require_once('../gnl_functions.php');

// Incluye la entidad de User
include_once('../entity/user.php');

// Inicia la sesión
session_start();

// Si no hay un usuario en la sesión
if (!isset($_SESSION["user"])) {
    // Redirige a la página de inicio
    header("Location: ../../index.php");
    // Destruye la sesión
    session_destroy();
} else {
    // Obtén el usuario de la sesión
    $user = $_SESSION["user"];
    
    // Crea un arreglo con los datos proporcionados por el formulario
    $data = array(
        "name" => $_POST["nameUpd"],
        "username" => $_POST["userUpd"],
        "region" => $_POST["regionUpd"],
        "pass" => $_POST["passUpd"]
    );
    
    // Si no se proporcionó una nueva contraseña, usa la actual del usuario
    if (empty($_POST["passUpd"])) {
        $data["pass"] = $user->password;
    }
    
    // Si no se proporcionó un nuevo nombre, usa el actual del usuario
    if (empty($_POST["nameUpd"])) {
        $data["name"] = $user->name;
    }
    
    // Si no se proporcionó un nuevo nombre de usuario, usa el actual del usuario
    if (empty($_POST["userUpd"])) {
        $data["username"] = $user->username;
    }
    
    // Si no se proporcionó una nueva región, usa la actual del usuario
    if ($_POST["regionUpd"] === null) {
        $data["region"] = $user->region;
    }
    
    // Actualiza el usuario con los datos proporcionados
    User::updateUser($data, $user);
}

?>