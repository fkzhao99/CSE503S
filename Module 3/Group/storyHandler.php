<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Story</title>
    <link rel="stylesheet" href="style/edit.css">
</head>
<body>
    <?php
    require 'model/story.php';
    session_start();
    # If the request is add, display a blank form
    if(isset($_POST['add']) && $_POST['add'] == 1){
        if(!isset($_SESSION['token']) || (isset($_POST['token']) && $_POST['token'] != $_SESSION['token'])){
            header("Location:login.php");
        }
    ?>  
        <h2>New Story</h2>
        <form action="main.php">
        <input class="back" type="submit" value="Back">
        </form><br><br>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="hidden" name="executeAdd" value="1">
            <textarea name="title" id="title" cols="25" rows="3" placeholder="Title" required></textarea><br><br>
            <textarea name="content" id="content" cols="25" rows="15" placeholder="Content" required></textarea><br><br>
            <textarea name="link" id="link" cols="25" rows="3" placeholder="Link" required></textarea><br><br>
            <input class="editBtn" type="submit" value="Submit">
        </form>
    <?php
    }
    if(isset($_POST['executeAdd']) && $_POST['executeAdd']==1){
        $res = story::addStory($_SESSION['id'], $_POST['title'], $_POST['content'], $_POST['link']);
        if($res == true){
            header("Location:main.php");
        }
        else{
            echo "Add new story failed!";
            sleep(3);
            header("Location:storyHandler.php");
        } 
    }
    

    # If the request is edit, display a form with the story's original content
    if(isset($_POST['edit']) && $_POST['edit'] == 1){
        if(!isset($_SESSION['token']) || (isset($_POST['token']) && $_POST['token'] != $_SESSION['token'])){
            header("Location:login.php");
        }
        else{
            $storyId = $_POST['storyId'];
            $story = story::getStoryById($storyId);
        ?>  
            <h2>Edit your Story</h2>
            <form action="main.php">
            <input class="back" type="submit" value="Back">
            </form><br><br>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="executeEdit" value="1">
                <input type="hidden" name="storyId" value="<?php echo $story->id;?>">
                <textarea name="title" cols="25" rows="3" required><?php echo $story->title;?></textarea><br><br>
                <textarea name="content" cols="25" rows="15" required><?php echo $story->content;?></textarea><br><br>
                <textarea name="link" cols="25" rows="3" required><?php echo $story->link;?></textarea><br><br>
                <input class="editBtn" type="submit" value="Submit">
            </form>
        <?php
        }
    }
    if(isset($_POST['executeEdit']) && $_POST['executeEdit']==1){
        $storyId = $_POST['storyId'];
        $res = story::editStory($storyId, $_POST['title'], $_POST['content'], $_POST['link']);
        if($res == true){
            header("Location:storyDetail.php?storyId=".$storyId);
        }
        else{
            echo "Edit story failed!";
            sleep(3);
            header("Location:storyHandler.php");
        }
    }


    # If the request is delete, just delete the story from the database and back to main page
    if(isset($_POST['delete']) && $_POST['delete'] == 1){
        if((isset($_POST['token']) && $_POST['token'] != $_SESSION['token']) || !isset($_SESSION['token'])){
            header("Location:login.php");
        }
        else{
            $storyId = $_POST['storyId'];
            $res = story::deleteStory($storyId);
            if($res == true){
                header("Location:main.php");
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