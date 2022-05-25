<?php
require 'model/user.php';
ini_set("session.cookie_httponly", 1);
session_start();
$userArray = user::getUserArray();
foreach($userArray as $k=>$user) {
    if($user -> id == $_SESSION['id']){
        unset($userArray[$k]);
        break;
    }
}
echo json_encode($userArray);
exit;
?>