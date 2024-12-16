// Obtén la referencia al input de búsqueda, botón de búsqueda y el área de resultados
let searchInput = document.getElementById("searchInput");
let searchButton = document.getElementById("searchButton");
let result = document.getElementById("results");

// Agrega un event listener al input de búsqueda para manejar el evento 'input'
searchInput.addEventListener("input", () => {
    // Obtén el valor actual del input de búsqueda
    let usuario = searchInput.value;
    
    // Si el input no está vacío
    if (usuario.trim() !== "") {
        // Realiza una solicitud fetch para buscar usuarios
        fetch("logic/functions/catchUsers.php?user=" + usuario)
        .then(response => {
            // Verifica si la respuesta del servidor es exitosa
            if (!response.ok) {
                throw new Error('Hubo un problema al realizar la solicitud: ' + response.status);
            }
            // Convierte la respuesta a JSON
            return response.json();
        })
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
                        <a href='profile.php?userSearched=${user.username}'>${user.username}</a>
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
        .catch(error => console.error(error))
    } else {
        // Si el input está vacío, limpia el área de resultados
        result.innerHTML = "";
    }
});
