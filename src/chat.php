<?php
require_once("logic/entity/user.php");
require_once("logic/gnl_functions.php");
session_start();
if (!isset($_SESSION['user'])) {
  header("location: index.php");
}
$userId = $_GET["userId"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Chat</title>
  <link rel="stylesheet" href="styles/CSS/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <?php
        try {
        $con = get_connection();
        $sql = "select * from users where username = :userId";
        $statement = $con->prepare($sql);
        $statement->bindParam(":userId", $userId, PDO::PARAM_STR);
        $statement->execute();
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "". $e->getMessage() ."";
        }finally {
            close_connection($con);
        }
        ?>
        <a href="users.php" class="back-icon"><ion-icon name="arrow-back-outline" class="arrowBackChat"></ion-icon></a>
        <img src="<?php echo ($res["foto"] == null) ? 'assets/logo.webp' : 'data:image/jpeg;base64, '. base64_encode($res["foto"]); ?>" >
        <div class="details">
          <span><?php echo $res['username'] ?></span>
        </div>
      </header>
      <div class="chat-box">

      </div>
      <form action="#" class="typing-area">
        <input type="hidden" name="incomingUser" class="incomingId" value="<?php echo $userId; ?>">
        <input type="text" name="message" class="input-field" placeholder="Envía un mensaje..." autocomplete="off">
        <button>Enviar</button>
      </form>
    </section>
  </div>
<script>
  function scrollToBottom() {
    let chatBox = document.getElementById('chatBox');
    chatBox.scrollTop = content.scrollHeight;
  }
</script>
<script src="scripts/chat.js"></script>
<script src="../reload.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>