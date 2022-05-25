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
<form class='notification' action='login.php'>
<?php
/*
This file is for executing the deleting account process.
*/

//check if the user login legally, if not, redirect to the login page.
session_start();
if($_SESSION['login'] != true){
	header("Location: login.php");
}

//get the file name and the account folders.
$datas = file("/home/HongyiXu/module2/users.txt");
$username = $_SESSION['user'];
$filepath = '/home/HongyiXu/module2/userfile/'.$username;

//delect all this user's folders
function delDir($path){
    $handle = opendir($path);
    if ($handle) {
        while (false !== ($item = readdir($handle))) {
            if (($item != ".") && ($item != "..")) {
                is_dir("$path/$item") ? delDir("$path/$item") : unlink("$path/$item");
            }
        }
        closedir($handle);
        rmdir($path);
    } elseif (file_exists($path)) {
        return unlink($path);
    }
}

//delect user's information in users.txt
delDir($filepath);
$arr=[];
$file=fopen('/home/HongyiXu/module2/users.txt','r');
while (!feof($file)){
    $line = fgets($file);
    $reg="/$username/";
   if(!preg_match($reg,$line) && trim($line)<>""){
      $arr[]=$line;
   }
  }
  fclose($file);

$str = implode($arr);
file_put_contents("/home/HongyiXu/module2/users.txt", $str);


//back to the login page
echo "<p> Delete the account successfully! Hold 3 seconds or click the back button to get back to your page.</p>";
echo "<input type='submit' name='action' value='back'>";

header("refresh:3;url=login.php");

?>
</form>
</body>
</html>