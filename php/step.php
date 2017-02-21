<?php
require_once 'access.class.php';
require_once 'Db.php';
$db = new Db ();
$user = new flexibleAccess();
$userId = $user->get_property('userID');
session_write_close();

$playerId = $_GET['player'];
$roomId = $db->getRoomByUser($userId);
$figur = $_GET['figur'];

if ($playerId == 1){
  $db->setPl1 ($roomId, $figur);
}
else{
    $db->setPl2 ($roomId, $figur);
}
//echo json_encode("1");
