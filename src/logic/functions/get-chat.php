<?php
// Incluye el archivo de funciones generales y la definición de la entidad de usuario
require_once('../gnl_functions.php');
require_once('../entity/user.php');

// Inicia sesión para acceder a la información del usuario
session_start();

// Verifica si hay un usuario en sesión
if (isset($_SESSION['user'])) {
    // Obtiene el usuario en sesión y el usuario entrante
    $user = $_SESSION['user'];
    $incomingUser = $_POST["incomingId"];
    $outgoingUser = $user->username;
    $output = "";

    // Establece conexión a la base de datos
    $con = get_connection();
    
    // Prepara y ejecuta la consutla SQL para actualizar el estado de todos los mensajes correspondientes
    $updateSql = "UPDATE messages SET state = 'leido'
    WHERE incoming_msg_id = :otherUser AND outgoing_msg_id = :currentUser";
    $updateStatement = $con->prepare($updateSql);
    $updateStatement->bindParam(":currentUser", $incomingUser, PDO::PARAM_STR);
    $updateStatement->bindParam(":otherUser", $outgoingUser, PDO::PARAM_STR);
    $updateStatement->execute();
    // Prepara y ejecuta la consulta SQL para obtener los mensajes entre los usuarios
    $sql = "SELECT * FROM messages LEFT JOIN users ON users.username = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = :outgoingUser AND incoming_msg_id = :incomingUser)
                OR (outgoing_msg_id = :incomingUser AND incoming_msg_id = :outgoingUser) ORDER BY msg_id";
    $statement = $con->prepare($sql);
    $statement->bindParam(":incomingUser", $incomingUser, PDO::PARAM_STR);
    $statement->bindParam(":outgoingUser", $outgoingUser, PDO::PARAM_STR);
    $statement->execute();

    // Verifica si se obtuvieron resultados de la consulta
    if ($statement->rowCount() > 0) {
        // Recorre los resultados y muestra los mensajes
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if ($row['outgoing_msg_id'] === $outgoingUser) {
                ?>
                <div class="chat outgoing">
                    <div class="details">
                        <p><?php echo $row["msg"] ?></p>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="chat incoming">
                    <div class="details">
                        <p><?php echo $row["msg"] ?></p>
                    </div>
                </div>
                <?php
            }
        }
    } else {
        // Si no hay mensajes, muestra un mensaje indicando que no hay mensajes disponibles
        ?>
        <div class="text" style="color: whitesmoke">No hay mensajes disponibles. Una vez que envíes el mensaje, aparecerán aquí.</div>
        <?php
    }
} else {
    // Si no hay usuario en sesión, redirige a la página de inicio de sesión
    header("location: ../index.php");
}
?>
