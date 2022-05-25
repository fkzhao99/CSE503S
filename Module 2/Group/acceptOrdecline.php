<?php
/*
This file is for executing the accepting or declining process.
*/

//check if the user login legally, if not, redirect to the login page.
session_start();
if($_SESSION['login'] != true){
	header("Location: login.php");
}
//get the file name and the action type:accept/decline.
$file = $_POST['file'];
$action = $_POST['action'];
$username = $_SESSION['user'];
$filepath = '/home/HongyiXu/module2/userfile/'.$username.'/'.'share/'.$file;
$filepath2 = '/home/HongyiXu/module2/userfile/'.$username.'/'.'private/'.$file;
//accept case.
if($action == 'accept'){
    rename($filepath,$filepath2);
    header("Location: main.php");
}
//decline case.
if($action == 'decline'){
    unlink($filepath);
    header("Location: main.php");
}
//view case. The following code refers to the CSE330 wiki.
else if($action == 'view'){
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($filepath);
    header("Content-Type: ".$mime);
    readfile($filepath);
}
?>