<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Story Detail</title>
    <link rel="stylesheet" href="style/edit.css">
</head>
<body>
    <?php
        #This file is for handle the request to comment 
        require "model/comment.php";
        session_start();
        $storyId = $_POST['storyId'];
        if(isset($_POST['addComment']) && $_POST['addComment']==1){
            if(isset($_SESSION['id'])){
                $res = comment::addComment($_SESSION['id'], $storyId, $_POST['commentContent']);
            }
            else{
                $res = comment::addGuestComment($storyId, $_POST['commentContent']);
            }
            
            header("Location:storyDetail.php?storyId=".$storyId);
        }

        if(isset($_POST['editComment']) && $_POST['editComment']==1){
            if(!isset($_SESSION['token']) || (isset($_POST['token']) && $_POST['token'] != $_SESSION['token'])){
                header("Location:login.php");
            }
            else{
                if(isset($_SESSION['id'])){
                    $commentId = $_POST['commentId'];
                    $storyId = $_POST['storyId'];
                    $comment = comment::getCommentById($commentId);
                    ?>
                    <div style="padding: 10%">
                    <h2>Edit your comment</h2>
                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
                    <input type="hidden" name="executeEdit" value="1">
                    <input type="hidden" name="storyId" value="<?php echo $storyId;?>">
                    <input type="hidden" name="commentId" value="<?php echo $commentId;?>">
                    <textarea name="content" cols="25" rows="4" required><?php echo $comment->content;?></textarea><br><br>
                    <input class="editBtn" type="submit" value="Submit">
                    </form>
                    </div>
                <?php
                }
            }

        }

        if(isset($_POST['executeEdit']) && $_POST['executeEdit']==1){
            $storyId = $_POST['storyId'];
            $commentId = $_POST['commentId'];
            $res = comment::editComment($commentId, $_POST['content']);
            if($res == true){
                header("Location:storyDetail.php?storyId=".$storyId);
            }
            else{
                echo "Edit story failed!";
                sleep(3);
                header("Location:storyHandler.php");
            }
        }

        if(isset($_POST['deleteComment']) && $_POST['deleteComment'] == 1){
            if((isset($_POST['token']) && $_POST['token'] != $_SESSION['token']) || !isset($_SESSION['token'])){
                header("Location:login.php");
            }
            else{
                $storyId = $_POST['storyId'];
                $commentId = $_POST['commentId'];
                $res = comment::deleteComment($commentId);
                if($res == true){
                    header("Location:storyDetail.php?storyId=".$storyId);
                }
                else{
                    echo "Delete failed!";
                    sleep(3);
                    header("Location:main.php");
                }
            }
        }
    ?>
</body>
</html>