<?php
require_once('Db.php');

$db = new Db();

if ($_GET['timestamp'] == 0) {
    echo json_encode($db->getUserActive());
    exit;
}

$rezOld = $db->getUserActive();
$rezNew = $db->getUserActive();


while (strcmp($rezNew, $rezOld) == 0) {
    usleep(10000);
    clearstatcache();

    $rezNew = $db->getUserActive();

}


echo json_encode($rezNew);
