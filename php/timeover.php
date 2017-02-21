<?php
require_once 'init.php';
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
