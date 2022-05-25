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
    $userId = $_SESSION['id'];
    $cateId = $json_obj['addCate'];
    $title = htmlentities($json_obj['addTitle']);
    $date = $json_obj['addDate'];
    $date = date("Y-m-d",strtotime($date));
    $hour = $json_obj['addHour'];
    $minute = $json_obj['addMinute'];
    $isGroup = $json_obj['addIsGroup'];
    $shareCode = bin2hex(random_bytes(32));
    $shareCode = substr($shareCode, 0, 10);

    $selectUsers = $json_obj['selectUsers'];
    $res = event::addEvent($userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
    foreach($selectUsers as $singleUser) {
        $shareCode = bin2hex(random_bytes(32));
        $shareCode = substr($shareCode, 0, 10);
        $newres = event::addEvent($singleUser, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
        $res = ($newres && $res);
    }
    if($res != null){
        echo json_encode(array(
            "success" => true,
            "message" => "Success!"
        ));
        exit;
    }
}
?>