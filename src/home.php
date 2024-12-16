<?php
    require_once("logic/entity/instapost.php");
    require_once("logic/entity/shortpost.php");
    require_once("logic/entity/user.php");
    require_once("chargeInstaPosts.php");
    require_once("chargeShortPosts.php");
    session_start();
    if (!isset($_SESSION["user"])) {
        header("Location: index.php");
        session_destroy();
    }
    $user = $_SESSION["user"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="styles/CSS/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="allbody">
    <nav class="navbar1">
        <!-- <ul>
        <button id="buttonPost">Subir</button>
            <li>
                <a href="users.php">
                    <span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                </a>
            </li>
        </ul> -->
        <ul>
            <li>
                <a>
                    <button id="buttonPost">Subir</button>
                </a>
            </li>
            <li>
                <a href="users.php" style="margin-right: 15px;">
                    <span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                </a>
            </li>
        </ul>
    </nav>
    <main class="content">
    <div class="fondo2" id="fondo"></div>
    <div class="post" id="post" hidden>
    <span id="exitButton">X</span> 
        <div class="selectPost">
            <span id="selectInstaPost" class="active">InstaPost</span>
            <span id="selectShortPost" class="disabled">ShortPost</span>
        </div>
        <div class="postForm">
        <form action="logic/functions/postInstaPost.php" method="POST" enctype="multipart/form-data" class="post1" id="post1">
            <div class="picsPost" id="picsPost">
                <input type="file" name="image" accept="image/webp" style="display:none;" id="fileInput" required>
                <label for="fileInput">
                    <span class="icon"><ion-icon name="add-outline"></ion-icon></span>
                </label>
            </div>
            <span class="deleteInstaPhoto" id="deleteInstaPhoto" hidden><ion-icon name="trash-outline"></ion-icon></span>
            <textarea name="descripPost" id="txtDescrip" cols="28" rows="5" placeholder="Escribe una descripcion..."></textarea>
            <input type="text" name="ubiPost" id="ubiPost" placeholder="Añade ubicación"></span>
            <button>Publicar</button>
        </form>
        <form action="logic/functions/postShortPost.php" method="POST" enctype="multipart/form-data" class="post2" id="post2" hidden>
            <textarea name="messageShortPost" id="messageShortPost" cols="30" rows="10" placeholder="¿En que piensas?..." required></textarea>
            <button>Publicar</button>
        </form>
        </div>
    </div>
    <div class="selectHomePost">
        <?php
            if ((isset($_GET["instaposts"]) || !isset($_GET["instaposts"])) && !isset($_GET["shortposts"])) {
                ?>
                    <a href="home.php?instaposts" id="selectHomeInstaPost" class="active">InstaPost</a>
                    <a href="home.php?shortposts" id="selectHomeShortPost" class="disabled">ShortPost</a>
                <?php
            }else {
                ?>
                    <a href="home.php?instaposts" id="selectHomeInstaPost" class="disabled">InstaPost</a>
                    <a href="home.php?shortposts" id="selectHomeShortPost" class="active">ShortPost</a>
                <?php
            }
        ?>
        
    </div>
    <section>
        <?php
        if ((isset($_GET["instaposts"]) || !isset($_GET["instaposts"])) && !isset($_GET["shortposts"])) {
            $instaposts = InstaPost::getHomeInstaPost($user);
            if ($instaposts != null) {
                chargeInstaPosts($instaposts);
            }else {
                ?>
                    <span style="display: block; width: 100%; margin-top: 10px; text-align: center">Tus seguidores no han subido publicaciones</span>
                <?php
            }
        }else {
            $shortposts = ShortPost::getHomeShortPost($user);
            if ($shortposts != null) {
                chargeShortPosts($shortposts);
            }else {
                ?>
                    <span style="display: block; width: 100%; margin-top: 10px; text-align: center">Tus seguidores no han subido publicaciones</span>
                <?php
            }
        }
        ?>
    </section>
    </main>
    <nav class="navbar2">
        <ul>
            <li class="active point">
                <a href="home.php">
                    <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                </a>
            </li>
            <li>
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
<script>
    // Obtiene el botón de publicación, el div de publicación y el botón de salida por sus identificadores
let buttonPost = document.getElementById("buttonPost");
let divPost = document.getElementById("post");
let exitButton = document.getElementById("exitButton");

// Agrega un evento de clic al botón de publicación
buttonPost.addEventListener("click", function() {
    // Elimina el atributo "hidden" del div de publicación para mostrarlo
    divPost.removeAttribute("hidden");
    // Cambia la clase del fondo para mostrar el fondo oscuro
    document.getElementById("fondo").className = "fondo";
});

// Agrega un evento de clic al botón de salida
exitButton.addEventListener("click", function() {
    // Establece el atributo "hidden" en true para ocultar el div de publicación
    divPost.setAttribute("hidden", true);
    // Cambia la clase del fondo para ocultar el fondo oscuro
    document.getElementById("fondo").className = "fondo2";
});

</script>
<script>
// Obtiene referencias a elementos del DOM
let picsPost = document.getElementById("picsPost"); // Contenedor de la vista previa de la imagen
let previewImage = document.createElement("img"); // Elemento de imagen para la vista previa
let deleteInstaPhoto = document.getElementById("deleteInstaPhoto"); // Botón para eliminar la foto

// Agrega un evento de cambio al input de tipo archivo
document.getElementById("fileInput").addEventListener("change", function(event) {
    const file = event.target.files[0]; // Obtiene el archivo seleccionado

    // Verifica si se ha seleccionado un archivo
    if (file) {
        const reader = new FileReader(); // Crea un objeto FileReader para leer el archivo
        reader.onload = function() {
            // Configura la vista previa de la imagen
            previewImage.src = reader.result; // Establece la URL de la imagen
            previewImage.setAttribute("name", "imgPost"); // Añade un atributo de nombre a la imagen
            previewImage.style.display = "block"; // Muestra la imagen
            picsPost.appendChild(previewImage); // Agrega la imagen al contenedor de la vista previa
            deleteInstaPhoto.removeAttribute("hidden"); // Muestra el botón de eliminar foto
        };
        reader.readAsDataURL(file); // Lee el archivo como una URL
    } else {
        // Si no se selecciona ningún archivo, oculta el botón de eliminar foto y elimina la vista previa
        deleteInstaPhoto.setAttribute("hidden", true); // Oculta el botón de eliminar foto
        previewImage.remove(); // Elimina la vista previa de la imagen
    }
});

// Agrega un evento de clic al botón de salida
exitButton.addEventListener("click", function() {
    // Elimina la vista previa de la imagen y restablece el valor del input de archivo
    previewImage.remove(); // Elimina la vista previa de la imagen
    fileInput.value = ''; // Restablece el valor del input de archivo
    deleteInstaPhoto.setAttribute("hidden", true); // Oculta el botón de eliminar foto

    // Cambia las clases de los elementos para mostrar el post de Instagram como activo y el post corto como desactivado
    selectInstaPost.classList.remove("disabled"); // Quita la clase "disabled" del post de Instagram
    selectInstaPost.classList.add("active"); // Agrega la clase "active" al post de Instagram
    selectShortPost.classList.remove("active"); // Quita la clase "active" del post corto
    selectShortPost.classList.add("disabled"); // Agrega la clase "disabled" al post corto
});

// Agrega un evento de clic al botón de eliminar foto de Instagram
deleteInstaPhoto.addEventListener("click", function() {
    // Elimina la vista previa de la imagen y restablece el valor del input de archivo
    previewImage.remove(); // Elimina la vista previa de la imagen
    fileInput.value = ''; // Restablece el valor del input de archivo
    deleteInstaPhoto.setAttribute("hidden", true); // Oculta el botón de eliminar foto
});
</script>
<script>
// Selecciona todos los elementos con la clase "likeButton"
let likesButtons = document.querySelectorAll('.likeButton');

// Itera sobre cada elemento con la clase "likeButton"
likesButtons.forEach(function(elemento) {
    // Agrega un evento de clic a cada elemento
    elemento.addEventListener('click', function() {
        // Verifica el estado actual del botón de "me gusta"
        if (this.getAttribute('name') === 'heart-outline') {
            // Si el botón está en estado "no me gusta", cambia a "me gusta"
            this.setAttribute('name', 'heart');
        } else {
            // Si el botón está en estado "me gusta", cambia a "no me gusta"
            this.setAttribute('name', 'heart-outline');
        }
    });
});

// Selecciona todos los elementos con la clase "likeButton2"
let likesButtons2 = document.querySelectorAll('.likeButton2');

// Itera sobre cada elemento con la clase "likeButton2"
likesButtons2.forEach(function(elemento) {
    // Agrega un evento de clic a cada elemento
    elemento.addEventListener('click', function() {
        // Verifica el estado actual del botón de "me gusta"
        if (this.getAttribute('name') === 'heart-outline') {
            // Si el botón está en estado "no me gusta", cambia a "me gusta"
            this.setAttribute('name', 'heart');
        } else {
            // Si el botón está en estado "me gusta", cambia a "no me gusta"
            this.setAttribute('name', 'heart-outline');
        }
    });
});

</script>
<script>
// Obtiene las referencias a los elementos del DOM
let selectShortPost = document.getElementById("selectShortPost");
let selectInstaPost = document.getElementById("selectInstaPost");

// Agrega un evento de clic al elemento selectShortPost
selectShortPost.addEventListener("click", function() {
    // Cambia las clases para activar el elemento selectShortPost y desactivar selectInstaPost
    selectShortPost.classList.remove("disabled");
    selectShortPost.classList.add("active");
    selectInstaPost.classList.remove("active");
    selectInstaPost.classList.add("disabled");

    // Oculta el elemento post1 y muestra el elemento post2
    post1.setAttribute("hidden", true);
    post2.removeAttribute("hidden");
});

// Agrega un evento de clic al elemento selectInstaPost
selectInstaPost.addEventListener("click", function() {
    // Cambia las clases para activar el elemento selectInstaPost y desactivar selectShortPost
    selectInstaPost.classList.remove("disabled");
    selectInstaPost.classList.add("active");
    selectShortPost.classList.remove("active");
    selectShortPost.classList.add("disabled");

    // Oculta el elemento post2 y muestra el elemento post1
    post2.setAttribute("hidden", true);
    post1.removeAttribute("hidden");
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
    // Función para cambiar el height del div usando calc()
    window.onload = function() {
        const instaPosts = document.querySelector('.instaPosts');
        if (instaPosts) {
            instaPosts.style.height = 'calc(90vh - 70px)'; // Restar la altura del footer
        }
    };
</script>
<script src="scripts/commentsInstaPost.js"></script>
<script src="scripts/commentsShortPost.js"></script>
<script src="scripts/pushLikeInstaPost.js"></script>
<script src="scripts/pushLikeShortPost.js"></script>
<script src="scripts/navbar.js"></script>
<script src="../reload.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>