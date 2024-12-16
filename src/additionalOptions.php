<?php
    require_once("logic/entity/user.php");
    session_start();
    $user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="styles/CSS/main.css">
</head>
<body class="index">
    <main class="mainIndex">
        <form action="logic/functions/addAdditionalOptions.php" method="post" enctype="multipart/form-data" class="additionalOptions">
            <div class="picBox">
                    <input type="file" name="image" accept="image/png, image/webp" style="display:none;" id="fileInput">
                    <span>Selecciona tu foto de perfil</span>
                    <label for="fileInput">
                    <img src="<?php
                        if($user->foto == null){
                            echo 'assets/logo.webp';
                        }else {
                            echo 'data:image/jpeg;base64, '. base64_encode($user->foto);
                        }
                    ?>" id="previewImage">
                    </label>
            </div>
            <div class="nameBox">
                <span>Escribe tu nombre:</span>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="regionBox">
                <span>Selecciona tu país:</span>
                <select name="region" id="regionSelect" required>
                </select>
            </div>
            <div class="dateBox">
                <span>Selecciona tu fecha de nacimiento:</span>
                <input type="date" name="date" id="date" required>
                <span id="valAge" style="color: red;"></span>
            </div>
            <button id="sendOthers">Ir al Inicio</button>
        </form>
    </main>
</body>
<script>
    let valAge = document.getElementById("valAge");
    let date = document.getElementById("date");
    let sendOthers = document.getElementById("sendOthers");

    date.addEventListener('focusout', function() {
        const birthdateValue = date.value;
        if (birthdateValue) {
            const birthdate = new Date(birthdateValue);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const monthDifference = today.getMonth() - birthdate.getMonth();
            const dayDifference = today.getDate() - birthdate.getDate();

            // Ajuste para comprobar si la persona ya ha cumplido años este año
            if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
                age--;
            }

            if (age < 18) {
                valAge.textContent = 'Debes ser mauyor de edad';
                sendOthers.setAttribute("disabled", true);
            } else {
                valAge.textContent = '';
                sendOthers.removeAttribute("disabled");
            }
        } else {
            valAge.textContent = '';
        }
    });
</script>
<script>
    document.getElementById("fileInput").addEventListener("change", function(event) {
    const file = event.target.files[0];
    const previewImage = document.getElementById("previewImage");

    if (file) {
        const reader = new FileReader(); 
        reader.onload = function() {
            previewImage.src = reader.result;
            previewImage.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        previewImage.src = "assets/logo.webp";
        previewImage.style.display = "none";
    }
});
</script>
<script src="scripts/fillRegions.js"></script>
<script src="scripts/registrationValidation.js"></script>
<script src="../reload.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>