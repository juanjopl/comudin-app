// Selecciona el formulario y varios elementos dentro de él
const form = document.querySelector(".typing-area");
const incomingId = form.querySelector(".incomingId").value;
const inputField = form.querySelector(".input-field");
const sendBtn = form.querySelector("button");
const chatBox = document.querySelector(".chat-box");

// Variable para realizar un seguimiento del desplazamiento manual del usuario
let userScrolled = false;

// Previene el envío por defecto del formulario
form.onsubmit = (e) => {
    e.preventDefault();
    // Crea y configura una solicitud XMLHttpRequest para enviar el mensaje
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "logic/functions/insert-chat.php", true);
    xhr.onload = () => {
        // Verifica si la solicitud se completó correctamente
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Limpia el campo de entrada
                inputField.value = "";
                // Actualiza el contenido del chat y desplaza la vista al final
                updateChat();
                // Desplaza la vista al final del chat
                scrollToBottom();
            }
        }
    }
    // Envía los datos del formulario
    let formData = new FormData(form);
    xhr.send(formData);
}

// Mantiene el foco en el campo de entrada
inputField.focus();

// Agrega un event listener para activar/desactivar el botón de envío basado en el contenido del campo de entrada
inputField.addEventListener("input", function() {
    if (inputField.value != "") {
        sendBtn.classList.add("active");
    } else {
        sendBtn.classList.remove("active");
    }
});

// Función para desplazar la vista al final de la caja de chat
function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Agrega un event listener para detectar cuando el usuario desplaza manualmente la caja de chat
chatBox.addEventListener("scroll", function() {
    // Si el usuario ha desplazado hacia arriba, establece userScrolled en true
    if (chatBox.scrollTop < (chatBox.scrollHeight - chatBox.clientHeight)) {
        userScrolled = true;
    } else {
        userScrolled = false;
    }
});

// Establece un intervalo para actualizar el chat cada 500ms
setInterval(updateChat, 500);

// Función para actualizar el chat
function updateChat() {
    // Crea y configura una solicitud XMLHttpRequest para obtener los mensajes del chat
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "logic/functions/get-chat.php", true);
    xhr.onload = () => {
        // Verifica si la solicitud se completó correctamente
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Guarda la posición actual del scroll antes de actualizar el chat
                const currentScroll = chatBox.scrollHeight - chatBox.scrollTop;
                // Actualiza el contenido del chat
                let data = xhr.response;
                chatBox.innerHTML = data;
                // Si el usuario no ha desplazado manualmente hacia arriba, desplaza la vista al final del chat
                if (!userScrolled) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                } else {
                    // Si el usuario ha desplazado manualmente hacia arriba, mantén la posición de desplazamiento relativa al último mensaje
                    chatBox.scrollTop = chatBox.scrollHeight - currentScroll;
                }
            }
        }
    }
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Envía la solicitud con el id del destinatario
    xhr.send("incomingId=" + incomingId);
}
