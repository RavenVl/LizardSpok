<?php
require_once 'access.class.php';
require_once 'Db.php';
$db = new Db();
$user = new flexibleAccess();
$name = $user->get_property('username');
$userId = $user->get_property('userID');
session_write_close();
$roomId = $db->getRoomByUser($userId);
if ($_POST['start'] = 1){
    $db->nextStepGames($roomId);
}
setcookie("trueExit", false, time()+900);
include '../html/gameEval.html';
