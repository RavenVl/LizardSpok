<?php
require_once('Db.php');

$db = new Db();

if ($_GET['timestamp'] == 0) {
    echo json_encode($db->getRoom());
    exit;
}

$rezOld = $db->getRoom();
$rezNew = $db->getRoom();


while (strcmp($rezNew, $rezOld) == 0) {
    usleep(10000);
    clearstatcache();

    $rezNew = $db->getRoom();

}


echo json_encode($rezNew);