<?php
require_once('init.php');
$roomId = $db->getRoomByUser($userId);
$hint = $db->getHint($roomId);
echo $hint;