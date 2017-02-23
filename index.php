<?
require_once 'php/access.class.php';
$user = new flexibleAccess();
if ($_GET['logout'] == 1)
    //$user->logout('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
    $user->logout('http://longpool.local/');
if (!$user->is_loaded()) {
    //Login stuff:
    if (isset($_POST['uname']) && isset($_POST['pwd'])) {
        if (!$user->login($_POST['uname'], $_POST['pwd'], $_POST['remember'])) {
            echo 'Wrong username and/or password';
        } else {
            //user is now loaded
            header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        }
    } ?>
    <head>
        <link rel="stylesheet" href="/css/stylein.css">
    </head>
    <h1 class="name">Login</h1>
    <div class="enter">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"/>
        <h3>User</h3>
        <input class="uname" type="text" name="uname"/>
        <h3>Password</h3>
        <input class="pwd" type="password" name="pwd"/>
        <!--	  Remember me? <input type="checkbox" name="remember" value="1" /><br /><br />-->
        <input class="login" type="submit" value="login"/>
        </form>

    </div>

    <?php
} else {
    //User is loaded
    ?>
    <head>
        <link rel="stylesheet" href="/css/stylein.css">
    </head>
    <h2>Welcome!</h2>
    <main>
        <div class="link-box">
            <a href="http://longpool.local/index.php?logout=1">Logout</a>
        </div>
        <div class="link-box">
            <a href="php/game.php">Game</a>
        </div>
    </main>


    <?php
//	header('Location: game.php');
//	exit;
}
?>
