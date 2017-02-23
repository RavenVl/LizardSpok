<?php
require_once 'init.php';

$roomName = $_POST['roomname'];
$maxTime = $_POST['time'];
if ($_COOKIE["trueExit"] = false) {
    header('Location: game.php');
}
$db->createRoom($userId, $roomName, $maxTime);
$roomId = $db->getRoomByUser($userId);
$db->createTime($roomId);
$roomId = $db->getRoomByUser($userId);
//echo "roomid=" . $roomID;
include '../html/gamerun.html';