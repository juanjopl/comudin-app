// Selecciona todos los botones con la clase .commentShortButton
let botonesComentario2 = document.querySelectorAll(".commentShortButton");

botonesComentario2.forEach(boton => {
    // Agrega un event listener a cada botón para manejar el evento 'click'
    boton.addEventListener("click", function() {
        // Obtén el id del div .commentsShortPost asociado al botón clicado
        const commentsShortPostId = boton.getAttribute("value");
        // Busca el div .commentsShortPost correspondiente al id obtenido
        const divCommentsShortPost = document.getElementById("commentsShortPost-" + commentsShortPostId);
        // Verifica si divCommentsShortPost no es null antes de acceder a sus propiedades
        if (divCommentsShortPost !== null) {
            // Si el div está oculto, muéstralo; de lo contrario, déjalo como está
            if (divCommentsShortPost.hasAttribute("hidden")) {
                divCommentsShortPost.removeAttribute("hidden");
                document.getElementById("fondo").className = "fondo";
            }
            // Obtén el elemento de la caja de comentarios
            let commentsBox = divCommentsShortPost.querySelector(".commentsShortBox");
            // Llama a la función para obtener los comentarios
            getShortComments(commentsBox, commentsShortPostId);
        } else {
            console.error("El elemento .commentsShortPost no fue encontrado.");
        }
    });
});

// Selecciona todos los botones de salida con el id #exitCommentButton
const botonesExitButton2 = document.querySelectorAll("#exitCommentButton");

// Itera sobre cada botón de salida
botonesExitButton2.forEach(boton => {
    // Agrega un event listener a cada botón para manejar el evento 'click'
    boton.addEventListener("click", function() {
        // Encuentra el div .commentsShortPost asociado al botón clicado
        const divCommentsShortPost = boton.closest('.commentsShortPost');
        let commentsBox = divCommentsShortPost.querySelector(".commentsShortBox");
        // Si el div está visible, ocúltalo y cambia la clase de fondo
        if (!divCommentsShortPost.hasAttribute("hidden")) {
            divCommentsShortPost.setAttribute("hidden", true);
            document.getElementById("fondo").className = "fondo2";
            commentsBox.innerHTML = "";
        }
    });
});

// Selecciona todos los botones de envío de comentarios con el id #sendShortComment
const sendButtons2 = document.querySelectorAll("#sendShortComment");

sendButtons2.forEach(boton => {
    // Agrega un event listener a cada botón para manejar el evento 'click'
    boton.addEventListener("click", function() {
        // Encuentra el div .commentsShortPost asociado al botón clicado
        const divCommentsShortPost = boton.closest('.commentsShortPost');
        let commentsBox = divCommentsShortPost.querySelector(".commentsShortBox");
        let form = divCommentsShortPost.querySelector(".typingShortBox");
        let inputField = form.querySelector(".input-field");
        let idPost = boton.getAttribute("value");

        // Previene el comportamiento por defecto del formulario
        form.onsubmit = (e) => {
            e.preventDefault();
        }

        // Configura y envía una solicitud XMLHttpRequest para insertar un comentario
        let request = new XMLHttpRequest();
        request.open("POST", "logic/functions/insert-Shortcomments.php", true);
        request.onload = () => {
            // Verifica si la solicitud se completó correctamente
            if (request.readyState === XMLHttpRequest.DONE) {
                if (request.status === 200) {
                    // Limpia el campo de input y actualiza los comentarios
                    inputField.value = "";
                    getShortComments(commentsBox, idPost);
                    scrollToBottom(commentsBox);
                }
            }
        }
        // Envía los datos del formulario
        let formData = new FormData(form);
        request.send(formData);
    });
});

// Función para hacer scroll hasta el final de la caja de comentarios
function scrollToBottom(commentBox) {
    commentBox.scrollTop = commentBox.scrollHeight;
}

// Función para obtener los comentarios mediante una solicitud XMLHttpRequest
function getShortComments(commentsBox, commentsShortPostId) {
    let request = new XMLHttpRequest();
    request.open("POST", "logic/functions/get-Shortcomments.php", true);
    request.onload = () => {
        // Verifica si la solicitud se completó correctamente
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                // Actualiza el contenido de la caja de comentarios
                let data = request.response;
                commentsBox.innerHTML = data;
                // Si la caja de comentarios no está activa, hace scroll hasta el final
                if (!commentsBox.classList.contains("active")) {
                    scrollToBottom(commentsBox);
                }
            }
        }
    }
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Envía la solicitud con el id del post
    request.send("postId=" + commentsShortPostId);
}
