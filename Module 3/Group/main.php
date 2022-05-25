<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="stylesheet" href="style/main.css">
</head>
<body>
<h1>Welcome to the News Website!</h1>
<ul class="btns">
<li class="left">
    <form action="main.php" method="GET">
        <input type="text" name="search">
        <input type="submit" value="Search Author">
    </form>
    <br><br>
    <form action="main.php" method="GET">
        <input type="hidden" name="hot">
        <input type="submit" value="Hottest">
    </form>
    <form action="main.php">
        <input type="submit" value="Latest">
    </form>
</li>
<?php
    require 'model/database.php';
    require 'model/user.php';
    require 'model/story.php';
    require 'model/comment.php';
    session_start();

    # Check if the user has logged in, if not, display login and register button
    if(!isset($_SESSION['id'])){
        printf("<li style='width: 300px; height: 100px; float: left; margin-right: 400px; font-size: 20px;'>Hello, Guest! \n</li>");
        ?>
        <li class="right">
        <form  action="login.php">
            <input type="submit" value="Login">
        </form>
        <br>
        <form action="register.php">
            <input type="submit" value="Register">
        </form>
        </li>
<?php
    }
    # If logged in, display logout and newStory button
    else{
        $userId = $_SESSION['id'];
        $username = user::getUserById($userId);
        printf("<li style='width: 300px; height: 100px; float: left; margin-right: 400px; font-size: 20px;'>Hello, %s! \n</li>", htmlentities($username));
        ?>
        <li class="right">
        <form action="storyHandler.php" method="POST">
            <input type="hidden" name="add" value="1">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>">
            <input type="submit" value="New Story">
        </form>
        <br>
        <form action="logout.php">
            <input type="submit" value="Logout">
        </form>
        </li>
<?php
    }
?>
</ul>
<br><br>
<HR>
<?php
    # Display the stories, order by posted time
    $storyArray = story::getStoryArray();
    # If receive search request, show certain stories
    if(isset($_GET['search'])){
        $author = htmlentities($_GET['search']);
        $storyArray = story::getStoryByAuthor($author);
    }
    # Sort by comment number
    if(isset($_GET['hot'])){
        foreach($storyArray as $story){
            $hot[] = comment::getCommentNum($story->id);
        }
        array_multisort($hot, SORT_DESC, SORT_NUMERIC, $storyArray);
    }
    foreach($storyArray as $story){
        ?>
        <div class="storyTitle">
            <a href="<?php echo "storyDetail.php?storyId=".$story->id; ?>">
                <h2><?php echo htmlentities($story->title);?></h2>
            </a>
        </div>

        <div class="storyInfo">
            <p>Post by: <?php echo user::getUserById($story->userId);?></p>
            <p>Post Time: <?php echo $story->time;?></p>
            <p>Comments: <?php echo comment::getCommentNum($story->id) ?></p>
        </div>
        <HR>
        <?php
    }
?>
</body>
</html>