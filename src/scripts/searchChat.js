document.getElementById('searchInput').addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    const listUsers = document.querySelectorAll('.listUser');

    listUsers.forEach(function(user) {
        const username = user.querySelector('#usernameChat').textContent.toLowerCase();
        if (username.startsWith(filter)) {
            user.classList.remove('hidden'); // Mostrar el div si coincide
        } else {
            user.classList.add('hidden'); // Ocultar el div si no coincide
        }
    });
});