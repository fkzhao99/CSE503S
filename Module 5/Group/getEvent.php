<?php
require 'model/event.php';

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);
$eventId = $json_obj['eventId'];
$eventId = substr($eventId, 1);
$event = event::getEventById($eventId);
echo json_encode($event);
exit;
?>