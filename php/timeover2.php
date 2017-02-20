<?php
require_once 'access.class.php';
require_once 'Db.php';
$db = new Db ();
$user = new flexibleAccess();
$name = $user->get_property('username');
$userId = $user->get_property('userID');
session_write_close();
$roomId = $db->getRoomByUser($userId);
$game = $db->getAllGames($roomId);
//$masItog ="The Player1 made a move figure $game[0] , The Player2 made a move figure $game[1], Win Player$game[2]";

$itog = $db->getItog($roomId);
if($itog>0){
    $temp="i" . strval($itog). ";" . $game;
    $rez=json_encode($temp);
}
else
    $rez =json_encode($game);


echo $rez ;