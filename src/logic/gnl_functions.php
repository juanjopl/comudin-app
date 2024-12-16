<?php
// Función para obtener una conexión a la base de datos
function get_connection() {
    // Configuración de la conexión a la base de datos
    $dsn = 'mysql:host=localhost;dbname=comudindb';
    $user = 'comudindb';
    $pass = 'comudindb';
    try {
        // Crear una nueva instancia de PDO para la conexión
        $con = new PDO($dsn, $user, $pass);
        // Configurar el modo de error para que lance excepciones
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Si hay un error en la conexión, mostrar el mensaje de error
        echo 'Fallo en la conexion: ' . $e->getMessage();
    }
    return $con;
}

// Función para cerrar la conexión a la base de datos
function close_connection($con) {
    $con = null; // Cerrar la conexión asignando null
}

// Función para validar el registro de un usuario
function registrationValidation($obj) {
    try {
        $con = get_connection();

        // Verificar si el nombre de usuario ya existe en la base de datos
        $sql = "select username from users where username=:username";
        $statement = $con->prepare($sql);
        $statement->bindParam(":username", $obj->username, PDO::PARAM_STR);
        $statement->execute();
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            // Si el nombre de usuario ya existe, redirigir con un mensaje de error
            header("Location:../../index.php?errReg=AUTH_USERNAME_EXIST");
            close_connection($con);
            exit();
        }

        // Verificar si el correo electrónico ya existe en la base de datos
        $sql = "select email from users where email=:email";
        $statement = $con->prepare($sql);
        $statement->bindParam(":email", $obj->email, PDO::PARAM_STR);
        $statement->execute();
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            // Si el correo electrónico ya existe, redirigir con un mensaje de error
            header("Location:../../index.php?errReg=AUTH_EMAIL_EXIST");
            close_connection($con);
            exit();
        }

        close_connection($con);
    } catch (PDOException $e) {
        // Si hay un error en la ejecución, mostrar el mensaje de error
        echo "" . $e->getMessage();
        return false;
    }
    return true;
}

// Función para verificar si un usuario sigue a otro
function isFollowing($follower, $following) {
    try {
        $con = get_connection();

        // Consultar si hay una relación de seguimiento entre los usuarios
        $sql = "select * from tracking where follower = :follower and following = :following;";
        $statement = $con->prepare($sql);
        $statement->bindParam(":follower", $follower, PDO::PARAM_STR);
        $statement->bindParam(":following", $following, PDO::PARAM_STR);
        $statement->execute();
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        
        // Retornar true si hay una relación de seguimiento, de lo contrario false
        if ($res) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        // Si hay un error en la ejecución, mostrar el mensaje de error
        echo "Error:" . $e->getMessage();
    } finally {
        close_connection($con); // Cerrar la conexión a la base de datos
    }
}

// Función para verificar si un usuario ha dado "me gusta" a una publicación de Instagram
function lookInstaPost($idPost, $username) {
    try {
        $con = get_connection();

        // Consultar si el usuario ha dado "me gusta" a la publicación de Instagram
        $sql = "SELECT idinstalikes FROM instalikes WHERE post = :post AND idUser = :username;";
        $statement = $con->prepare($sql);
        $statement->bindParam(":post", $idPost, PDO::PARAM_INT);
        $statement->bindParam(":username", $username, PDO::PARAM_STR);
        $statement->execute();
        
        // Retornar true si hay un registro, de lo contrario false
        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        // Si hay un error en la ejecución, mostrar el mensaje de error
        echo "" . $e->getMessage();
    } finally {
        close_connection($con); // Cerrar la conexión a la base de datos
    }
}

// Función para verificar si un usuario ha dado "me gusta" a una publicación corta
function lookShortPost($idPost, $username) {
    try {
        $con = get_connection();

        // Consultar si el usuario ha dado "me gusta" a la publicación corta
        $sql = "SELECT idshortlikes FROM shortlikes WHERE post = :post AND idUser = :username;";
        $statement = $con->prepare($sql);
        $statement->bindParam(":post", $idPost, PDO::PARAM_INT);
        $statement->bindParam(":username", $username, PDO::PARAM_STR);
        $statement->execute();
        
        // Retornar true si hay un registro, de lo contrario false
        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        // Si hay un error en la ejecución, mostrar el mensaje de error
        echo "" . $e->getMessage();
    } finally {
        close_connection($con); // Cerrar la conexión a la base de datos
    }
}
?>
