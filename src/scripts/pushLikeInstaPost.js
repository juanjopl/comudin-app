// Selecciona todos los elementos que tienen la clase 'likeButton'
let likesButton = document.querySelectorAll(".likeButton");

// Itera sobre cada uno de los botones de 'me gusta'
likesButton.forEach(element => {
    // Obtiene el atributo 'value' del botón, que contiene el ID del post
    let idPost = element.getAttribute("value");

    // Añade un event listener al botón para manejar el evento 'click'
    element.addEventListener("click", function() {
        // Obtiene el atributo 'name' del botón, que indica el estado del 'me gusta' (por ejemplo, 'heart' o no)
        let likeStatus = element.getAttribute("name");

        // Realiza una solicitud fetch para actualizar el estado del 'me gusta' en el servidor
        fetch(`logic/functions/pushLikeInstaPost.php?idPost=${idPost}&status=${likeStatus}`)
        .then(response => {
            // Verifica si la respuesta del servidor es exitosa
            if (!response.ok) {
                // Si no es exitosa, lanza un error con el estado de la respuesta
                throw new Error('Hubo un problema al realizar la solicitud: ' + response.status);
            }

            // Selecciona el contador de 'me gusta' correspondiente al post
            let likeCounter = document.querySelector(`#instaPost-${idPost} .likesInstaPost`);
            if (likeCounter) {
                // Obtiene el número actual de 'me gusta' y lo convierte a un número entero
                let currentLikes = parseInt(likeCounter.textContent);

                // Si el estado es 'heart', incrementa el contador, de lo contrario, decrementa el contador
                if (likeStatus === "heart") {
                    likeCounter.textContent = currentLikes + 1;
                } else {
                    likeCounter.textContent = currentLikes - 1;
                }
            }

            // Devuelve la respuesta en formato JSON para el siguiente bloque then
            return response.json();
        })
        .then(() => {
            // Este bloque then está vacío pero puede ser usado para manejar cualquier lógica adicional después de la respuesta JSON
        })
        .catch(error => {
            // Muestra cualquier error ocurrido durante la solicitud fetch
            console.error(error);
        });
    });
});
