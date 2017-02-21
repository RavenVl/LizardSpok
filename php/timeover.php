<?php
require_once 'access.class.php';
require_once 'Db.php';
$db = new Db ();
$user = new flexibleAccess();
$name = $user->get_property('username');
$userId = $user->get_property('userID');
session_write_close();
$roomId = $db->getRoomByUser($userId);
$db->setItogStep($roomId);
sleep(2);
$game= $db->getAllGames($roomId);
sleep(2);
$db->setTime($roomId, 0);
$db->nextStepGames($roomId);
$itog = $db->getItog($roomId);
if($itog>0){
   $temp="i" . strval($itog). ";" . $game;
   $rez=json_encode($temp);
   setcookie("trueExit", false, time()+900);
}
else
   $rez =json_encode($game);


echo $rez ;
