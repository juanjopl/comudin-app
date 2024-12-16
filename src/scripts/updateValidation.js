// Obtiene referencias a los elementos del DOM
let passUpd = document.getElementById("pass");
let confirmpassUpd = document.getElementById("confirmpass");
let confirmpassText = document.getElementById("confirmpassText");
let usernameUpd = document.getElementById("user");
let userUpdText = document.getElementById("userUpdText");
let actualUsername = document.getElementById("username").textContent.trim();
let updateButton = document.getElementById("updateButton");

// Valida la confirmación de la contraseña en tiempo real
confirmpassUpd.addEventListener("input", function() {
    // Comprueba si las contraseñas no coinciden
    if(confirmpassUpd.value.trim() != passUpd.value.trim()) {
        confirmpassText.textContent = "Las contraseñas no coinciden";
        confirmpassText.style.color = "#ce0000";
        updateButton.setAttribute("disabled", true);
    } else {
        confirmpassText.textContent = "Las contraseñas coinciden";
        confirmpassText.style.color = "#28B463";
        updateButton.removeAttribute("disabled");
    }

    // Limpia el mensaje si el campo de confirmación está vacío
    if(confirmpassUpd.value.trim() === "") {
        confirmpassText.textContent = "";
    }
});

// Valida la contraseña en tiempo real
passUpd.addEventListener("input", function() {
    if (passUpd.value.trim() !== "") {
        confirmpassUpd.setAttribute("required", true);
        // Comprueba si las contraseñas coinciden
        if(confirmpassUpd.value.trim() === passUpd.value.trim()) {
            confirmpassText.textContent = "Las contraseñas coinciden";
            confirmpassText.style.color = "#28B463";
            updateButton.removeAttribute("disabled");
        } else {
            confirmpassText.textContent = "Las contraseñas no coinciden";
            confirmpassText.style.color = "#ce0000";
            updateButton.setAttribute("disabled", true);
        }
    } else {
        confirmpassText.textContent = "";
        updateButton.removeAttribute("disabled");
    }
});

// Valida el nombre de usuario en tiempo real
usernameUpd.addEventListener("input", function() {
    // Comprueba si el nombre de usuario es suficientemente largo y diferente del nombre de usuario actual
    if (usernameUpd.value.length > 5 && usernameUpd.value.trim() !== actualUsername) {
        fetch("logic/functions/checkUsername.php?user=" + encodeURIComponent(usernameUpd.value.trim()))
        .then(response => response.json())
        .then(data => {
            let message = data[0].message;
            if (message !== "") {
                updateButton.setAttribute("disabled", true);
                userUpdText.textContent = message;
                userUpdText.style.color = "#ce0000";
            } else {
                userUpdText.textContent = "";
                updateButton.removeAttribute("disabled");
            }
        })
        .catch(error => console.error(error));
    } else {
        userUpdText.textContent = "El nombre de usuario debe tener al menos 5 caracteres";
        updateButton.setAttribute("disabled", true);
    }

    // Permite que el botón de actualización se habilite si el nombre de usuario no ha cambiado
    if(usernameUpd.value.trim() === actualUsername) {
        userUpdText.textContent = "";
        updateButton.removeAttribute("disabled");
    }
});

// Mediante el uso del evento click, comprueba que el campo de contraseña es diferente de "vacio", si es asi, pide obligatoriamente el campo de confirmar contraseña
updateButton.addEventListener("click", function() {
    if (passUpd.value.trim() !== "") {
        confirmpassUpd.setAttribute("required", true);
    } else {
        confirmpassUpd.removeAttribute("required");
    }
});
