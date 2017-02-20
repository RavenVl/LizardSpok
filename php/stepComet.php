<?php
require_once 'access.class.php';
require_once 'Db.php';
$user = new flexibleAccess();
$name = $user->get_property('username');
$userId = $user->get_property('userID');

session_write_close();


$db = new Db ();
$roomId = $db->getRoomByUser($userId);
$timestep=$db->getTime($roomId);
usleep(1000000);
$timestep+=1;
$db->setTime($roomId, $timestep);
$maxTime = $db->getMaxTime($roomId);
$rez=[$timestep, $maxTime];
echo json_encode($rez);