<?php
$name = $_POST['name'];
$pass = $_POST['pass'];
if($name != '1' && $pass != '2'){
    header('Location: error.php');exit;
}
else
{
    header('Location: game.php');exit;

}