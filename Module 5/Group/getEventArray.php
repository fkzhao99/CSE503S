<?php
require 'model/event.php';
ini_set("session.cookie_httponly", 1);
session_start();

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);


if(isset($_SESSION['username'])) {
    $userId = $_SESSION['id'];
    $date = htmlentities($json_obj['showDate']);
    $eventArray = event::getEventByIdDate($userId, $date);
    echo json_encode($eventArray);
    exit;
}
else {
    echo json_encode(array(
		"success" => false,
	));
	exit;
}
?>