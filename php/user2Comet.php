<?php
require_once 'init.php';
$roomId =$db->getRoomByUser($userId);
$rezOld = $db->getRoomById($roomId);
$rezNew = $db->getRoomById($roomId);
while (strcmp($rezNew, $rezOld)==0)
{
    usleep(10000);
    clearstatcache();

    $rezNew = $db->getRoomById($roomId);

}
echo json_encode($rezNew);