<?php
$mysqli = new mysqli('localhost', 'user330', 'wustl2022', 'news');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>