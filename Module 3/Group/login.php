<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
    <h1>Login</h1>
    <div class = "login">
    <br>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="hidden" name="login" value="1">
        <label>Username</label>
        <input type="text" name="username" id="username" required/><br><br>
        <label>Password</label>
        <input type="password" name="password" id="password" required/><br><br>
        <input type="submit" value="Login" />
    </form>
    <br><br>
    <form action="register.php" method="POST">
        Not yet a user?&nbsp;&nbsp;<input type="submit" value="Register">
    </form>
   
    Or
    <br>
    <a href="main.php" class = "guest_link">View stories as guest</a>
    <br><br>
    </div>

    <div class='msg'>
    <?php
    require 'model/user.php';

    if(isset($_POST['login']) && $_POST['login'] == 1){
        $res = user::login($_POST['username'], $_POST['password']);
        if($res == null){
            ?>
            <div style="text-align: center;">Wrong username or password!</div>
            <?php
        }
        else{
            session_start();
            $_SESSION['id'] = $res->id;
            $_SESSION['token'] = bin2hex(random_bytes(32));
            header("Location:main.php");
        }
    }
    ?>
    </div>
</body>
</html>