<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Story Detail</title>
    <link rel="stylesheet" href="style/detail.css">
</head>
<body>
    <?php
    
        require "model/story.php";
        require "model/comment.php";
        require "model/user.php";
        session_start();
        
        #If the user is the author of the story, show edit and delete button
        $storyId = $_GET['storyId'];
        $story = story::getStoryById($storyId);

        ?><h1><?php echo htmlentities($story->title); ?></h1>

        <form class="back" action="main.php">
        <input type="submit" value="Back">
        </form>

        <?php
        if(isset($_SESSION['id']) && $_SESSION['id'] == $story->userId){
            ?>
            <form class="action" action="storyHandler.php" method="POST">
                <input type="hidden" name="edit" value="1">
                <input type="hidden" name="storyId" value="<?php echo $story->id; ?>">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>">
                <input type="submit" value="Edit">
            </form>

            <form class="action" action="storyHandler.php" method="POST">
                <input type="hidden" name="delete" value="1">
                <input type="hidden" name="storyId" value="<?php echo $story->id; ?>">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>">
                <p><input type="submit" value="Delete"></p>
            </form>
            </div>

            <?php
        }
    ?>
    
    <!-- Display the content and information of the story -->
    <div class="storyInfo">
    <p>Post by: <?php echo user::getUserById($story->userId);?></p>
    <p>Post Time: <?php echo $story->time; ?></p>
    </div>

    <div class="content"><?php echo htmlentities($story->content); ?></div>

    <p class="link">
    Link: <a href="<?php echo htmlentities($story->link); ?>">
        <?php echo $story->link;?>
    </a>
    </p>
    <HR>
    <!-- Display the comment part -->
    <form class="comment" action="commentHandler.php" method="POST">
        <input type="hidden" name="addComment" value="1">
        <input type="hidden" name="storyId" value="<?php echo $storyId; ?>">
        <textarea name="commentContent" id="commentContent" cols="80" rows="5" placeholder="What do you think?" required></textarea><br><br>
        <input type="submit" value="Comment"><br>
    </form>
    <br>
    <?php
        #display comments of the story
        $commentArray = comment::getCommentArray($storyId);
        foreach($commentArray as $comment){
            ?>
            <div class="CommentContent">
                <?php echo htmlentities($comment->content);?><br>
            
                <p class="CommentInfo" style="font-size: 10px; color: darkgray;">Post by: <?php if(user::getUserById($comment->userId)==null) echo "Guest"; 
                                else echo user::getUserById($comment->userId);?>
                </p>
                <p class="CommentInfo" style="font-size: 10px; color: darkgray;">Post Time: <?php echo $comment->time;?></p>
                <?php
                    # If the user has logged in, show edit button for this user's comment
                    if(isset($_SESSION['id']) && $_SESSION['id'] == $comment->userId){
                        ?>
                        <table>
                        <tr>
                        <td>
                        <form action="commentHandler.php" method="POST">
                            <input type="hidden" name="editComment" value="1">
                            <input type="hidden" name="commentId" value="<?php echo $comment->id; ?>">
                            <input type="hidden" name="storyId" value="<?php echo $storyId; ?>">
                            <input type="submit" value="edit">
                        </form>
                        </td>
                        <td>
                        <form action="commentHandler.php" method="POST">
                            <input type="hidden" name="deleteComment" value="1">
                            <input type="hidden" name="commentId" value="<?php echo $comment->id; ?>">
                            <input type="hidden" name="storyId" value="<?php echo $storyId; ?>">
                            <input type="submit" value="delete">
                        </form>
                        </td>
                        </tr>
                        </table>
                        <?php
                    }
                ?>
            </div>
            <br>
            <?php
        }
    ?>
    <br><br>
</body>
</html>