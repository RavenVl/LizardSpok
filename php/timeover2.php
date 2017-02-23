<?php
require_once 'init.php';
$roomId = $db->getRoomByUser($userId);
$game = $db->getAllGames($roomId);
//$masItog ="The Player1 made a move figure $game[0] , The Player2 made a move figure $game[1], Win Player$game[2]";

$itog = $db->getItog($roomId);
if ($itog > 0) {
    $temp = "i" . strval($itog) . ";" . $game;
    $rez = json_encode($temp);
    setcookie("trueExit", true, time() + 900);
} else
    $rez = json_encode($game);


echo $rez;