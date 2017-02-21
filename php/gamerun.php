<?php
require_once 'access.class.php';
require_once 'Db.php';
$db = new Db();
$user = new flexibleAccess();
$name = $user->get_property('username');
$userId = $user->get_property('userID');
session_write_close();
$roomName = $_POST['roomname'];
$maxTime = $_POST['time'];
if($_COOKIE["trueExit"] = false){
    header('Location: game.php');
}
$db->createRoom($userId, $roomName, $maxTime);
$roomId = $db->getRoomByUser($userId);
$db->createTime($roomId);
$roomId =$db->getRoomByUser($userId);
//echo "roomid=" . $roomID;
include '../html/gamerun.html';