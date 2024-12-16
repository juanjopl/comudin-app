<?php
    require_once(__DIR__ . "/../gnl_functions.php");
    require_once(__DIR__ . "/user.php");
    class InstaPost {
        //Propiedades
        public $id;
        public $photo;
        public $description = null;
        public $ubication = null;
        public $username;
        public $perfilPhoto = null;
        public $likes;

        //Funcion para formar un objeto InstaPost
        public static function parse($data) {
            $obj = new InstaPost();
            $obj->id = $data["idinstapost"];
            $obj->photo = $data["photo"];
            $obj->description = $data["description"];
            $obj->ubication = $data["ubication"];
            $obj->username = $data["idUser"];
            $obj->perfilPhoto = $data["perfilPhoto"];
            $obj->likes = $data["likes"];
            return $obj;
        }
        //Funcion que recoge todos los instaposts que se encuentran subidos en la aplicacion, pero con la condicion de que los usuarios sean seguidos del usuario que se encuentra activo (este caso existe en la parte del inicio)
        //Además de eso, existe el caso de que recoja los instaposts de un usuario en concreto (este caso solo existe en la parte del perfil)
        public static function getHomeInstaPost($user, $profile = null) {
            try {
                //Se crea la conexión
                $con = get_connection();
                if(!isset($profile)) {
                    $sql = "SELECT DISTINCT i.idinstapost, i.photo, i.description, i.ubication, i.idUser, i.likes, u.foto as perfilPhoto 
                    FROM instaposts AS i
                    JOIN tracking AS t ON i.idUser = t.following
                    JOIN users as u ON i.idUser = u.username
                    WHERE t.follower = :username
                    ORDER BY i.idinstapost DESC;";
                }else {
                    $sql = "SELECT DISTINCT i.idinstapost, i.photo, i.description, i.ubication, i.idUser, i.likes, u.foto as perfilPhoto 
                    FROM instaposts AS i
                    JOIN users as u ON i.idUser = u.username
                    WHERE i.idUser = :username
                    ORDER BY i.idinstapost DESC;";
                }
                $statement = $con->prepare($sql);
                //Se usa la funcion bindParam y se le pasan todos los parametros para dar seguridad al script SQL
                $statement->bindParam(":username", $user->username, PDO::PARAM_STR);
                $statement->execute();
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    //Se forma un array con objetos InstaPosts para luego ser devueltos y con la ayuda de un foreach, puedan ser mostrados
                    $instaposts[] = InstaPost::parse($row);
                }
                if (empty($instaposts)) {
                    return null;
                } else {
                    return $instaposts;
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