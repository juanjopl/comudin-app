<?php
// Incluye los archivos de funciones generales y de la entidad de usuario
require_once('../gnl_functions.php');
require_once('../entity/user.php');

// Inicia la sesión
session_start();

// Verifica si hay un usuario en sesión
if(isset($_SESSION['user'])){
    // Obtiene el nombre de usuario del comentarista de la sesión
    $commentator = $_SESSION['user']->username;
    // Obtiene el comentario enviado por el formulario
    $comment = $_POST["comment"];
    // Obtiene el ID de la publicación asociada al comentario enviado por el formulario
    $idPost = $_POST["idPost"];

    // Verifica si el comentario no está vacío
    if(!empty($comment)){
        try {
            // Establece conexión a la base de datos
            $con = get_connection();
            // Prepara y ejecuta la consulta SQL para insertar el comentario en la tabla de comentarios cortos
            $sql = "INSERT INTO shortcomments (comment, commentator, post)
                    VALUES (:comment, :commentator, :post)";
            $statement = $con->prepare($sql);
            $statement->bindParam(":comment", $comment, PDO::PARAM_STR);
            $statement->bindParam(":commentator", $commentator, PDO::PARAM_STR);
            $statement->bindParam(":post", $idPost, PDO::PARAM_INT);
            $res = $statement->execute();

            // Si la inserción del comentario es exitosa, actualiza el número de comentarios en la publicación
            if($res){
                $sql = "UPDATE shortposts SET comments = comments + 1 WHERE idshortpost = :idPost";
                $statement = $con->prepare($sql);
                $statement->bindParam(":idPost", $idPost, PDO::PARAM_INT);
                $statement->execute();
            }
        } catch (Exception $e) {
            // Maneja cualquier excepción ocurrida durante la inserción del comentario
            echo "Error al enviar el mensaje: ". $e->getMessage();
        } finally {
            // Cierra la conexión a la base de datos
            close_connection($con);
        }
    } else {
        // Si el comentario está vacío, muestra un mensaje de advertencia
        echo "El mensaje está vacío.";
    }
} else {
    // Si no hay usuario en sesión, redirige a la página de inicio de sesión
    header("location: ../index.php");
}
?>
