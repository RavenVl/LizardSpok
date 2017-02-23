<?php
require_once 'init.php';
$roomId = $db->getRoomByUser($userId);
$timestep = $db->getTime($roomId);
setcookie("trueExit", false, time() + 900);
usleep(900000);

$maxTime = $db->getMaxTime($roomId);
$rez = [$timestep, $maxTime];
echo json_encode($rez);
