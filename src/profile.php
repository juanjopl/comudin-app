<?php
require_once("logic/entity/instapost.php");
require_once("logic/entity/shortpost.php");
require_once("logic/entity/user.php");
require_once("chargeInstaPosts.php");
require_once("chargeShortPosts.php");
include_once("../src/logic/gnl_functions.php");
    session_start();
    if (!isset($_SESSION["user"])) {
        header("Location: index.php");
        session_destroy();
    }else if (isset($_GET["userSearched"])) {
        $user = User::catchUser($_GET["userSearched"]);
    }
    else {
        $user = $_SESSION['user'];
    }
    User::rechargeDatesProfile($user);
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
    <div class="fondo2" id="fondo"></div>
    <div class="options" id="options" hidden>
        <span id="exitButton">X</span>
        <a href="chat.php?userId=<?php echo $user->username ?>">Enviar mensaje</a>
    </div>
    <div class="stats" id="stats" hidden>
        <span id="exitButton2">X</span>
        <div id="result" class="result">

        </div>
    </div>
    <div class="update" id="update" hidden>
        <span id="exitButton3">X</span>
        <form action="logic/functions/updateUser.php" method="post" class="dataUpdate">
            <div class="updateBox">
                <span>Nombre</span>
                <input type="text" name="nameUpd" id="name" value="<?php echo $user->name ?>">
            </div>
            <div class="updateBox">
                <span>Nombre de usuario</span>
                <input type="text" name="userUpd" id="user" value="<?php echo $user->username ?>">
                <span id="userText"></span>
            </div>
            <div class="updateBox">
                <span>Selecciona tu país</span>
                <select name="regionUpd" id="regionSelect" required>
                </select>
            </div>
            <div class="updateBox">
                <span>Contraseña</span>
                <input type="password" name="passUpd" id="pass">
                <span id="passText"></span>
            </div>
            <div class="updateBox">
                <span>Confirmar contraseña</span>
                <input type="password" name="confirmpassUpd" id="confirmpass">
            </div>
            <span id="confirmpassText" style="text-align: center"></span>
            <span id="userUpdText" style="text-align: center"></span>
            <button type="submit" id="updateButton">Actualizar</button>
        </form>
    </div>
    <nav class="navbar1">
        <span id="username" style="margin-left: 15px">
            <?php
                echo $user->username;
            ?>
        </span>
        <ul style="justify-content: right;">
            <?php
                if(isset($_GET['userSearched'])){
                    ?>
                        <li>
                            <a>
                                <span class="icon"><ion-icon name="ellipsis-vertical" id="menu"></ion-icon></span>
                            </a>
                        </li>
                    <?php
                }
            ?>
        </ul>
    </nav>
    <main class="content">
        <div class="profileData">
            <div class="profile-info">
                <div class="profile-pic">
                    <?php
                        if(isset($_GET['userSearched'])){
                            ?>
                                <img src="<?php
                                if($user->foto == null){
                                    echo 'assets/logo.webp';
                                }else {
                                    echo 'data:image/jpeg;base64, '. base64_encode($user->foto);
                                }
                                ?>" id="profilePic">
                            <?php
                        }else {
                            ?>
                                <form action="logic/functions/changePic.php" method="POST" enctype="multipart/form-data" id="picForm">
                                    <input type="file" name="image" accept="image/webp" style="display:none;" id="fileInput">
                                    <label for="fileInput">
                                    <img src="<?php
                                        if($user->foto == null){
                                            echo 'assets/logo.webp';
                                        }else {
                                            echo 'data:image/jpeg;base64, '. base64_encode($user->foto);
                                        }
                                    ?>">
                                    </label>
                                </form>
                            <?php
                        }
                    ?>
                </div>
                <p id="actualName">
                    <?php
                        echo $user->name;
                    ?>
                </p>
                <p id="region">
                    <?php
                        echo $user->region;
                    ?>
                    <img id="imgRegion" alt="">
                </p>
            </div>
            <div class="profile-followers">
                <ul>
                    <li>
                        <span><?php echo $user->posts ?></span>
                        <p>Publicaciones</p>
                    </li>
                    <li>
                        <span id="followers"><?php echo $user->followers ?></span>
                        <p>Seguidores</p>
                    </li>
                    <li>
                        <span id="following"><?php echo $user->following ?></span>
                        <p>Siguiendo</p>
                    </li>
                </ul>
                <?php
                    if(!isset($_GET['userSearched'])){
                        ?>
                            <button id="edit">Editar perfil</button>
                        <?php
                    }else {
                        if(isFollowing($_SESSION["user"]->username, $user->username)) {
                            ?>
                                <form action="logic/functions/followUser.php?follow=true" method="post">
                                    <input type="hidden" name="following" value="<?php echo $user->username ?>">
                                    <button>Siguiendo</button>
                                </form>
                            <?php
                        }else {
                            ?>
                                <form action="logic/functions/followUser.php?follow=false" method="post">
                                    <input type="hidden" name="following" value="<?php echo $user->username ?>">
                                    <button>Seguir</button>
                                </form>
                            <?php
                        }
                        
                    }
                ?>
            </div>
        </div>
        <div class="profilePosts">
            <?php
                if(isset($_GET["ok"]) && $_GET["ok"] == "POSTUP"){
                    ?>
                        <span class="uploadedMessage" id="uploadedMessage">Post subido!!</span>
                    <?php
                }else if(isset($_GET["ok"]) && $_GET["ok"] == "POSTDELETED"){
                    ?>
                        <span class="uploadedMessage" id="uploadedMessage">Post borrado!!</span>
                    <?php
                }
            ?>
        </div>
        <div class="selectHomePost">
        <?php
            if ((isset($_GET["instaposts"]) || !isset($_GET["instaposts"])) && !isset($_GET["shortposts"])) {
                if(isset($_GET["userSearched"])){
                ?>
                    <a href="profile.php?userSearched=<?php echo $user->username ?>&instaposts" id="selectHomeInstaPost" class="active">InstaPost</a>
                    <a href="profile.php?userSearched=<?php echo $user->username ?>&shortposts" id="selectHomeShortPost" class="disabled">ShortPost</a>
                <?php
                }else {
                ?>
                    <a href="profile.php?instaposts" id="selectHomeInstaPost" class="active">InstaPost</a>
                    <a href="profile.php?shortposts" id="selectHomeShortPost" class="disabled">ShortPost</a>
                <?php
                }
            }else {
                if(isset($_GET["userSearched"])){
                ?>
                    <a href="profile.php?userSearched=<?php echo $user->username ?>&instaposts" id="selectHomeInstaPost" class="disabled">InstaPost</a>
                    <a href="profile.php?userSearched=<?php echo $user->username ?>&shortposts" id="selectHomeShortPost" class="active">ShortPost</a>
                <?php
                }else {
                ?>
                    <a href="profile.php?instaposts" id="selectHomeInstaPost" class="disabled">InstaPost</a>
                    <a href="profile.php?shortposts" id="selectHomeShortPost" class="active">ShortPost</a>
                <?php
                }
            }
        ?>
        </div>
        <section>
            <?php
            if ((isset($_GET["instaposts"]) || !isset($_GET["instaposts"])) && !isset($_GET["shortposts"])) {
                $instaposts = InstaPost::getHomeInstaPost($user, 1);
                if ($instaposts != null) {
                    chargeInstaPosts($instaposts);
                }else {
                    ?>
                        <span style="display: block; width: 100%; margin-top: 10px; text-align: center">No se ha subido ningún InstaPost</span>
                    <?php
                }
            }else {
                $shortposts = ShortPost::getHomeShortPost($user, 1);
                if ($shortposts != null) {
                    chargeShortPosts($shortposts);
                }else {
                    ?>
                        <span style="display: block; width: 100%; margin-top: 10px; text-align: center">No se ha subido ningún ShortPost</span>
                    <?php
                }
            }
            ?>
        </section>
    </main>
    <nav class="navbar2">
        <ul>
            <li>
                <a href="home.php">
                    <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                </a>
            </li>
            <li>
                <a href="search.php">
                    <span class="icon"><ion-icon name="search-outline"></ion-icon></span>
                </a>
            </li>
            <li class="active point">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
