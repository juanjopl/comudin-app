<?php
require_once("../entity/user.php");
require_once("../gnl_functions.php");
session_start();
$username = $_GET["username"];

if (isset($_SESSION["user"])) {

    try {
        // Obtener la conexión
        $con = get_connection();

        // Definir la consulta SQL
        $sql = "
            SELECT 
                t.follower AS username, 
                u2.foto 
            FROM 
                users AS u 
            JOIN 
                tracking AS t ON u.username = t.following 
            JOIN 
                users AS u2 ON t.follower = u2.username 
            WHERE 
                t.following = :user;
        ";

        // Preparar la consulta
        $statement = $con->prepare($sql);

        // Vincular el parámetro
        $statement->bindParam(":user", $username, PDO::PARAM_STR);

        // Ejecutar la consulta
        $statement->execute();

        // Obtener los resultados
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Preparar los datos a devolver
        $data = array();

        if ($result) {
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

        // Establecer el encabezado de contenido como JSON
        header('Content-Type: application/json');

        // Devolver los datos en formato JSON
        echo json_encode($data);

    } catch (Exception $e) {
        // Si hay un error, devolver un mensaje de error en JSON
        header('Content-Type: application/json');
        echo json_encode(array('message' => $e->getMessage()));
    } finally {
        // Cerrar la conexión a la base de datos
        close_connection($con);
    }
} else {
    // Manejar el caso en que no hay sesión iniciada
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'No hay sesión iniciada'));
}
?>
