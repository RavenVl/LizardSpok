<?php
require_once 'init.php';
$roomId = $db->getRoomByUser($userId);
if ($_COOKIE["trueExit"] = false) {
    header('Location: game.php');
}
$roomID = $_POST['idroom'];
$db->connectRoom($userId, $roomID);
//echo "roomid=" . $roomID;
include '../html/gamerun2.html';