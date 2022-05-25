<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style/notification.css">
	<title>Main</title>
</head>
<body>
<form class='notification' action='main.php'>
	<?php
	/*
This file is for executing the file-uploading process.
*/

	//check if the user login legally, if not, redirect to the login page.
	session_start();
	if ($_SESSION['login'] != true) {
		header("Location: login.php");
	}

	//The following code refers to the CSE330 wiki
	if (isset($_FILES['uploadedfile']['name'])) {
		$filename = basename($_FILES['uploadedfile']['name']);
		if (!preg_match('/^[\w_\.\-]+$/', $filename)) {
			echo "<p>Invalid filename. Hold 3 seconds or click the back button to get back to your page.</p>";
			echo "<input type='submit' name='action' value='back'>";
			header("refresh:3;url=main.php");
			exit;
		}
		$username = $_SESSION['user'];
		$full_path = "/home/HongyiXu/module2/userfile/" . $username . "/" . "private/" . $filename;

		if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path)) {
			echo " <p>File has been uploaded. Hold 3 seconds or click the back button to get back to your page.</p>";
			echo "<form class='back' method='POST'>
	<input type='submit' value='back' />
</form>";
			header("refresh:3;url=main.php");
			exit;
		} else {
			echo " <p>upload fail! Hold 3 seconds or click the back button to get back to your page.</p>";
			echo "<form class='back' method='POST'>
	<input type='submit' value='back' />
</form>";
			header("refresh:3;url=main.php");
			exit;
		}
	} else {
		echo " <p>upload fail! Hold 3 seconds or click the back button to get back to your page.</p>";
		echo "<form class='back' method='POST'>
	<input type='submit' value='back' />
</form>";
		header("refresh:3;url=main.php");
		exit;
	}
	?>
</form>
</body>
</html>