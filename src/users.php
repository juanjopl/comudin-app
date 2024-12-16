<?php
require_once("logic/entity/user.php");
    session_start();
    if (!isset($_SESSION["user"])) {
        header("Location: logic/functions/logoutUser.php");
        exit();
    }else {
        $user = $_SESSION['user'];
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles/CSS/main.css">
</head>
<body class="allbody">
    <main>
        <a href="home.php" class="linkArrowBackChat">
            <ion-icon name="arrow-back-outline" class="arrowBackChat"></ion-icon>
        </a>
        
        <div class="search">
            <input type="text" name="search" id="searchInput" placeholder="Buscar">
            <div class="results" id="results">

            </div>
        </div>
        <div class="users">
            <?php
            try {
                $con = get_connection();
                $sql = "select * from users where not username = :username ;";
                $statement = $con->prepare($sql);
                $statement->bindParam(":username", $user->username, PDO::PARAM_STR);
                $statement->execute();
                $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                if($statement->rowCount() == 0) {
                    ?>
                        <span style="color: whitesmoke;">No has iniciado conversacion con ningún usuario</span>;
                    <?php
                }else {
                    include_once "data.php";
                }
            } catch(PDOException $e) {
                echo "". $e->getMessage() ."";
            }finally {
                close_connection($con);
            }
            ?>
        </div>
    </main>
</body>
<script>
    // Obtiene todos los elementos con la clase 'userRow2'
let userRow2 = document.querySelectorAll(".userRow2");

// Itera sobre cada elemento con la clase 'userRow2'
userRow2.forEach(row => {
    // Obtiene el primer elemento 'span' dentro de la fila
    let span = row.querySelector("span");
    // Obtiene el primer elemento 'p' dentro de la fila
    let paragraph = row.querySelector("p");

    // Agrega un evento de escucha al hacer clic en la fila
    row.addEventListener("click", () => {
        // Redirige a la página de chat con el ID de usuario obtenido del 'span'
        window.location.href = `chat.php?userId=${span.textContent}`;
    });

    // Agrega un evento de escucha al hacer clic en el 'span'
    span.addEventListener("click", () => {
        // Redirige a la página de chat con el ID de usuario obtenido del 'span'
        window.location.href = `chat.php?userId=${span.textContent}`;
    });

    // Agrega un evento de escucha al hacer clic en el 'p'
    paragraph.addEventListener("click", () => {
        // Redirige a la página de chat con el ID de usuario obtenido del 'span'
        window.location.href = `chat.php?userId=${span.textContent}`;
    });
});
</script>
<script src="scripts/searchChat.js"></script>
<script src="../reload.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>