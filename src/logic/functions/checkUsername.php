<?php
require_once('../gnl_functions.php');
require_once('../entity/user.php');

    $usernameUpd = $_GET["user"];
    try {
        $con = get_connection();
        $sql = "select username from users where username = :username";
        $statement = $con->prepare($sql);
        $statement->bindParam(":username", $usernameUpd, PDO::PARAM_STR);
        $statement->execute();
        if($statement->rowCount() > 0) {
            $data[] = array('message' => 'Ya existe un usuario con ese nombre de usuario');
        }else {
            $data[] = array('message' => '');
        }
        // Establecer el encabezado de contenido como JSON
        header('Content-Type: application/json');
        // Devolver los datos en formato JSON
        echo json_encode($data);
    } catch(PDOException $e) {
        echo "".$e->getMessage();
    } finally {
        close_connection($con);
    }
?>