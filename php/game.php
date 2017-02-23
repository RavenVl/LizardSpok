<?php
require_once 'init.php';

if ($_POST['idBack'] == 1) {
    $db->deleteRoom($userId);

}

if ($_POST['idBack2'] == 1) {
    $db->diconectRoom($userId);
}

if ($_POST['idBack'] == 3) {
    $db->deleteRoom($userId);
    $roomId = $_POST['room'];
    $db->deleteGames($roomId);
    $db->deleteTime($roomId);
}

include '../html/game.html';

