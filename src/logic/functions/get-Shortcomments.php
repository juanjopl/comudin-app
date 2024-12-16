<?php
// Incluye el archivo de funciones generales y comienza la sesión
require_once('../gnl_functions.php');
session_start();

// Verifica si hay un usuario en sesión
if (isset($_SESSION['user'])) {
    // Obtiene el ID del post enviado por el formulario
    $idPost = $_POST["postId"];
    $output = "";

    // Establece conexión a la base de datos
    $con = get_connection();
    
    // Prepara y ejecuta la consulta SQL para obtener los comentarios cortos del post
    $sql = "SELECT s.comment, s.commentator, u.foto 
        FROM shortcomments as s 
        JOIN users as u ON s.commentator = u.username
        WHERE s.post = :idPost";
    $statement = $con->prepare($sql);
    $statement->bindParam(":idPost", $idPost, PDO::PARAM_INT);
    $statement->execute();

    // Verifica si se obtuvieron resultados de la consulta
    if ($statement->rowCount() > 0) {
        // Recorre los resultados y muestra los comentarios
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="comment">
                <img src="<?php
                // Verifica si hay una foto de perfil disponible, si no, muestra un logotipo predeterminado
                if ($row["foto"] == null) {
                    echo 'assets/logo.webp';
                } else {
                    echo 'data:image/jpeg;base64, ' . base64_encode($row["foto"]);
                }
                ?>">
                <div class="rowData">
                    <a href="profile.php?userSearched=<?php echo $row["commentator"] ?>"><?php echo $row["commentator"] ?></a>
                    <p><?php echo $row["comment"] ?></p>
                </div>
            </div>
            <?php
        }
    } else {
        // Si no hay comentarios, muestra un mensaje indicando que no hay comentarios aún
        ?>
        <div class="text" style="color: whitesmoke; text-align: center">No hay comentarios aún</div>
        <?php
    }
} else {
    // Si no hay usuario en sesión, redirige a la página de inicio de sesión
    header("location: ../index.php");
}
?>
