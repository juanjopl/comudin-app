<?php
// Incluye la clase de usuario y las funciones generales
require_once("../entity/user.php");
require_once("../gnl_functions.php");

// Inicia la sesión
session_start();

// Obtiene el ID del post y el estado (corazón) de la solicitud
$idPost = $_GET["idPost"];
$status = $_GET["status"];

// Obtiene el nombre de usuario de la sesión actual
$username = $_SESSION["user"]->username;

try {
    // Establece la conexión con la base de datos
    $con = get_connection();

    // Actualiza el contador de "me gusta" del post corto según el estado de la solicitud
    if($status=="heart") {
        $sql = "UPDATE shortposts SET likes = likes + 1 WHERE idshortpost = :idPost;";
    } else {
        $sql = "UPDATE shortposts SET likes = likes - 1 WHERE idshortpost = :idPost;";
    }
    $statement = $con->prepare($sql);
    $statement->bindParam(":idPost", $idPost, PDO::PARAM_INT);
    $res = $statement->execute();

    // Verifica si la actualización fue exitosa
    if($res) {
        // Si el estado es "corazón", inserta un nuevo registro en la tabla de "me gusta" cortos
        // Si no, elimina el registro correspondiente de la tabla de "me gusta" cortos
        if($status=="heart") {
            $sql = "INSERT INTO shortlikes (post, idUser) VALUES (:post, :idUser);";
        } else {
            $sql = "DELETE FROM shortlikes WHERE post = :post AND idUser = :idUser;";
        }
        $statement = $con->prepare($sql);
        $statement->bindParam(":post", $idPost, PDO::PARAM_INT);
        $statement->bindParam(":idUser", $username, PDO::PARAM_STR);
        $res = $statement->execute();

        // Prepara la respuesta en formato JSON
        $dato = array();
        if($res) {
            $dato = array(
                'status' => "ok"
            );
        } else {
            $dato[] = array('message' => 'No se encontraron usuarios');
        }
        echo json_encode($dato);
    }
} catch(PDOException $e) {
    // En caso de error, devuelve un mensaje de error en formato JSON
    echo json_encode(array('message'=> $e->getMessage()));
} finally {
    // Cierra la conexión a la base de datos
    close_connection($con);
}
?>
