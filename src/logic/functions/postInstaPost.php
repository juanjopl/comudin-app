<?php
// Incluye los archivos de funciones generales y de la entidad de usuario
require_once("../gnl_functions.php");
require_once("../entity/user.php");

// Inicia la sesión
session_start();

// Obtiene el usuario de la sesión
$user = $_SESSION['user'];

// Obtiene la descripción y la ubicación del InstaPost enviadas por el formulario
$descripPost = $_POST["descripPost"];
$ubiPost = $_POST["ubiPost"];

// Verifica si se ha enviado un archivo de imagen y si no está vacío
if(isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {
    // Obtiene la ruta temporal del archivo de imagen
    $image = $_FILES["image"]["tmp_name"];
    // Obtiene información sobre la imagen
    $info = @getimagesize($image);

    // Verifica si la información de la imagen es válida
    if ($info !== false) {
        // Obtiene el tipo MIME de la imagen
        $tipo = $info['mime'];
        // Verifica si el tamaño del archivo de imagen es menor o igual a 1MB (aproximadamente)
        if ($_FILES["image"]["size"] > 1000000) {
            // Si el tamaño de la imagen excede el límite, redirige con un mensaje de error
            header("Location:../../profile.php?err=SIZE");
        } else {
            // Lee el contenido del archivo de imagen
            $imageData = file_get_contents($image);

            // Establece conexión a la base de datos
            $con = get_connection();

            // Prepara y ejecuta la consulta SQL para insertar el InstaPost en la base de datos
            $sql = "insert into instaposts (photo, description, ubication, idUser, likes)
            values (:image, :descripPost, :ubiPost, :idUser, 0);";
            $statement = $con->prepare($sql);
            $statement->bindParam(":idUser", $user->username, PDO::PARAM_STR);
            $statement->bindParam(":image", $imageData, PDO::PARAM_LOB);
            $statement->bindParam(":descripPost", $descripPost, PDO::PARAM_STR);
            $statement->bindParam(":ubiPost", $ubiPost, PDO::PARAM_STR);

            $result = $statement->execute();

            // Verifica si la inserción de la publicación fue exitosa
            if ($result) {
                // Prepara y ejecuta la consulta SQL para actualizar el número de publicaciones del usuario
                $sql = "update users set posts = posts + 1 where username = :username";
                $statement = $con->prepare($sql);
                $statement->bindParam(":username", $user->username, PDO::PARAM_STR);
                $res = $statement->execute();
                // Redirige con un mensaje de éxito
                header("Location:../../profile.php?ok=POSTUP&instaposts");
            }
        }
    } else {
        // Si la información de la imagen es inválida, devuelve falso
        return false;
    }
} else {
    // Si no se ha enviado un archivo de imagen, redirige con un mensaje de error
    header("Location:../../profile.php?err=NOFILE");
}
?>