let followers = document.getElementById("followers");
let following = document.getElementById("following");
let exitButton2 = document.getElementById("exitButton2");
let username = document.getElementById("username").textContent.trim();

// Agrega un evento de escucha para cuando se hace clic en el botón del menú
followers.addEventListener("click", function() {
    // Remueve el atributo 'hidden' del menú de opciones
    stats.removeAttribute("hidden");
    // Cambia la clase del elemento con id "fondo" a "fondo"
    document.getElementById("fondo").className = "fondo";
    
    fetch("logic/functions/getFollowers.php?username="+username)
    .then(response => response.json())
    .then(data => {
        // Limpia el área de resultados
        result.innerHTML = "";
            // Itera sobre los datos de los usuarios obtenidos
            data.forEach(user => {
                // Crea un nuevo elemento para cada usuario
                let usuarioDiv = document.createElement("article");
                usuarioDiv.classList.add("userCard");
                usuarioDiv.setAttribute("id", "userSelected");

                // Establece la imagen del usuario
                if (user.pic === "") {
                    user.pic = "assets/logo.webp";
                } else {
                    user.pic = "data:image/jpeg;base64," + user.pic;
                }

                // Si hay un mensaje en el objeto de usuario, muestra el mensaje
                if ('message' in user) {
                    usuarioDiv.innerHTML = `
                        <p>${user.message}</p>
                    `;
                } else {
                    // Si no hay mensaje, muestra la imagen y el nombre de usuario
                    usuarioDiv.innerHTML = `
                        <img src="${user.pic}" alt="Foto de perfil">
                        <a id="follower" href='profile.php?userSearched=${user.username}'>${user.username}</a>
                    `;
                }

                // Agrega un event listener al artículo del usuario para redirigir al perfil
                usuarioDiv.addEventListener("click", () => {
                    window.location.href = `profile.php?userSearched=${user.username}`;
                });

                // Añade el nuevo elemento al área de resultados
                result.appendChild(usuarioDiv);
            });
    })
    .catch(error => console.error(error));
});

