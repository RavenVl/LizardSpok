<?php
require_once 'init.php';
$playerId = $_GET['player'];
$roomId = $db->getRoomByUser($userId);
$figur = $_GET['figur'];

if ($playerId == 1) {
    $db->setPl1($roomId, $figur);
} else {
    $db->setPl2($roomId, $figur);
}
//echo json_encode("1");
