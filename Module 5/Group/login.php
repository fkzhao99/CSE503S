<?php
require 'model/user.php';

header("Content-Type: application/json");

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$username = htmlentities($json_obj['username']);
$password = htmlentities($json_obj['password']);

$res = user::login($username, $password);
if($res == null){
    echo json_encode(array(
        "success" => false,
        "message" => "Wrong username or password!"
    ));
    exit;
}
else{
    ini_set("session.cookie_httponly", 1);
    session_start();
    $_SESSION['id'] = $res->id;
    $_SESSION['username'] = $username;
    $_SESSION['token'] = bin2hex(random_bytes(32));
    echo json_encode(array(
        "success" => true,
        "message" => "Successfully log in!",
        "userid" => $_SESSION['id'], 
        "username" => $_SESSION['username'],
        "token" => $_SESSION['token']
    ));
    exit;
}
?>
