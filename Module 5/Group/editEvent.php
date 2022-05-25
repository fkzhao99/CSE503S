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
    $eventId = $json_obj['editId'];
    $userId = $_SESSION['id'];
    $cateId = $json_obj['editCate'];
    $title = htmlentities($json_obj['editTitle']);
    $date = $json_obj['editDate'];
    $date = date("Y-m-d",strtotime($date));
    $hour = $json_obj['editHour'];
    $minute = $json_obj['editMinute'];
    $isGroup = $json_obj['editIsGroup'];

    $res = event::editEvent($eventId, $userId, $cateId, $title, $date, $hour, $minute, $isGroup);

    if($res != null){
        echo json_encode(array(
            "success" => true,
            "message" => "Success!"
        ));
        exit;
    }
}
?>