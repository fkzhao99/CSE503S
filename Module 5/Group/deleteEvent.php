<?php
require 'model/event.php';
ini_set("session.cookie_httponly", 1);
session_start();

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$token = $json_obj['token'];
if($token != $_SESSION['token']) {
    echo json_encode(array(
        "success" => false,
        "message" => "Wrong token"
    ));
    exit;
}

else{
    $eventId = $json_obj['deleteId'];
    $res = event::deleteEvent($eventId);
    if($res != null){
        echo json_encode(array(
            "success" => true,
            "message" => "Success!"
        ));
        exit;
    }
}
?>