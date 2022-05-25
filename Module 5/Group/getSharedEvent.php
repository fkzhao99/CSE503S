<?php
require 'model/event.php';
ini_set("session.cookie_httponly", 1);
session_start();

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);
$shareCode = htmlentities($json_obj['shareCode']);

$event = event::getEventByCode($shareCode);
if($event == '') {
    echo json_encode(array(
		"success" => false,
	));
	exit;
}
$userId = $_SESSION['id'];
$cateId = $event->cateId;
$title = $event->title;
$date = $event->date;
$hour = $event->hour;
$minute = $event->minute;
$isGroup = $event->isGroup;
$shareCode = bin2hex(random_bytes(32));
$shareCode = substr($shareCode, 0, 10);

$res = event::addEvent($userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
echo json_encode(array(
    "success" => true,
    "id" => $res,
    "date" => $date,
));
exit;
?>