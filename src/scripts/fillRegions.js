//Este script sirve para crear un elemento select en el que todas sus option son paises sacados de la API flagcdn.
let regionSelect = document.getElementById("regionSelect");
fetch("https://flagcdn.com/en/codes.json")
.then(response => response.json())
.then(data => {
    let firstselect = document.createElement("option");
    firstselect.textContent = "...";
    firstselect.setAttribute("selected", true);
    firstselect.setAttribute("disabled", true);
    regionSelect.appendChild(firstselect);
    Object.keys(data).forEach(region => {
        let option = document.createElement("option");
        option.textContent = data[region];
        regionSelect.appendChild(option);
    });
})
.catch(error => console.error(error));