following.addEventListener("click", function() {
    // Remueve el atributo 'hidden' del menú de opciones
    stats.removeAttribute("hidden");
    // Cambia la clase del elemento con id "fondo" a "fondo"
    document.getElementById("fondo").className = "fondo";
    
    fetch("logic/functions/getFollowing.php?username="+username)
    .then(response => response.json())
    .then(data => {
        // Limpia el área de resultados
        result.innerHTML = "";
            // Itera sobre los datos de los usuarios obtenidos
            data.forEach(user => {
                // Crea un nuevo elemento para cada usuario
                let usuarioDiv = document.createElement("article");
                usuarioDiv.classList.add("userCard");
                usuarioDiv.setAttribute("id", "userSelected");

                // Establece la imagen del usuario
                if (user.pic === "") {
                    user.pic = "assets/logo.webp";
                } else {
                    user.pic = "data:image/jpeg;base64," + user.pic;
                }

                // Si hay un mensaje en el objeto de usuario, muestra el mensaje
                if ('message' in user) {
                    usuarioDiv.innerHTML = `
                        <p>${user.message}</p>
                    `;
                } else {
                    // Si no hay mensaje, muestra la imagen y el nombre de usuario
                    usuarioDiv.innerHTML = `
                        <img src="${user.pic}" alt="Foto de perfil">
                        <a id="follower" href='profile.php?userSearched=${user.username}'>${user.username}</a>
                    `;
                }

                // Añade el nuevo elemento al área de resultados
                result.appendChild(usuarioDiv);
            });
    })
    .catch(error => console.error(error));
});

// Agrega un evento de escucha para cuando se hace clic en el botón de salida
exitButton2.addEventListener("click", function() {
    // Establece el atributo 'hidden' del menú de opciones
    stats.setAttribute("hidden", true);
    // Cambia la clase del elemento con id "fondo" a "fondo2"
    document.getElementById("fondo").className = "fondo2";
});


// Agrega un event listener al artículo del usuario para redirigir al perfil
follower.addEventListener("click", () => {
    window.location.href = `profile.php?userSearched=${user.username}`;
});
</script>
<script>
// Obtiene la referencia al botón del menú, al menú de opciones y al botón de salida
let menuButton = document.getElementById("menu");
let options = document.getElementById("options");
let exitButton = document.getElementById("exitButton");

// Agrega un evento de escucha para cuando se hace clic en el botón del menú
menuButton.addEventListener("click", function() {
    // Remueve el atributo 'hidden' del menú de opciones
    options.removeAttribute("hidden");
    // Cambia la clase del elemento con id "fondo" a "fondo"
    document.getElementById("fondo").className = "fondo";
});

// Agrega un evento de escucha para cuando se hace clic en el botón de salida
exitButton.addEventListener("click", function() {
    // Establece el atributo 'hidden' del menú de opciones
    options.setAttribute("hidden", true);
    // Cambia la clase del elemento con id "fondo" a "fondo2"
    document.getElementById("fondo").className = "fondo2";
});
</script>
<script>
    window.addEventListener('load', function() {
        // Obtener el elemento con el ID "uploadedMessage"
        let uploadedMessage = document.getElementById('uploadedMessage');
        // Esperar 5 segundos (5000 milisegundos) y luego ocultar el mensaje
        setTimeout(function() {
            uploadedMessage.classList.add('fadeOut');
        }, 2000);
    });
</script>
<script>
//Este script sirve para que al seleccionar una foto de perfil nueva y darle a aceptar, se envie el formulario y se cambie la foto.
$(document).ready(function() {
    $('#fileInput').change(function() {
        $('#picForm').submit();
    });
});
</script>
<script>
// Obtiene todos los elementos con la clase 'likeButton' y 'likeButton2'
let likesButtons = document.querySelectorAll('.likeButton');
let likesButtons2 = document.querySelectorAll('.likeButton2');

