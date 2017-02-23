<?php

class flexibleAccess
{
    /*Settings*/

    var $dbName = 'spok';

    var $dbHost = 'localhost';

    var $dbPort = 3306;

    var $dbUser = 'root';

    var $dbPass = '';

    var $dbTable = 'users';

    var $sessionVariable = 'userSessionValue';

    var $tbFields = array(
        'userID' => 'userID',
        'login' => 'username',
        'pass' => 'password',
        'email' => 'email',
        'active' => 'active'
    );

    var $remTime = 2592000;//One month

    var $remCookieName = 'ckSavePass';

    var $remCookieDomain = 'longpool.local';

    var $passMethod = '';

    var $displayErrors = true;

    var $userID;
    var $dbConn;
    var $userData = array();


    function flexibleAccess($dbConn = '', $settings = '')
    {
        if (is_array($settings)) {
            foreach ($settings as $k => $v) {
                if (!isset($this->{$k})) die('Property ' . $k . ' does not exists. Check your settings.');
                $this->{$k} = $v;
            }
        }
        $this->remCookieDomain = $this->remCookieDomain == '' ? $_SERVER['HTTP_HOST'] : $this->remCookieDomain;
        $this->dbConn = ($dbConn == '') ? mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName, $this->dbPort) : $dbConn;
        if (!$this->dbConn) die(mysqli_error($this->dbConn));
        mysqli_select_db($this->dbConn, $this->dbName) or die(mysqli_error($this->dbConn));
        if (!isset($_SESSION)) session_start();
        if (!empty($_SESSION[$this->sessionVariable])) {
            $this->loadUser($_SESSION[$this->sessionVariable]);
        }
        //Maybe there is a cookie?
        if (isset($_COOKIE[$this->remCookieName]) && !$this->is_loaded()) {
            //echo 'I know you<br />';
            $u = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
            $this->login($u['uname'], $u['password']);
        }

    }


    function login($uname, $password, $remember = false, $loadUser = true)
    {
        $uname = $this->escape($uname);
        $password = $originalPassword = $this->escape($password);
        switch (strtolower($this->passMethod)) {
            case 'sha1':
                $password = "SHA1('$password')";
                break;
            case 'md5' :
                $password = "MD5('$password')";
                break;
            case 'nothing':
                $password = "'$password'";
        }
        $res = $this->query("SELECT * FROM `{$this->dbTable}` 
		WHERE `{$this->tbFields['login']}` = '$uname' AND `{$this->tbFields['pass']}` = $password LIMIT 1", __LINE__);
        if (@mysqli_num_rows($res) == 0)
            return false;
        if ($loadUser) {
            $this->userData = mysqli_fetch_array($res);
            $this->userID = $this->userData[$this->tbFields['userID']];
            $_SESSION[$this->sessionVariable] = $this->userID;
            if ($remember) {
                $cookie = base64_encode(serialize(array('uname' => $uname, 'password' => $originalPassword)));
                $a = setcookie($this->remCookieName,
                    $cookie, time() + $this->remTime, '/', $this->remCookieDomain);

            }
        }
        $this->activate();
        return true;
    }

    function logout($redirectTo = '')
    {
        setcookie($this->remCookieName, '', time() - 3600);
        $_SESSION[$this->sessionVariable] = '';
        $this->userData = '';
        $this->deactivate();
        if ($redirectTo != '' && !headers_sent()) {
            header('Location: ' . $redirectTo);
            exit;
        }
    }

    function is($prop)
    {
        return $this->get_property($prop) == 1 ? true : false;
    }


    function get_property($property)
    {
        if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
        if (!isset($this->userData[$property])) $this->error('Unknown property <b>' . $property . '</b>', __LINE__);
        return $this->userData[$property];
    }

    function is_active()
    {
        return $this->userData[$this->tbFields['active']];
    }


    function is_loaded()
    {
        return empty($this->userID) ? false : true;
    }

    function activate()
    {
        if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
        if ($this->is_active()) $this->error('Allready active account', __LINE__);
        $res = $this->query("UPDATE `{$this->dbTable}` SET {$this->tbFields['active']} = 1 
	WHERE `{$this->tbFields['userID']}` = '" . $this->escape($this->userID) . "' LIMIT 1");
        if (@mysqli_affected_rows() == 1) {
            $this->userData[$this->tbFields['active']] = true;
            return true;
        }
        return false;
    }

    function deactivate()
    {
        if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
        //if ( $this->is_active()) $this->error('Allready active account', __LINE__);
        $res = $this->query("UPDATE `{$this->dbTable}` SET {$this->tbFields['active']} = 0 
	WHERE `{$this->tbFields['userID']}` = '" . $this->escape($this->userID) . "' LIMIT 1");
        if (@mysqli_affected_rows() == 1) {
            $this->userData[$this->tbFields['active']] = false;
            return true;
        }
        return false;
    }

    function insertUser($data)
    {
        if (!is_array($data)) $this->error('Data is not an array', __LINE__);
        switch (strtolower($this->passMethod)) {
            case 'sha1':
                $password = "SHA1('" . $data[$this->tbFields['pass']] . "')";
                break;
            case 'md5' :
                $password = "MD5('" . $data[$this->tbFields['pass']] . "')";
                break;
            case 'nothing':
                $password = $data[$this->tbFields['pass']];
        }
        foreach ($data as $k => $v) $data[$k] = "'" . $this->escape($v) . "'";
        $data[$this->tbFields['pass']] = $password;
        $this->query("INSERT INTO `{$this->dbTable}` (`" . implode('`, `', array_keys($data)) . "`) VALUES (" . implode(", ", $data) . ")");
        return (int)mysqli_insert_id($this->dbConn);
    }

    function randomPass($length = 10, $chrs = '1234567890qwertyuiopasdfghjklzxcvbnm')
    {

        $pwd = '';
        for ($i = 0; $i < $length; $i++) {
            $pwd .= $chrs{mt_rand(0, strlen($chrs) - 1)};
        }
        return $pwd;
    }
    ////////////////////////////////////////////
    // PRIVATE FUNCTIONS
    ////////////////////////////////////////////


    function query($sql, $line = 'Uknown')
    {
        //if (defined('DEVELOPMENT_MODE') ) echo '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
        //$res = mysql_db_query($this->dbName, $sql, $this->dbConn);
        mysqli_select_db($this->dbConn, $this->dbName);
        $res = mysqli_query($this->dbConn, $sql);
        if (!res)
            $this->error(mysqli_error($this->dbConn), $line);
        return $res;
    }


    function loadUser($userID)
    {
        $res = $this->query("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['userID']}` = '" . $this->escape($userID) . "' LIMIT 1");
        if (mysqli_num_rows($res) == 0)
            return false;
        $this->userData = mysqli_fetch_array($res);
        $this->userID = $userID;
        $_SESSION[$this->sessionVariable] = $this->userID;
        return true;
    }


    function escape($str)
    {
        $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
        $str = mysqli_real_escape_string($this->dbConn, $str);
        return $str;
    }


    function error($error, $line = '', $die = false)
    {
        if ($this->displayErrors)
            echo '<b>Error: </b>' . $error . '<br /><b>Line: </b>' . ($line == '' ? 'Unknown' : $line) . '<br />';
        if ($die) exit;
        return false;
    }
}

?>