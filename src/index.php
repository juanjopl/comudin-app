<?php
    require_once("logic/config/config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="styles/CSS/main.css">
</head>
<body class="index">
    <main class="mainIndex">
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="login">
            <form action="logic/functions/authUser.php" method="post">
                <label for="chk" aria-hidden="true">Inicio sesión</label>
                <input type="text" name="user" placeholder="Nombre de usuario">
                <input type="password" name="pass" placeholder="Contraseña">
                <?php
                    if(isset($_GET["errLog"])) {
                        ?>
                            <span id="loginText"><?php echo $error[$_GET['errLog']] ?></span>
                        <?php
                    }
                ?>
                <button>Iniciar Sesión</button>
            </form>
        </div>

        <div class="signup">
            <form action="logic/functions/regUser.php" method="post" id="form">
                <label for="chk" aria-hidden="true">Registro</label>
                <div class="inputBox">
                    <input type="text" name="userReg" id="user" placeholder="Nombre de usuario">
                    <span id="userText"></span>
                </div>
                <div class="inputBox">
                    <input type="email" name="emailReg" id="email" placeholder="Correo electrónico">
                    <span id="emailText"></span>
                </div>
                <div class="inputBox">
                    <input type="password" name="passReg" id="pass" placeholder="Contraseña">
                    <span id="passText"></span>
                </div>
                <div class="buttonBox">
                    <button id="submitButton">Registrar</button>
                    <span id="buttonText">Rellena todos los campos</span>
                    <?php
                        if(isset($_GET["errReg"])) {
                            ?>
                                <span id="loginText"><?php echo $error[$_GET['errReg']] ?></span>
                            <?php
                        }
                    ?>
                </div>
            </form>
        </div>
    </main>
</body>
<script src="scripts/fillRegions.js"></script>
<script src="scripts/registrationValidation.js"></script>
</html>