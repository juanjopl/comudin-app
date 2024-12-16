<?php
// Incluir el archivo de funciones generales y la definición de la entidad de usuario
require_once('../gnl_functions.php');
include_once('../entity/user.php');

// Iniciar sesión para acceder a la información del usuario actual
session_start();

// Establecer el tipo de contenido como JSON para la respuesta
header('Content-Type: application/json');

// Obtener el nombre de usuario de la consulta GET y el usuario actual de la sesión
$user = $_GET['user'];
$userEx = $_SESSION["user"];

// Establecer conexión a la base de datos
$con = get_connection();

try {
    // Consultar usuarios cuyos nombres de usuario coincidan parcialmente con el proporcionado y no sea el usuario actual
    $sql = "select * from users where username not like :userEx and username like concat(:user, '%')";
    $statement = $con->prepare($sql);
    $statement->bindParam(':user', $user, PDO::PARAM_STR);
    $statement->bindParam(':userEx', $userEx->username, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        $data = array();
        // Procesar los resultados obtenidos
        foreach ($result as $row) {
            // Codificar la imagen de perfil en base64
            $foto = base64_encode($row['foto']);

            // Construir el objeto de datos para el usuario
            $dato = array(
                'username' => $row['username'],
                'pic' => $foto
            );

            // Agregar el objeto de datos al array de datos
            $data[] = $dato;
        }
    } else {
        // Si no se encontraron usuarios, agregar un mensaje al array de datos
        $data[] = array('message' => 'No se encontraron usuarios');
    }

    // Devolver los datos como JSON
    echo json_encode($data);
} catch (Exception $e) {
    // Si hay un error, devolver un mensaje de error en JSON
    echo json_encode(array('message' => $e->getMessage()));
} finally {
    // Cerrar la conexión a la base de datos
    close_connection($con);
}
?>