// Agrega un evento de escucha a cada elemento con la clase 'likeButton'
likesButtons.forEach(function(elemento) {
    elemento.addEventListener('click', function() {
        // Verifica si el atributo 'name' del elemento es 'heart-outline'
        if (this.getAttribute('name') === 'heart-outline') {
            // Establece el atributo 'name' del elemento como 'heart'
            this.setAttribute('name', 'heart');
        } else {
            // Establece el atributo 'name' del elemento como 'heart-outline'
            this.setAttribute('name', 'heart-outline');
        }
    });
});

// Agrega un evento de escucha a cada elemento con la clase 'likeButton2'
likesButtons2.forEach(function(elemento) {
    elemento.addEventListener('click', function() {
        // Verifica si el atributo 'name' del elemento es 'heart-outline'
        if (this.getAttribute('name') === 'heart-outline') {
            // Establece el atributo 'name' del elemento como 'heart'
            this.setAttribute('name', 'heart');
        } else {
            // Establece el atributo 'name' del elemento como 'heart-outline'
            this.setAttribute('name', 'heart-outline');
        }
    });
});
</script>
<script>
// Obtiene la referencia al formulario
const form = document.querySelector(".typingInstaBox");

// Obtiene la referencia al campo de entrada y al botón de enviar dentro del formulario
let inputField = form.querySelector(".input-field");
let sendBtn = form.querySelector("button");

// Establece el foco en el campo de entrada
inputField.focus();

// Agrega un evento de escucha para cuando se presiona una tecla en el campo de entrada
inputField.addEventListener("keyup", function(event) {
    // Verifica si el campo de entrada no está vacío
    if (inputField.value !== "") {
        // Agrega la clase 'active' al botón de enviar
        sendBtn.classList.add("active");
    } else {
        // Remueve la clase 'active' del botón de enviar
        sendBtn.classList.remove("active");
    }

    // Verifica si la tecla presionada es "Enter"
    if (event.keyCode === 13) {
        event.preventDefault(); // Evita el comportamiento predeterminado (envío del formulario)
        // Aquí puedes realizar otras acciones si lo deseas
    }
});
</script>
<script>
// Obtiene la referencia al formulario
const form2 = document.querySelector(".typingShortBox");

// Obtiene la referencia al campo de entrada y al botón de enviar dentro del formulario
let inputField2 = form2.querySelector(".input-field");
let sendBtn2 = form2.querySelector("#sendShortComment");

// Establece el foco en el campo de entrada
inputField2.focus();

// Agrega un evento de escucha para cuando se presiona una tecla en el campo de entrada
inputField2.addEventListener("keyup", function(event) {
    // Verifica si el valor del campo de entrada, sin espacios en blanco al inicio o al final, no está vacío
    if (inputField2.value.trim() !== "") {
        // Agrega la clase 'active' al botón de enviar
        sendBtn2.classList.add("active");
    } else {
        // Remueve la clase 'active' del botón de enviar
        sendBtn2.classList.remove("active");
    }

    // Verifica si la tecla presionada es "Enter"
    if (event.keyCode === 13) {
        event.preventDefault(); // Evita el comportamiento predeterminado (envío del formulario)
        // Aquí puedes realizar otras acciones si lo deseas
    }
});
</script>
<script>
// Obtiene la referencia al botón del menú, al menú de opciones y al botón de salida
let edit = document.getElementById("edit");
let update = document.getElementById("update");
let exitButton3 = document.getElementById("exitButton3");

// Agrega un evento de escucha para cuando se hace clic en el botón del menú
edit.addEventListener("click", function() {
    // Remueve el atributo 'hidden' del menú de opciones
    update.removeAttribute("hidden");
    // Cambia la clase del elemento con id "fondo" a "fondo"
    document.getElementById("fondo").className = "fondo";
});

// Agrega un evento de escucha para cuando se hace clic en el botón de salida
exitButton3.addEventListener("click", function() {
    // Establece el atributo 'hidden' del menú de opciones
    update.setAttribute("hidden", true);
    // Cambia la clase del elemento con id "fondo" a "fondo2"
    document.getElementById("fondo").className = "fondo2";
});
</script>
<script src="scripts/commentsInstaPost.js"></script>
<script src="scripts/commentsShortPost.js"></script>
<script src="scripts/pushLikeInstaPost.js"></script>
<script src="scripts/pushLikeShortPost.js"></script>
<script src="scripts/updateValidation.js"></script>
<script src="scripts/fillRegions.js"></script>
<script src="scripts/catchFlag.js"></script>
<script src="scripts/navbar.js"></script>
<script src="../reload.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>