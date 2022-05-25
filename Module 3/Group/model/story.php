<?php
require "database.php";

class story{
    public $id;
    public $userId;
    public $title;
    public $content;
    public $link;
    public $time;

    private function __construct($id, $userId, $title, $content, $link, $time)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->title = $title;
        $this->content = $content;
        $this->link = $link;
        $this->time = $time;
    }

    # add story
    public static function addStory($userId, $title, $content, $link){
        global $mysqli;
        $stmt = $mysqli->prepare("insert into story (userId, title, content, link) values (?, ?, ?, ?)");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('isss', $userId, $title, $content, $link);
        if($stmt->execute()){
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    #edit story
    public static function editStory($id, $title, $content, $link){
        global $mysqli;
        $stmt = $mysqli->prepare("update story set title=?, content=?, link=? where id=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('sssi', $title, $content, $link, $id);
        if($stmt->execute()){
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    #delete story
    public static function deleteStory($id){
        global $mysqli;
        $stmt = $mysqli->prepare("delete from story where id=?");
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

    #get story by id
    public static function getStoryById($id){
        global $mysqli;
        $stmt = $mysqli->prepare("select id, userId, title, content, link, time from story where id=?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($id, $userId, $title, $content, $link, $time);
        if ($stmt->fetch()) {
            $s = new static($id, $userId, $title, $content, $link, $time);
            $stmt->close();
            if($s->id==null) return null;
            return $s;
        }
        $stmt->close();
        return null;
    }

    #get the story array
    public static function getStoryArray(){
        global $mysqli;
        $stmt = $mysqli->prepare("select id, userId, title, content, link, time from story order by time DESC");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    
        $stmt->execute();
        $stmt->bind_result($id, $userId, $title, $content, $link, $time);
        $arr = array();
        while($stmt->fetch()){
            $singleStory = new static($id, $userId, $title, $content, $link, $time);
            array_push($arr, $singleStory);
        }
        $stmt->close();
        return $arr;
    }

    #get story array by author name
    public static function getStoryByAuthor($author){
        global $mysqli;
        $id = user::getIdByUser($author);
        $stmt = $mysqli->prepare("select id, userId, title, content, link, time from story where userId=? order by time DESC");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($id, $userId, $title, $content, $link, $time);
        $arr = array();
        while($stmt->fetch()){
            $singleStory = new static($id, $userId, $title, $content, $link, $time);
            array_push($arr, $singleStory);
        }
        $stmt->close();
        return $arr;
    }
}
?>