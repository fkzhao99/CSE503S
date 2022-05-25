<!DOCTYPE html>
<html lang="en">
<head>
    <meta  charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Sharing Site</title>
    <link rel="stylesheet" type="text/css" href="style/login.css">
</head>
<body class=background>
    <h1>User Login</h1>
    <form class=logininput action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="text" name="username" placeholder="Input your Username" required>
        <br><br>
        <input type="submit" value="Login">
    </form>
    <br>
    <form class=registerinput action="register.php" method="POST">
        Not yet a user? <input type="submit" value="Register">
    </form>
    <?php
    /*
    This file is for a user to login.
    */
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
    
        //read username file
        $h = fopen("/home/HongyiXu/module2/users.txt", "r");
    
        $exist = false;
        //check if the user is in username file
        while( !feof($h) ){
            $getname = fgets($h);
            if(trim($getname) == $username){
                $exist = true;
                break;
            }
        }
    
        fclose($h);
    
    //if the user exist, login
        if($exist == true){
            session_start();
            $_SESSION['user'] = $username;
            $_SESSION['login'] = true;
            header("Location: main.php");
            exit;
        }
    
        //not exist, return to index
        else{
            echo "User not exist! Log in fail!";
        }
    }
    
    ?>
</body>
</html>

