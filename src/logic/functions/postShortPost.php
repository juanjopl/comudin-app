<?php
// Incluye los archivos de funciones generales y de la entidad de usuario
require_once("../gnl_functions.php");
require_once("../entity/user.php");

// Inicia la sesión
session_start();

// Obtiene el usuario de la sesión
$user = $_SESSION['user'];

// Obtiene el mensaje del ShortPost enviado por el formulario
$messageShortPost = $_POST["messageShortPost"];

try {
    // Establece conexión a la base de datos
    $con = get_connection();

    // Prepara y ejecuta la consulta SQL para insertar el ShortPost en la base de datos
    $sql = "INSERT INTO shortposts (message, idUser, likes, comments) VALUES (:message, :idUser, 0, 0);";
    $statement = $con->prepare($sql);
    $statement->bindParam(":message", $messageShortPost , PDO::PARAM_STR);
    $statement->bindParam(":idUser", $user->username, PDO::PARAM_STR);
    $res = $statement->execute();

    // Verifica si la inserción del post fue exitosa
    if($res){
        // Prepara y ejecuta la consulta SQL para actualizar el número de posts del usuario
        $sql = "update users set posts = posts + 1 where username = :username";
        $statement = $con->prepare($sql);
        $statement->bindParam(":username", $user->username, PDO::PARAM_STR);
        $res = $statement->execute();
        // Redirige con un mensaje de éxito
        header("Location:../../profile.php?ok=POSTUP&shortposts");
    }
} catch(PDOException $e) {
    // En caso de error, muestra el mensaje de error
    echo "". $e->getMessage();
} finally {
    // Cierra la conexión a la base de datos
    close_connection($con);
}
?>
