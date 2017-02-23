<?php
require_once 'init.php';
$roomId = $db->getRoomByUser($userId);
$timestep = $db->getTime($roomId);
usleep(1000000);
$timestep += 1;
$db->setTime($roomId, $timestep);
$maxTime = $db->getMaxTime($roomId);
$rez = [$timestep, $maxTime];
echo json_encode($rez);