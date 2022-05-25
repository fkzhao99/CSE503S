<?php
require "database.php";

class comment{
    public $id;
    public $userId;
    public $storyId;
    public $content;
    public $time;

    public function __construct($id, $userId, $storyId, $content, $time)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->storyId = $storyId;
        $this->content = $content;
        $this->time = $time;
    }

    #add comment
    public static function addComment($userId, $storyId, $content)
    {
        global $mysqli;
        $stmt = $mysqli->prepare("insert into comment (userId, storyId, content) values (?, ?, ?)");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('iis', $userId, $storyId, $content);
        if($stmt->execute()){
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    #add comment as a guest
    public static function addGuestComment($storyId, $content)
    {
        global $mysqli;
        $stmt = $mysqli->prepare("insert into comment (storyId, content) values (?, ?)");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('is', $storyId, $content);
        if($stmt->execute()){
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    #edit comment
    public static function editComment($id, $content)
    {
        global $mysqli;
        $stmt = $mysqli->prepare("update comment set content=? where id=?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('si', $content, $id);
        if($stmt->execute()){
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    #delete comment
    public static function deleteComment($id){
        global $mysqli;
        $stmt = $mysqli->prepare("delete from comment where id=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $id);
        if($stmt->execute()){
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    #get comment by id
    public static function getCommentById($id){
        global $mysqli;
        $stmt = $mysqli->prepare("select id, userId, storyId, content, time from comment where id=?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($id, $userId, $storyId, $content, $time);
        if ($stmt->fetch()) {
            $s = new static($id, $userId, $storyId, $content, $time);
            $stmt->close();
            if($s->id==null) return null;
            return $s;
        }
        $stmt->close();
        return null;
    }

    #get comment array by story id
    public static function getCommentArray($storyId){
        global $mysqli;
        $stmt = $mysqli->prepare("select id, userId, storyId, content, time from comment where storyId=? order by time DESC");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $storyId);
        $stmt->execute();
        $stmt->bind_result($id, $userId, $storyId, $content, $time);
        $arr = array();
        while($stmt->fetch()){
            $singleStory = new static($id, $userId, $storyId, $content, $time);
            array_push($arr, $singleStory);
        }
        $stmt->close();
        return $arr;
    }

    #get comment number by story id
    public static function getCommentNum($storyId){
        global $mysqli;
        $stmt = $mysqli->prepare("select count(*) from comment where storyId=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $storyId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }
}
?>