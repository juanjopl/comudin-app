//En este script, con la ayuda del elemento #region y llamando a 2 apis de banderas de paises, se consigue sacar el "emoticono" del usuario, para asi mostrarlo en su perfil.
document.addEventListener('DOMContentLoaded', function() {
let regionText = document.getElementById("region").textContent.trim();
fetch('https://flagcdn.com/en/codes.json')
  .then(response => response.json())
  .then(data => {
    let countryCode;
    for (let code in data) {
      if (data[code] === regionText) {
        countryCode = code;
        break;
      }
    }
    let urlImg = `https://flagcdn.com/16x12/${countryCode}.webp`;
    imgRegion.setAttribute("src", urlImg);
  })
  .catch(error => console.error(error));
});