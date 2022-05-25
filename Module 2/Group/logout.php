<?php
/*
This file is for a user to log out.
*/

session_start();
//untset the session
session_unset();

//destroy the session
session_destroy();

//return to login page
header("Location: login.php");
exit;
?>