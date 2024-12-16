<?php
// Incluir el archivo de funciones generales y la definición de la entidad de usuario
require_once("../gnl_functions.php");
require_once("../entity/user.php");

// Iniciar sesión para acceder a la información del usuario
session_start();

// Obtener el ID del post dependiendo de si es un post corto o un post de Instagram
if (isset($_POST["idShortPost"])) {
    $idPost = $_POST["idShortPost"];
} else {
    $idPost = $_POST["idInstaPost"];
}

try {
    // Establecer conexión a la base de datos
    $con = get_connection();

    // Construir y ejecutar la consulta SQL para eliminar el post y sus relaciones
    if (isset($_POST["idShortPost"])) {
        $sql = "
            DELETE FROM shortlikes WHERE post = :idPost;
            DELETE FROM shortcomments WHERE post = :idPost;
            DELETE FROM shortposts WHERE idshortpost = :idPost;
            UPDATE users SET posts = posts - 1 WHERE username = :username;
        ";
    } else {
        $sql = "
            DELETE FROM instalikes WHERE post = :idPost;
            DELETE FROM instacomments WHERE post = :idPost;
            DELETE FROM instaposts WHERE idinstapost = :idPost;
            UPDATE users SET posts = posts - 1 WHERE username = :username;
        ";
    }
    $statement = $con->prepare($sql);
    $statement->bindParam(":idPost", $idPost, PDO::PARAM_INT);
    $statement->bindParam(":username", $_SESSION["user"]->username, PDO::PARAM_STR);
    $res = $statement->execute();

    // Verificar si la operación de eliminación fue exitosa y redirigir con un mensaje de éxito
    if ($res) {
        if (isset($_GET["idShortPost"])) {
            header("Location:../../profile.php?ok=POSTDELETED&shortposts");
        } else {
            header("Location:../../profile.php?ok=POSTDELETED&instaposts");
        }
    }
} catch (PDOException $e) {
    // Si hay un error en la conexión o ejecución de la consulta, mostrar el mensaje de error
    echo "" . $e->getMessage();
} finally {
    // Cerrar la conexión a la base de datos
    close_connection($con);
}
?>
