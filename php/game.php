<?php
require_once 'access.class.php';
require_once 'Db.php';

$db = new Db();

$user = new flexibleAccess();
$name=$user->get_property('username');
$userId = $user->get_property('userID');
session_write_close();
if($_POST['idBack']==1){
    $db->deleteRoom($userId);
    
}

if($_POST['idBack2']==1){
    $db->diconectRoom($userId);
}

if($_POST['idBack']==3){
    $db->deleteRoom($userId);
    $roomId = $_POST['room'];
    $db->deleteGames($roomId);
    $db->deleteTime($roomId);
}

include '../html/game.html';

