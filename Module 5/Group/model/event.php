<?php
require 'database.php';

class event{
    public $id;
    public $userId;
    public $cateId;
    public $title;
    public $date;
    public $hour;
    public $minute;
    public $isGroup;
    public $shareCode;

    private function __construct($id, $userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->cateId = $cateId;
        $this->title = $title;
        $this->date = $date;
        $this->hour = $hour;
        $this->minute = $minute;
        $this->isGroup = $isGroup;
        $this->shareCode = $shareCode;
    }

    public static function getEventById($id) {
        global $mysqli;
        $stmt = $mysqli->prepare("select id, userId, cateId, title, date, hour, minute, isGroup, shareCode from event where id=?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($id, $userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
        if ($stmt->fetch()) {
            $event = new static($id, $userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
            $stmt->close();
            return $event;
        }
        $stmt->close();
        return null;
    }

    public static function addEvent($userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode) {
        global $mysqli;
        $stmt = $mysqli->prepare("insert into event (userId, cateId, title, date, hour, minute, isGroup, shareCode) values (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('iissiiis', $userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
        if ($stmt->execute()) {
            $id = $mysqli->insert_id;
            $stmt->close();
            return $id;
        }
        $stmt->close();
        return null;
    }

    public static function getEventByIdDate($userId, $date) {
        global $mysqli;
        $stmt = $mysqli->prepare("select id, userId, cateId, title, date, hour, minute, isGroup, shareCode from event where userId=? and date=?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            return $mysqli->error;
            exit;
        }
        $stmt->bind_param('is', $userId, $date);
        $stmt->execute();
        $stmt->bind_result($id, $userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
        $arr = array();
        while($stmt->fetch()){
            $singleEvent = new static($id, $userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
            array_push($arr, $singleEvent);
        }
        $stmt->close();
        return $arr;
    }
    
    public static function getEventByIdDateCate($userId, $date, $cateId) {
        global $mysqli;
        $stmt = $mysqli->prepare("select id, userId, cateId, title, date, hour, minute, isGroup, shareCode from event where userId=? and date=? and cateId=?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            return $mysqli->error;
            exit;
        }
        $stmt->bind_param('isi', $userId, $date, $cateId);
        $stmt->execute();
        $stmt->bind_result($id, $userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
        $arr = array();
        while($stmt->fetch()){
            $singleEvent = new static($id, $userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
            array_push($arr, $singleEvent);
        }
        $stmt->close();
        return $arr;
    }

    public static function editEvent($eventId, $userId, $cateId, $title, $date, $hour, $minute, $isGroup) {
        global $mysqli;
        $stmt = $mysqli->prepare("update event set userId=?, cateId=?, title=?, date=?, hour=?, minute=?, isGroup=? where id=?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('iissiiii', $userId, $cateId, $title, $date, $hour, $minute, $isGroup, $eventId);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return null;
    }

    public static function deleteEvent($eventId) {
        global $mysqli;
        $stmt = $mysqli->prepare("delete from event where id=?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $eventId);
        if($stmt->execute()){
            $stmt->close();
            return true;
        }
        $stmt->close();
        return null;
    }

    public static function getEventByCode($shareCode) {
        global $mysqli;
        $stmt = $mysqli->prepare("select id, userId, cateId, title, date, hour, minute, isGroup, shareCode from event where shareCode=?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $shareCode);
        $stmt->execute();
        $stmt->bind_result($id, $userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
        if ($stmt->fetch()) {
            $event = new static($id, $userId, $cateId, $title, $date, $hour, $minute, $isGroup, $shareCode);
            $stmt->close();
            return $event;
        }
        $stmt->close();
        return null;
    }
}
?>