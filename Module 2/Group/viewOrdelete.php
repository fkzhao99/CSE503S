<?php
/*
This file is for executing the viewing or deleting process.
*/

//check if the user login legally, if not, redirect to the login page.
session_start();
if($_SESSION['login'] != true){
	header("Location: login.php");
}

//get the file name and the action type:view/delete.
$file = $_POST['file'];
$action = $_POST['action'];
$username = $_SESSION['user'];
$filepath = '/home/HongyiXu/module2/userfile/'.$username.'/'.'private/'.$file;

//delete case.
if($action == 'delete'){
    unlink($filepath);
    header("Location: main.php");
}

//view and download case. The following code refers to the CSE330 wiki and some php instruction source.
else if($action == 'view'){
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($filepath);
    header("Content-Type: ".$mime);
    readfile($filepath);
}
else if($action == 'download'){
    if(file_exists($filepath)) {

        //Define header information

        header('Content-Disposition: attachment; filename="'.basename($file).'"');

        
        //Clear system output buffer
        flush();
        
        //Read the size of the file
        readfile($filepath);
        
        //Terminate from the script
        die();
    }

        
}
?>