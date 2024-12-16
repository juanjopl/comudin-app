<?php
// Incluye los archivos de funciones generales y de la entidad de usuario
require_once('../gnl_functions.php');
require_once('../entity/user.php');

// Inicia la sesión
session_start();

// Verifica si hay un usuario en sesión
if(isset($_SESSION['user'])){
    // Obtiene el nombre de usuario saliente de la sesión
    $outgoingUser = $_SESSION['user']->username;
    // Obtiene el nombre de usuario entrante enviado por el formulario
    $incomingUser = $_POST['incomingUser'];
    // Obtiene el mensaje enviado por el formulario
    $message = $_POST["message"];

    // Verifica si el mensaje no está vacío
    if(!empty($message)){
        try {
            // Establece conexión a la base de datos
            $con = get_connection();
            // Prepara y ejecuta la consulta SQL para insertar el mensaje en la base de datos
            $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, state)
                    VALUES (:incomingUser, :outgoingUser, :message, 'no leido')";
            $statement = $con->prepare($sql);
            $statement->bindParam(":incomingUser", $incomingUser, PDO::PARAM_STR);
            $statement->bindParam(":outgoingUser", $outgoingUser, PDO::PARAM_STR);
            $statement->bindParam(":message", $message, PDO::PARAM_STR);
            $statement->execute();
        } catch (Exception $e) {
            // Maneja cualquier excepción ocurrida durante la inserción del mensaje
            echo "Error al enviar el mensaje: ". $e->getMessage();
        } finally {
            // Cierra la conexión a la base de datos
            close_connection($con);
        }
    } else {
        // Si el mensaje está vacío, muestra un mensaje de advertencia
        echo "El mensaje está vacío.";
    }
} else {
    // Si no hay usuario en sesión, redirige a la página de inicio de sesión
    header("location: ../index.php");
}
?>
