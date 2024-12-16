<?php
// Incluir el archivo de funciones generales y la definición de la entidad de usuario
require_once('../gnl_functions.php');
include_once('../entity/user.php');

// Obtener los valores de usuario y contraseña del formulario
$user = $_POST['user'];
$pass = $_POST['pass'];

// Intentar realizar la autenticación del usuario
try {
    // Establecer conexión a la base de datos
    $con = get_connection();
    
    // Consultar si existe un usuario con el nombre proporcionado
    $sql = "select * from users where username = :user";
    $statement = $con->prepare($sql);
    $statement->bindParam(":user", $user, PDO::PARAM_STR);
    $statement->execute();
    $res = $statement->fetch(PDO::FETCH_ASSOC);
    
    // Verificar si se encontró un usuario y si la contraseña coincide
    if ($res && password_verify($pass, $res["password"])) {
        // Iniciar sesión y almacenar el objeto de usuario en la sesión
        session_start();
        $_SESSION['user'] = User::parse($res);
        
        // Redirigir al usuario a la página de inicio
        header("Location: ../../home.php");
    } else {
        // Si el usuario no existe o la contraseña no coincide, redirigir con un mensaje de error
        header("Location: ../../index.php?errLog=NOT_EXIST");
    }
    
    // Cerrar la conexión a la base de datos
    close_connection($con);
} catch (PDOException $e) {
    // Si hay un error en la conexión, mostrar el mensaje de error
    echo 'Fallo en la conexion: ' . $e->getMessage();
} finally {
    // Cerrar la conexión a la base de datos en cualquier caso
    close_connection($con);
}
?>
