<?php
    require_once(__DIR__ . "/../gnl_functions.php");
    require_once(__DIR__ . "/user.php");
    class ShortPost {
        //Propiedades
        public $id;
        public $message = null;
        public $username;
        public $perfilPhoto = null;
        public $likes;
        public $comments;

        //Funcion para formar un objeto ShortPost
        public static function parse($data) {
            $obj = new ShortPost();
            $obj->id = $data["idshortpost"];
            $obj->message = $data["message"];
            $obj->username = $data["idUser"];
            $obj->perfilPhoto = $data["perfilPhoto"];
            $obj->likes = $data["likes"];
            $obj->comments = $data["comments"];
            return $obj;
        }

        //Funcion que recoge todos los shortposts que se encuentran subidos en la aplicacion, pero con la condicion de que los usuarios sean seguidos del usuario que se encuentra activo (este caso existe en la parte del inicio)
        //Además de eso, existe el caso de que recoja los shortposts de un usuario en concreto (este caso solo existe en la parte del perfil)
        public static function getHomeShortPost($user, $profile = null) {
            try {
                //Se crea la conexión
                $con = get_connection();
                if(!isset($profile)) {
                    $sql = "SELECT DISTINCT s.idshortpost, s.message, s.idUser, s.likes, s.comments, u.foto as perfilPhoto 
                    FROM shortposts AS s
                    JOIN tracking AS t ON s.idUser = t.following
                    JOIN users as u ON s.idUser = u.username
                    WHERE t.follower = :username
                    ORDER BY s.idshortpost DESC;";   
                }else {
                    $sql = "SELECT DISTINCT s.idshortpost, s.message, s.idUser, s.likes, s.comments, u.foto as perfilPhoto 
                    FROM shortposts AS s
                    JOIN users as u ON s.idUser = u.username
                    WHERE s.idUser = :username
                    ORDER BY s.idshortpost DESC;"; 
                }
                $statement = $con->prepare($sql);
                //Se usa la funcion bindParam y se le pasan todos los parametros para dar seguridad al script SQL
                $statement->bindParam(":username", $user->username, PDO::PARAM_STR);

                $statement->execute();
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    //Se forma un array con objetos ShortPosts para luego ser devueltos y con la ayuda de un foreach, puedan ser mostrados
                    $shortposts[] = ShortPost::parse($row);
                }
                if (empty($shortposts)) {
                    return null;
                } else {
                    return $shortposts;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            } finally {
                //Cierra la conexión
                close_connection($con);
            }
        }
    }
?>