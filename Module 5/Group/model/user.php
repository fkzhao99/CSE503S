<?php
require 'database.php';

class user{
    public $id;
    public $username;
    
    private function __construct($id, $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    # get username by id
    public static function getUserById($id){
        global $mysqli;
        $stmt = $mysqli->prepare("select username from user WHERE id=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($username);
        if($stmt->fetch()){
            $stmt->close();
            return $username;
        }
        $stmt->close();
        return null;
    }

    # get id by username
    public static function getIdByUser($username){
        global $mysqli;
        $stmt = $mysqli->prepare("select id from user WHERE username=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($id);
        if($stmt->fetch()){
            $stmt->close();
            return $id;
        }
        $stmt->close();
        return null;
    }

    # get username array
    public static function getUserArray(){
        global $mysqli;
        $stmt = $mysqli->prepare("select id, username from user");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    
        $stmt->execute();
        $stmt->bind_result($id, $username);
        $arr = array();
        while($stmt->fetch()){
            $singleUser = new static($id, $username);
            array_push($arr, $singleUser);
        }
        $stmt->close();
        return $arr;
    }

    # register
    public static function register($username, $password){
        global $mysqli;

        $stmt = $mysqli->prepare("select count(*) from user WHERE username=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if($count > 0){
            return 1;
        }
        else if( !preg_match('/^[\w_\.\-]+$/', $username) ){
            return 2;
        }
        else{
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $user_info = $mysqli->prepare("insert into user (username, password) values (?, ?)");
                if(!$user_info){
                  printf("Query Prep Failed: %s\n", $mysqli->error);
                  exit;
                }
                $user_info->bind_param('ss', $username, $password_hash);
                $user_info->execute();
                $user_info->close();
                return $username;
        }
        return null;
    }

    # login
    public static function login($username, $password){
        global $mysqli;

        $stmt = $mysqli->prepare("select id, username, password from user where username=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($id, $username, $hash);
        $stmt->fetch();
        $stmt->close();
        if(!password_verify($password, $hash)){
            return null;
        }
        else{
            return new static($id, $username);
        }
        return null;
    }
}
?>