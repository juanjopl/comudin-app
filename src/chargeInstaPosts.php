<?php
    require_once("logic/entity/instapost.php");
    require_once("logic/entity/user.php");
    require_once("../src/logic/gnl_functions.php");
    function chargeInstaPosts($instaposts) {
        $user = $_SESSION["user"];
        ?>
        <div class="instaPosts">
        <?php
        foreach ($instaposts as $instapost) {
            ?>
                <div class="instaPost" id="instaPost-<?php echo $instapost->id ?>">
                    <div class="userDataPost">
                        <div class="row1">
                        <img src="<?php
                            if($instapost->perfilPhoto == null){
                                echo 'assets/logo.webp';
                            }else {
                                echo 'data:image/jpeg;base64, '. base64_encode($instapost->perfilPhoto);
                            }
                            ?>">
                        </div>
                        <div class="row2">
                            <a href="profile.php?userSearched=<?php echo $instapost->username ?>"><?php echo $instapost->username ?></a>
                            <span class=""><?php echo $instapost->ubication ?></span>
                        </div>
                        <?php
                            if($instapost->username == $_SESSION["user"]->username) {
                                ?>
                                    <div class="row3">
                                        <form action="logic/functions/deletePost.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas borrar este producto?');">
                                            <button type="submit" class="deleteInstaPhoto" id="deleteInstaPhoto" name="idInstaPost" value="<?php echo $instapost->id ?>"><ion-icon name="trash-outline"></ion-icon></button>
                                        </form>
                                    </div>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="photoPost">
                        <img src="<?php
                            echo 'data:image/jpeg;base64, '. base64_encode($instapost->photo);
                        ?>" class="photoPost">
                    </div>
                    <div class="buttonsPost">
                        <ul>
                            <?php
                                if(lookInstaPost($instapost->id, $user->username)) {
                                    ?>
                                        <li><ion-icon name="heart" class="likeButton" id="likeButton" value="<?php echo $instapost->id ?>"></ion-icon></li>
                                    <?php
                                }else {
                                    ?>
                                        <li><ion-icon name="heart-outline" class="likeButton" id="likeButton" value="<?php echo $instapost->id ?>"></ion-icon></li>
                                    <?php
                                }
                            ?>
                            
                            <li><ion-icon name="chatbubble-outline" class="commentInstaButton" value="<?php echo $instapost->id ?>" id="buttonCommentInsta"></ion-icon></li>
                        </ul>
                    </div>
                    <span class="likesInstaPost"><?php echo $instapost->likes ?></span>
                    <span> Me gusta</span><br>
                    <span class="usernamePost"><?php echo $instapost->username ?><span class="descripPost"><?php echo $instapost->description ?></span></span>
                </div>
                <div class="commentsInstaPost" id="commentsInstaPost-<?php echo $instapost->id ?>" hidden>
                    <span id="exitCommentButton">X</span>
                    <div class="commentsInstaBox" id="commentsInstaBox">
                        
                    </div>
                    <form action="#" class="typingInstaBox">
                        <input type="hidden" name="idPost" class="idPost" value="<?php echo $instapost->id; ?>">
                        <input type="text" name="comment" class="input-field" placeholder="Envía un comentario..." autocomplete="off">
                        <button id="sendInstaComment" value="<?php echo $instapost->id ?>">Enviar</button>
                    </form>
                </div>
            <?php
        }
        ?>
        </div>
        <?php
    }
?>