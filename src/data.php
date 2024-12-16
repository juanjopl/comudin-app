<?php
//El archivo data.php sirve para recoger el ultimo mensaje ya sea enviado o recibido en la mensajeria de la aplicación

require_once("logic/entity/user.php");
require_once("logic/gnl_functions.php");

$user = $_SESSION["user"];

foreach($rows as $row) {
    $sql = "SELECT * FROM messages WHERE (incoming_msg_id = :username OR outgoing_msg_id = :username) AND (outgoing_msg_id = :row_username OR incoming_msg_id = :row_username) ORDER BY msg_id DESC LIMIT 1;";
    $statement = $con->prepare($sql);
    $statement->bindParam(":username", $user->username, PDO::PARAM_STR);
    $statement->bindParam(":row_username", $row['username'], PDO::PARAM_STR);
    $statement->execute();
    $res = $statement->fetch(PDO::FETCH_ASSOC);
    if($statement->rowCount() > 0) {
        //El siguiente if comprobará que el mensaje no supere una longitud de 28 caracteres, en caso de que los supere, se mostrarán 3 puntos para no perjudicar el Front de la página.
        if (strlen($res["msg"]) > 28) {
            $msg = substr($res["msg"], 0, 35) . '...';
        } else {
            $msg = $res["msg"];
        }        
        ?>
        <div class="listUser" id="listUser">
            <!-- En el div .userRow1 se mostrará la foto y el nombre de usuario del usuario con el que hay conversacion existente -->
            <div class="userRow1">
                <img src="<?php echo ($row["foto"] == null) ? 'assets/logo.webp' : 'data:image/jpeg;base64, '. base64_encode($row["foto"]); ?>" >
            </div>
            <!-- En el div .userRow2 se mostrará el ultimo mensaje de la conversacion, si es enviado irá despues de un "Tu:" para que el usuario sepa que lo mandó él, 
            si no, solamente aparece el mensaje para que el usuario sepa que se lo mandaron a él -->
            <div class="userRow2">
                <?php  
                    ?>
                        <span id="usernameChat"><?php echo $row["username"]; ?></span>
                    <?php 
                    if($res["outgoing_msg_id"] == $user->username) {
                        ?>
                            <p>Tu: <?php echo $msg; ?></p>
                        <?php
                    } else {
                        ?>
                            <p><?php echo $msg; ?></p>
                        <?php
                    }
                ?>
            </div>
            <div class="userRow3">
                <?php
                    if($res["state"] == "no leido" && $res["outgoing_msg_id"] != $user->username) {
                        ?>
                            <span><ion-icon name="ellipse" id="state"></ion-icon></span>
                        <?php
                    }
                ?>
            </div>
        </div>
<?php
    }
}
?>
