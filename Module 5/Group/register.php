<?php
require 'model/user.php';

header("Content-Type: application/json");

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$username = htmlentities($json_obj['username']);
$password = htmlentities($json_obj['password']);

$res = user::register($username, $password);
if ($res == 1) {
    echo json_encode(array(
        "success" => false,
        "message" => "Username has been taken, please choose a new one!"
    ));
    exit;
}
else if($res == 2){
    echo json_encode(array(
        "success" => false,
        "message" => "Invalid username!"
    ));
    exit;
}
else{
    echo json_encode(array(
        "success" => true,
    ));
    exit;
}

?>