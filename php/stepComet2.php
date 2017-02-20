<?php
require_once 'access.class.php';
require_once 'Db.php';
$db = new Db ();
$user = new flexibleAccess();
$userId = $user->get_property('userID');
session_write_close();
$roomId = $db->getRoomByUser($userId);
$timestep = $db->getTime($roomId);
usleep(900000);

$maxTime = $db->getMaxTime($roomId);
$rez=[$timestep, $maxTime];
echo json_encode($rez);
