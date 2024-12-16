<?php
// Incluye el archivo de funciones generales y la definición de la entidad de User
require_once("../gnl_functions.php");
include_once("../entity/user.php");

// Inicia sesión para acceder a la información del usuario
session_start();

try {
    // Establece conexión a la base de datos
    $con = get_connection();

    // Obtén el nombre de usuario que se está siguiendo y el nombre de usuario que está siguiendo
    $following = $_POST["following"];
    $follower = $_SESSION["user"]->username;

    // Determina si se está siguiendo o dejando de seguir a un usuario
    if ($_GET["follow"] == "false") {
        // Si se está siguiendo, prepara la consulta SQL para insertar una nueva relación de seguimiento
        $sql = "insert into tracking (follower, following) VALUES (:follower, :following);";
        añadir($follower, $following); // Llama a la función para aumentar el contador de seguidores y seguidos
    } else {
        // Si se está dejando de seguir, prepara la consulta SQL para eliminar la relación de seguimiento existente
        $sql = "delete from tracking where follower = :follower and following = :following;";
        quitar($follower, $following); // Llama a la función para disminuir el contador de seguidores y seguidos
    }

    // Prepara y ejecuta la consulta SQL
    $statement = $con->prepare($sql);
    $statement->bindParam(":follower", $follower, PDO::PARAM_STR);
    $statement->bindParam(":following", $following, PDO::PARAM_STR);
    $res = $statement->execute();

    // Redirige a la página de perfil del usuario seguido después de realizar la acción
    if ($res) {
        header("Location: ../../profile.php?userSearched=$following");
    }
} catch (Exception $e) {
    // Si hay un error en la conexión o ejecución de la consulta, muestra el mensaje de error
    echo "Error:" . $e->getMessage() . "";
} finally {
    // Cierra la conexión a la base de datos
    close_connection($con);
}

// Función para aumentar el contador de seguidores y seguidos
function añadir($follower, $following) {
    try {
        // Establece conexión a la base de datos
        $con = get_connection();

        // Prepara y ejecuta la consulta SQL para aumentar los contadores
        $sql = "update users set following = following + 1 where username = :username1;
                update users set followers = followers + 1 where username = :username2;";
        $statement = $con->prepare($sql);
        $statement->bindParam(":username1", $follower, PDO::PARAM_STR);
        $statement->bindParam(":username2", $following, PDO::PARAM_STR);
        $statement->execute();
    } catch (Exception $e) {
        // Si hay un error, muestra el mensaje de error
        echo "" . $e->getMessage() . "";
    } finally {
        // Cierra la conexión a la base de datos
        close_connection($con);
    }
}

// Función para disminuir el contador de seguidores y seguidos
function quitar($follower, $following) {
    try {
        // Establece conexión a la base de datos
        $con = get_connection();

        // Prepara y ejecuta la consulta SQL para disminuir los contadores
        $sql = "update users set following = following - 1 where username = :username1;
                update users set followers = followers - 1 where username = :username2;";
        $statement = $con->prepare($sql);
        $statement->bindParam(":username1", $follower, PDO::PARAM_STR);
        $statement->bindParam(":username2", $following, PDO::PARAM_STR);
        $statement->execute();
    } catch (Exception $e) {
        // Si hay un error, muestra el mensaje de error
        echo "" . $e->getMessage() . "";
    } finally {
        // Cierra la conexión a la base de datos
        close_connection($con);
    }
}
?>
