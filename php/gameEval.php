<?php
require_once 'init.php';

$roomId = $db->getRoomByUser($userId);

if ($_POST['start'] = 1){
    $db->nextStepGames($roomId);
}
setcookie("trueExit", false, time()+900);
include '../html/gameEval.html';
