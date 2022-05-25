<!DOCTYPE html>
<html lang="en">
<?php
/*
This file is the main page after the user logs in.
The user can see all his files here, and choose to view or delete them.
The user can log out here.
 */

//check if the user login legally, if not, redirect to the login page.
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
}

$username = $_SESSION['user'];
$path = "/home/HongyiXu/module2/userfile/" . $username . '/private';
$path2 = "/home/HongyiXu/module2/userfile/" . $username . '/share';
?>



<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <title>Main</title>
</head>

<body>
    <!--Title of the page-->
    <h1>Welcome to File Sharing Site</h1>
    <div class="showuser">
        <?php
        echo "Hi! " . htmlentities($username);
        ?>
    </div>
    <br>

    <div class="showprivate">
    <?php
    //show the list of the user's files.
    print_r("Here are your files:");
    echo "<br>";
    echo nl2br("\n");
    //for each file, give a 'view' button, a 'download' button and a 'delete' button for the user to choose.
    $files = scandir($path);
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            print_r($file);
            echo "<form action='viewOrdelete.php' method='POST'>
        <input type='hidden' name='file' value='$file'>
        <input type='submit' name='action' value='view'>
        <input type='submit' name='action' value='download'>
        <input type='submit' name='action' value='delete'>
        </form>";
        }
    }
    ?>
    </div>

    <div class="pendingfile">
    <?php
    //show the list of the user's received files.
    print_r("Here are your pending received files:");
    echo "<br>";
    echo nl2br("\n");
    //for each received file, give a 'view' button, a 'accept' button and a 'decline' button for the user to choose.
    $files2 = scandir($path2);
    foreach ($files2 as $file) {
        if ($file != "." && $file != "..") {
            print_r($file);
            echo "<form action='acceptOrdecline.php' method='POST'>
        <input type='hidden' name='file' value='$file'>
        <input type='submit' name='action' value='view'>
        <input type='submit' name='action' value='accept'>
        <input type='submit' name='action' value='decline'>
        </form>";
        }
    }
    ?>
    </div>

    <br>


    <!--Button for uploading files. The following code refers to the CSE330 wiki.-->
    <br>
    <form class="upload" enctype="multipart/form-data" action="uploader.php" method="POST">
        <p>
            <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
            <label >Choose a file to upload:</label>
            <input name="uploadedfile" type="file" id="uploadfile_input" />
        </p>
        <p>
            <input type="submit" value="Upload File" />
        </p>
    </form>

    <!--Button for send files. The following code refers to the CSE330 wiki and some php instruction source.-->
    <form class="send" action="send.php" method="POST">
        <p>
            <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
            <label>Input a file's name to send:</label>
            <input name="sendfile" type="text" required />
        </p>
        <p>
            <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
            <label>Choose a person to send:</label>
            <select name=chooseperson>
                <option selected="selected">choose one person</option>
                <?php
                $file = fopen('/home/HongyiXu/module2/users.txt','r');
                while (!feof($file)){
                    $line = fgets($file);
                    $reg="/$username/";
                   if(!preg_match($reg,$line) && trim($line)<>""){
                      $arr[]=$line;
                   }
                  }
                  fclose($file);

                foreach ($arr as $v) {
                ?>
                    <option value="<?php echo $v;?>"><?php echo $v;?></option>
                <?php
                }
                ?>
            </select>
        </p>
        <p>
            <input type="submit" value="Send File" />
        </p>
    </form>

    <!--Button for delete account.-->
    <form class="delacc" action="editaccount.php" method="POST">
            <input type="submit" value="Delete account" />
    </form>

    <!--Button for log out.-->

    <form class="logout" action="logout.php" method="POST">
            <input type="submit" value="Logout">
    </form>


</body>

</html>