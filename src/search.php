<?php
require_once("logic/entity/user.php");
    session_start();
    if (!isset($_SESSION["user"])) {
        header("Location: index.php");
        session_destroy();
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
        <div class="search">
            <input type="text" name="search" id="searchInput" placeholder="Buscar">
            <div class="results" id="results">

            </div>
        </div>
    </main>
    <nav class="navbar2">
        <ul>
            <li>
                <a href="home.php">
                    <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                </a>
            </li>
            <li class="active point">
                <a href="search.php">
                    <span class="icon"><ion-icon name="search-outline"></ion-icon></span>
                </a>
            </li>
            <li>
                <a href="profile.php">
                    <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
                </a>
            </li>
            <li>
                <a href="logic/functions/logoutUser.php">
                    <span class="icon"><ion-icon name="log-out-outline"></ion-icon></span>
                </a>
            </li>
            <div class="indicator2"><span></span></div>
        </ul>
    </nav>
</body>
<script src="scripts/search.js"></script>
<script src="scripts/navbar.js"></script>
<script src="../reload.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>