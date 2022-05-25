<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
    <h1>Register</h1>
    <div class = "login">
    <br>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="hidden" name="register" value="1">
        <label>Username</label>
        <input type="text" name="username" id="username" required/><br><br>
        <label>Password</label>
        <input type="password" name="password" id="password" required/><br><br>
        <input type="submit" value="Register" />
    </form>
    </div>

<?php
require 'model/user.php';

if(isset($_POST['register']) && $_POST['register'] == 1){
    $res = user::register($_POST['username'], $_POST['password']);
    if ($res == 1) {
        echo "<p>Username has been taken, please choose a new one!</p>";
    }
    else if($res == 2){
        echo "<p>Invalid username!</p>";
    }
    else{
        echo "<p>You have successfully registered! Redirect to login page in 3 seconds.</p>";
        header("refresh:3; url=login.php");
    }
}
?>

<form class="back" action="main.php">
    <input type="submit" value="Back to Main Page">
</form>
</body>
</html>