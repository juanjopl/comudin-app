// Cuando el usuario escribe en el campo de usuario
document.getElementById("user").addEventListener("input", () => {
    let user = document.getElementById("user").value;
    let input = document.getElementById("user");
    let text = document.getElementById("userText");
    let patronUser = /^[a-zA-Z0-9]{5,}$/;

    // Si el campo está vacío, limpia la validación
    if (user === "") {
        clearValidation(input, text);
    // Si el nombre de usuario cumple con el patrón, marca como válido
    } else if (user.match(patronUser)) {
        setValid(input, "Tu nombre de usuario es válido", "#28B463", text);
    // Si no cumple con el patrón, marca como inválido
    } else {
        setInvalid(input, "Debe contener al menos 5 caracteres", "#ce0000", text);
    }
    checkValidity();
});
// Cuando el usuario escribe en el campo de correo electrónico
document.getElementById("email").addEventListener("input", () => {
    let email = document.getElementById("email");
    let text = document.getElementById("emailText");
    let patronEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    // Si el campo está vacío, limpia la validación
    if (email.value === "") {
        clearValidation(email, text);
    // Si el correo cumple con el patrón, marca como válido
    } else if (email.value.match(patronEmail)) {
        setValid(email, "Tu email es válido", "#28B463", text);
    // Si no cumple con el patrón, marca como inválido
    } else {
        setInvalid(email, "Debe ser un correo válido (@dominio)", "#ce0000", text);
    }
    checkValidity();
});

// Cuando el usuario escribe en el campo de contraseña
document.getElementById("pass").addEventListener("input", () => {
    let pass = document.getElementById("pass");
    let text = document.getElementById("passText");
    let patronPassword = /^[a-zA-Z0-9]{5,}$/;

    // Si el campo está vacío, limpia la validación
    if (pass.value === "") {
        clearValidation(pass, text);
    // Si la contraseña cumple con el patrón, marca como válida
    } else if (pass.value.match(patronPassword)) {
        setValid(pass, "Tu contraseña es válida", "#28B463", text);
    // Si no cumple con el patrón, marca como inválida
    } else {
        setInvalid(pass, "Debe contener al menos 5 caracteres", "#ce0000", text);
    }
    checkValidity();
});

// Función para marcar un campo como válido
function setValid(input, message, color, text) {
    input.classList.remove("invalid");
    input.classList.add("valid");
    text.innerHTML = message;
    text.style.color = color;
}

// Función para marcar un campo como inválido
function setInvalid(input, message, color, text) {
    input.classList.remove("valid");
    input.classList.add("invalid");
    text.innerHTML = message;
    text.style.color = color;
}

// Función para limpiar la validación de un campo
function clearValidation(input, text) {
    input.classList.remove("valid");
    input.classList.remove("invalid");
    text.innerHTML = "";
    text.style.color = "#fff";
}

// Función para verificar la validez de todos los campos
function checkValidity() {
    let boton = document.getElementById("submitButton");
    let userInput = document.getElementById("user");
    let emailInput = document.getElementById("email");
    let passInput = document.getElementById("pass");
    // Habilita el botón de envío si todos los campos son válidos
    if (userInput.classList.contains("valid") && emailInput.classList.contains("valid") && passInput.classList.contains("valid")) {
        document.getElementById("buttonText").innerHTML = "";
        boton.disabled = false;
    // Deshabilita el botón de envío si falta algún campo por completar o es inválido
    } else {
        document.getElementById("buttonText").innerHTML = "Rellena todos los campos";
        boton.disabled = true;
    }
}
