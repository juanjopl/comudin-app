<?php
require_once(__DIR__ . "/../gnl_functions.php");
    class User {
        //Propiedades
        public $id;
        public $username;
        public $email;
        public $password;
        public $name;
        public $date;
        public $region;
        public $foto;
        public $followers;
        public $following;
        public $posts;

        //Funcion para formar un objeto User
        public static function parse($data) {
            $obj = new User();
            $obj->id = $data["idUser"];
            $obj->username = $data["username"];
            $obj->email = $data["email"];
            $obj->password = $data["password"];
            $obj->name = $data["name"];
            $obj->date = $data["date"];
            $obj->region = $data["region"];
            $obj->foto = $data["foto"];
            $obj->followers = $data["followers"];
            $obj->following = $data["following"];
            $obj->posts = $data["posts"];
            return $obj;
        }
        //Funcion para crear un usuario, es llamado despues de rellenar el formulario de Crear Usuario
        public static function addUser($obj) {
            if(registrationValidation($obj)) {
                $passHash = password_hash($obj->password, PASSWORD_DEFAULT);
                $obj->password = $passHash;
                try {
                    //Se genera la conexión
                    $con = get_connection();
                    $sql = "insert into users (username, email, password, posts, followers, following)
                    values (:username, :email, :password, :posts, :followers, :following);";
                    $stmt = $con->prepare($sql);
                    //Se usa la funcion bindParam y se le pasan todos los parametros para dar seguridad al script SQL
                    $stmt->bindParam(":username", $obj->username, PDO::PARAM_STR);
                    $stmt->bindParam(":email", $obj->email, PDO::PARAM_STR);
                    $stmt->bindParam(":password", $obj->password, PDO::PARAM_STR);
                    $stmt->bindParam(":posts", $obj->posts, PDO::PARAM_INT);
                    $stmt->bindParam(":followers", $obj->followers, PDO::PARAM_INT);
                    $stmt->bindParam(":following", $obj->following, PDO::PARAM_INT);
                    //Ejecuta el script
                    $res = $stmt->execute();
                    if($res) {
                        //Si se ejecuta correctamente, el objeto User pasado por parametro pasará a ser un $_SESSION
                        session_start();
                        $_SESSION['user'] = $obj;
                        return true;
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }else {
                return false;
            }
        }
        //Funcion para cambiar la foto de perfil, ya sea al crear una cuenta o directamente en la parte de perfil
        public static function changePic($user, $image) {
            $info = @getimagesize($image);
    
            if ($info !== false) {
                $tipo = $info['mime'];
                    $imageData = file_get_contents($image);
                    //Se genera la conexión
                    try {
                    $con = get_connection();
                    $sql = "update users set foto = :foto WHERE username= :username";
                    $statement = $con ->prepare($sql);
                    //Se usa la funcion bindParam y se le pasan todos los parametros para dar seguridad al script SQL
                    $statement->bindParam(":username", $user->username, PDO::PARAM_STR);
                    $statement->bindParam(":foto", $imageData, PDO::PARAM_LOB);
    
                    $result = $statement->execute();
    
                    if ($result) {
                        $user->foto = $imageData;
                    }
                    return true;
                    }catch(PDOException $e) {
                        echo "Error:".$e->getMessage();
                    }finally {
                        //Cierra la conexión
                        close_connection($con);
                    }  
            } else {
                return false;
            }
        }
        /*
        Funcion que se ejecuta en caso de que se quiera añadir los campos de nombre, pais, foto de perfil y fecha de nacimiento al crear 
        una cuenta en la aplicacion.
        */
        public static function addAdditionalOptions($user, $date, $name, $region, $image) {
            //Se genera la conexión
            $con = get_connection();
            /*
            Todos los ifs sirven para comprobar que parametros se han pasado y cuales no, además de eso, el que sea diferente de null crea 
            el script y lo ejecuta para pasar el dato a base de datos.
            */
            if($image != null) {
                User::changePic($user, $image);
            }
            if($name != null) {
                $sql = "update users set name = :name where username = :username";
                $statement = $con -> prepare($sql);
                //Se usa la funcion bindParam y se le pasan todos los parametros para dar seguridad al script SQL
                $statement->bindParam(":name", $name, PDO::PARAM_STR);
                $statement->bindParam(":username", $user->username, PDO::PARAM_STR);
                $statement->execute();
                $user->name = $name;
            }
            if($region != null) {
                $sql = "update users set region = :region where username = :username";
                $statement = $con -> prepare($sql);
                //Se usa la funcion bindParam y se le pasan todos los parametros para dar seguridad al script SQL
                $statement->bindParam(":region", $region, PDO::PARAM_STR);
                $statement->bindParam(":username", $user->username, PDO::PARAM_STR);
                $statement->execute();
                $user->region = $region;
            }
            if($date != null) {
                $sql = "update users set date = :date where username = :username";
                $statement = $con -> prepare($sql);
                //Se usa la funcion bindParam y se le pasan todos los parametros para dar seguridad al script SQL
                $statement->bindParam(":date", $date, PDO::PARAM_STR);
                $statement->bindParam(":username", $user->username, PDO::PARAM_STR);
                $statement->execute();
                $user->date = $date;
            }
            //Cierra la conexión
            close_connection($con);
            //Redirige a la pagina de inicio de la aplicación con la cuenta de usuario creada.
            header("Location: ../../home.php");
            exit();
        }
        //Función que sirve para recoger todos los datos de un usuario diferente al propio, es decir, uno que se haya buscado o que se haya accedido desde alguna publicación.
        public static function catchUser($user) {
            try {
                //Se crea la conexión
                $con = get_connection();
                $sql = "select * from users where username = :user";
                $statement = $con -> prepare($sql);
                //Se usa la funcion bindParam y se le pasan todos los parametros para dar seguridad al script SQL
                $statement->bindParam(":user", $user, PDO::PARAM_STR);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                if($result) {
                    return User::parse($result);
                }else {
                    return null;
                }
            }catch(PDOException $e) {
                echo "Error:".$e->getMessage();
            }finally {
                //Cierra la conexión
                close_connection($con);
            }
        }
        //Función que sirve recargar las estadisticas del usuario (seguidores, seguidos y el nº de publicaciones).
        public static function rechargeDatesProfile($user) {
            try {
                //Se crea la conexión
                $con = get_connection();
                $sql = "select * from users where username = :user";
                $statement = $con->prepare($sql);
                //Se usa la funcion bindParam y se le pasan todos los parametros para dar seguridad al script SQL
                $statement->bindParam(":user", $user->username, PDO::PARAM_STR);
                $statement->execute();
                $res = $statement->fetch(PDO::FETCH_ASSOC);
                if($res) {
                    //Recoge las estadisticas del usuario y los mete en el objeto User que se pasa por pametro.
                    $user->followers = $res["followers"];
                    $user->following = $res["following"];
                    $user->posts = $res["posts"];
                }
            }catch(PDOException $e) {
                echo "Error:".$e->getMessage();
            }finally {
                //Cierra la conexión
                close_connection($con);
            }    
        }
        public static function updateUser($data, $user) {
            try {
                // Hashea la contraseña nueva del usuario
                $passHash = password_hash($data["pass"], PASSWORD_DEFAULT);
                
                // Obtén la conexión a la base de datos
                $con = get_connection();
                
                // Define la consulta SQL para actualizar el usuario en la base de datos
                $sql = "update users set username = :usernameUpd, region = :region, password = :password, name = :name where username = :username;";
                
                // Prepara la consulta SQL
                $statement = $con -> prepare($sql);
                
                // Vincula los parámetros de la consulta con los valores proporcionados
                $statement->bindParam(":usernameUpd", $data["username"], PDO::PARAM_STR);
                $statement->bindParam(":region", $data["region"], PDO::PARAM_STR);
                $statement->bindParam(":password", $passHash, PDO::PARAM_STR);
                $statement->bindParam(":name", $data["name"], PDO::PARAM_STR);
                $statement->bindParam(":username", $user->username, PDO::PARAM_STR);
                
                // Ejecuta la consulta
                $res = $statement->execute();
                
                // Si la consulta se ejecutó correctamente
                if($res) {
                    // Actualiza la información del usuario en la sesión
                    $_SESSION["user"]->username = $data["username"];
                    $_SESSION["user"]->name = $data["name"];
                    $_SESSION["user"]->region = $data["region"];
                    $_SESSION["user"]->password = $passHash;
                    
                    // Redirige al usuario a su perfil
                    header("Location: ../../profile.php");
                }
            } catch(PDOException $e) {
                // Muestra un mensaje de error si ocurre una excepción
                echo "".$e->getMessage();
            } finally {
                // Cierra la conexión a la base de datos
                close_connection($con);
            }
        }
    }
?>