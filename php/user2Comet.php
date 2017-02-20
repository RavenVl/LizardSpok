<?php
require_once 'access.class.php';
require_once('Db.php');

$db = new Db();

//if($_GET['timestamp']==0){
//    echo  json_encode($db->getUserActive());
//    exit;
//}
$user = new flexibleAccess();
$userId = $user->get_property('userID');
session_write_close();
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