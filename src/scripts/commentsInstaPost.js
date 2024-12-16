// Selecciona todos los botones con la clase .commentInstaButton
let botonesComentario = document.querySelectorAll(".commentInstaButton");

botonesComentario.forEach(boton => {
    // Agrega un event listener a cada botón para manejar el evento 'click'
    boton.addEventListener("click", function() {
        // Obtén el id del div .commentsInstaPost asociado al botón clicado
        const commentsInstaPostId = boton.getAttribute("value");
        // Busca el div .commentsInstaPost correspondiente al id obtenido
        const divCommentsInstaPost = document.getElementById("commentsInstaPost-" + commentsInstaPostId);
        // Verifica si divCommentsInstaPost no es null antes de acceder a sus propiedades
        if (divCommentsInstaPost !== null) {
            // Si el div está oculto, muéstralo; de lo contrario, déjalo como está
            if (divCommentsInstaPost.hasAttribute("hidden")) {
                divCommentsInstaPost.removeAttribute("hidden");
                document.getElementById("fondo").className = "fondo";
            }
            // Obtén el elemento de la caja de comentarios
            let commentsBox = divCommentsInstaPost.querySelector(".commentsInstaBox");
            // Llama a la función para obtener los comentarios
            getInstaComments(commentsBox, commentsInstaPostId);
        } else {
            console.error("El elemento .commentsInstaPost no fue encontrado.");
        }
    });
});

// Selecciona todos los botones de salida con el id #exitCommentButton
const botonesExitButton = document.querySelectorAll("#exitCommentButton");

// Itera sobre cada botón de salida
botonesExitButton.forEach(boton => {
    // Agrega un event listener a cada botón para manejar el evento 'click'
    boton.addEventListener("click", function() {
        // Encuentra el div .commentsInstaPost asociado al botón clicado
        const divCommentsInstaPost = boton.closest('.commentsInstaPost');
        let commentsBox = divCommentsInstaPost.querySelector(".commentsInstaBox");
        // Si el div está visible, ocúltalo y cambia la clase de fondo
        if (!divCommentsInstaPost.hasAttribute("hidden")) {
            divCommentsInstaPost.setAttribute("hidden", true);
            document.getElementById("fondo").className = "fondo2";
            commentsBox.innerHTML = "";
        }
    });
});

// Selecciona todos los botones de envío de comentarios con el id #sendInstaComment
const sendButtons = document.querySelectorAll("#sendInstaComment");

sendButtons.forEach(boton => {
    // Agrega un event listener a cada botón para manejar el evento 'click'
    boton.addEventListener("click", function() {
        // Encuentra el div .commentsInstaPost asociado al botón clicado
        const divCommentsInstaPost = boton.closest('.commentsInstaPost');
        let commentsBox = divCommentsInstaPost.querySelector(".commentsInstaBox");
        let form = divCommentsInstaPost.querySelector(".typingInstaBox");
        let inputField = form.querySelector(".input-field");
        let idPost = boton.getAttribute("value");

        // Previene el comportamiento por defecto del formulario
        form.onsubmit = (e) => {
            e.preventDefault();
        }

        // Configura y envía una solicitud XMLHttpRequest para insertar un comentario
        let request = new XMLHttpRequest();
        request.open("POST", "logic/functions/insert-Instacomments.php", true);
        request.onload = () => {
            // Verifica si la solicitud se completó correctamente
            if (request.readyState === XMLHttpRequest.DONE) {
                if (request.status === 200) {
                    // Limpia el campo de entrada y actualiza los comentarios
                    inputField.value = "";
                    getInstaComments(commentsBox, idPost);
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
function getInstaComments(commentsBox, commentsInstaPostId) {
    let request = new XMLHttpRequest();
    request.open("POST", "logic/functions/get-Instacomments.php", true);
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
    request.send("postId=" + commentsInstaPostId);
}
