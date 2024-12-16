<?php
    require_once("logic/entity/shortpost.php");
    require_once("logic/entity/user.php");
    require_once("../src/logic/gnl_functions.php");
    function chargeShortPosts($shortposts) {
        ?>
        <div class="shortPosts">
        <?php
        foreach ($shortposts as $shortpost) {
            ?>
                <div class="shortPost" id="shortPost-<?php echo $shortpost->id ?>">
                    <div class="userDataPost">
                        <div class="row1">
                        <img src="<?php
                            if($shortpost->perfilPhoto == null){
                                echo 'assets/logo.webp';
                            }else {
                                echo 'data:image/jpeg;base64, '. base64_encode($shortpost->perfilPhoto);
                            }
                            ?>">
                        </div>
                        <div class="row2">
                            <span class=""><?php echo $shortpost->username ?></span>
                        </div>
                        <?php
                            if($shortpost->username == $_SESSION["user"]->username) {
                                ?>
                                    <div class="row3">
                                        <form action="logic/functions/deletePost.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas borrar este producto?');">
                                            <button class="deleteShortPhoto" id="deleteShortPhoto" name="idShortPost" value="<?php echo $shortpost->id ?>"><ion-icon name="trash-outline"></ion-icon></button>
                                        </form>
                                    </div>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="messagePost">
                        <p><?php echo $shortpost->message ?></p>
                    </div>
                    <div class="buttonsPost">
                        <ul>
                            <?php
                                if(lookShortPost($shortpost->id, $_SESSION["user"]->username)) {
                                    ?>
                                        <li><ion-icon name="heart" class="likeButton2" value="<?php echo $shortpost->id ?>"></ion-icon></li>
                                    <?php    
                                }else {
                                    ?>
                                        <li><ion-icon name="heart-outline" class="likeButton2" value="<?php echo $shortpost->id ?>"></ion-icon></li>
                                    <?php
                                }
                            ?>
                            <li><ion-icon name="chatbubble-outline" class="commentShortButton" value="<?php echo $shortpost->id ?>"></ion-icon></li>
                        </ul>
                    </div>
                    <div class="stadisticsPost">
                        <ul>
                            <li><span class="numLikes"><?php echo $shortpost->likes ?></span></li>
                            <li><span class="numComments"><?php echo $shortpost->comments ?></span></li>
                        </ul>
                    </div>
                </div>
                <div class="commentsShortPost" id="commentsShortPost-<?php echo $shortpost->id ?>" hidden>
                    <span id="exitCommentButton">X</span>
                    <div class="commentsShortBox" id="commentsShortBox">
                        hoooola
                    </div>
                    <form action="#" class="typingShortBox">
                        <input type="hidden" name="idPost" class="idPost" value="<?php echo $shortpost->id; ?>">
                        <input type="text" name="comment" class="input-field" placeholder="Envía un comentario..." autocomplete="off">
                        <button id="sendShortComment" value="<?php echo $shortpost->id ?>">Enviar</button>
                    </form>
                </div>
            <?php
        }
        ?>
        </div>
        <?php
    }
?>