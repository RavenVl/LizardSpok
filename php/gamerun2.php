<?php
require_once 'access.class.php';
require_once 'Db.php';
$db = new Db();
$user = new flexibleAccess();
$name = $user->get_property('username');
$userId = $user->get_property('userID');
session_write_close();
if($_COOKIE["trueExit"] = false){
    header('Location: game.php');
}
$roomID = $_POST['idroom'];
$db->connectRoom($userId, $roomID);
echo "roomid=" . $roomID;
include '../html/gamerun2.html';