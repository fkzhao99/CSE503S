<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/notification.css">
    <title>Main</title>
</head>
<body>
<form class='notification' method="POST" action='main.php'>
<?php
/*
This file is for executing the file-sharing process.
*/

//check if the user login legally, if not, redirect to the login page.
session_start();
if($_SESSION['login'] != true){
	header("Location: login.php");
}

if(isset($_POST['sendfile']) && isset($_POST['chooseperson'])){
    $file = $_POST['sendfile'];
    $sendusername = trim($_POST['chooseperson']);
    $username = $_SESSION['user'];
    $Privious_path = "/home/HongyiXu/module2/userfile/".$username."/"."private/".$file;
    $Send_path = "/home/HongyiXu/module2/userfile/".$sendusername."/"."share/".$file;
    $datas = is_file("/home/HongyiXu/module2/userfile/".$username."/"."private/".$file);
    //check if input the right filename.
    if ($datas){
        if( copy($Privious_path, $Send_path) ){
            echo "<p>Successfully send. Hold 3 seconds or click the back button to get back to your page.</p>";
            header("refresh:3;url=main.php");
        
        }else{
            echo "<p>Send fail! Hold 3 seconds or click the back button to get back to your page.</p>";
            header("refresh:3;url=main.php");
        
        }   
    }
    else{
        echo "<p>Input the right file name! Hold 3 seconds or click the back button to get back to your page.</p>";
        header("refresh:3;url=main.php");
    
    }   
}
   

?>
<input type='submit' value='back'>
</form>
<?php
#header("refresh:3;url=main.php");
?>
</body>
</html>