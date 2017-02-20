<?php

class Db
{
    public $pdo;
    function __construct()
    {
        $dsn = 'mysql:dbname=spok;host=127.0.0.1';
        $user = 'root';
        $password = '';

        try {
            $this->pdo = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }

    }

    function setItogStep($roomId){
       
        $masItog = $this->getItogStep($roomId);
        $pl1 = $masItog[0];
        $pl2 = $masItog[1];
        $itog = $masItog[2];

        //set itog
        $curStep = $this->getCurStep($roomId);
        $sql = "UPDATE games SET itog =" . $itog. " WHERE IDroom = " . $roomId . " AND step=" . $curStep;
        $result=$this->pdo->exec($sql);
        $masItog ="The Player1 made a move figure $pl1 , The Player2 made a move figure $pl2, Win Player$itog";
        return $masItog;

    }

    function getItogStep($roomId){
        $sql = "SELECT * FROM `games` WHERE IDroom = " . $roomId ." order BY step DESC LIMIT 1";
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $pl1 = $row->waypl1;
        $pl2 = $row->waypl2;
        $itog = "0";
        // get itog

        if($pl1==0){
            $itog = '2';
        }
        elseif ($pl2==0){
            $itog = '1';
        }
        else{
            $sql = "SELECT * FROM `gamecalc` WHERE pl1 = " . $pl1 ." AND pl2 = " .$pl2;
            $stmt = $this->pdo->query($sql);
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            $itog = $row->itog;
        }
        $rez = [$pl1, $pl2, $itog];
        return $rez;
    }
    
    function getAllGames($roomID){

        $sql = "SELECT * FROM games WHERE IDroom = $roomID AND itog > 0 order BY step";
        $stmt = $this->pdo->query($sql);
        //$row = $stmt->fetch(PDO::FETCH_OBJ);
        $data='';
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $data .=$row->step . " " . $row->waypl1 ." ". $row->waypl2 ." ". $row->itog . ";";
        }
        $stmt = null;

        return $data;
    }

    function setPl1($roomID, $figur){
        $curStep = $this->getCurStep($roomID);
        $sql = "UPDATE games SET waypl1 =" . $figur. " WHERE IDroom = " . $roomID . " AND step=" . $curStep;
        $result=$this->pdo->exec($sql);
        //echo $result;

    }

    function setPl2($roomID, $figur){
        $curStep = $this->getCurStep($roomID);
        $sql = "UPDATE games SET waypl2 =" . $figur. " WHERE IDroom = " . $roomID . " AND step=" . $curStep;
        $result=$this->pdo->exec($sql);
        //echo $result;

    }

    function getCurStep($roomId){
        $sql = "SELECT * FROM `games` WHERE IDroom = " . $roomId ." order BY step DESC LIMIT 1";
        //SELECT count(IDroom) FROM games where IDroom=1 group BY IDroom
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $date = $row->step;

        return $date;
    }

    function nextStepGames($roomId){

        $curStep = $this->getCurStep($roomId)+1;
        $sql = "INSERT INTO `games` (`ID`, `IDroom`, `waypl1`, `waypl2`, `step`, `itog`) VALUES (NULL," . $roomId. ", 0, 0, " . $curStep.", NULL);";
        $result=$this->pdo->exec($sql);
        //echo $result;

    }

    function getUserActive(){
        $sql = "select * from users where active=1";
        $stmt = $this->pdo->query($sql);
        //$row= $stmt->fetch();
        $data='';
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $data .= $row->username . ";";
        }
        $stmt = null;
        
        return $data;
    }


    function getRoom(){
        $sql = "SELECT r.id,r.name, u1.username as username1, u2.username as username2, g.name as status  FROM `rooms` as r 
                left JOIN users as u1 ON  r.IDplayer1 = u1.userID
                left JOIN users as u2 ON  r.IDplayer2 = u2.userID
                left JOIN gamestatus as g ON  r.IDstatus = g.ID";
        $stmt = $this->pdo->query($sql);
        $data='';
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $data .=$row->id . " " . $row->name ." ". $row->username1." ".$row->username2." ".$row->status. ";";
        }
        $stmt = null;

        return $data;
    }

    function getRoomByUser($userId){
        $sql = "SELECT * FROM `rooms` WHERE IDplayer1 =". $userId. " OR  IDplayer2 =" . $userId ;
        $stmt = $this->pdo->query($sql);
        $data='';
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $data .= $row->ID;
        }
        $stmt = null;
        //echo 'data='.$data.'\n';
        return $data;
    }



    function getRoomById($roomId){
        $sql = "SELECT * FROM `rooms` WHERE ID=". $roomId;
        $stmt = $this->pdo->query($sql);
        $data='';
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $data .= $row->ID .' '. $row->IDplayer1 .' '. $row->IDplayer2.' '. $row->name . ' ' . $row->IDstatus;
        }
        $stmt = null;
        //echo 'data='.$data.'\n';
        return $data;
    }

    function getMaxTime($roomId){
        $sql = "SELECT * FROM `rooms` WHERE ID=". $roomId;
        $stmt = $this->pdo->query($sql);
        $data='';
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $data = $row->maxtime;
        $stmt = null;
        //echo 'data='.$data.'\n';
        return $data;
    }

    function createRoom($userId, $roomname, $maxtime){
        $sql="INSERT INTO rooms (ID, name, IDplayer1, IDplayer2, IDstatus, maxtime) VALUES (NULL, '$roomname' , $userId, null, 1, $maxtime);";
       // $sql="INSERT INTO rooms (ID, name, IDplayer1, IDplayer2, IDstatus) VALUES (NULL, '123', '1', NULL, '1');";
        echo $sql;
        $result=$this->pdo->exec($sql);
        //echo $result;
    }

    function connectRoom($userId, $roomID){
        $sql="UPDATE rooms SET IDplayer2 =". $userId. ", IDstatus=2 WHERE ID =".$roomID;
        $result=$this->pdo->exec($sql);
        //echo $result;
    }

    function deleteRoom($userId){
         $sql="DELETE FROM rooms WHERE idPlayer1=" . $userId;
        //$sql="DELETE FROM rooms WHERE idPlayer1=1";
        $result=$this->pdo->exec($sql);
        //echo $result;
    }

    function deleteGames($roomID){
        $sql="DELETE FROM games WHERE IDroom=" . $roomID;
        //delete from games where IDroom=15
        $result=$this->pdo->exec($sql);
    }
    
    function diconectRoom($userId){
        $sql="UPDATE rooms SET IDplayer2 =". "NULL" . ", IDstatus=1 WHERE IDplayer2 =".$userId;
        $result=$this->pdo->exec($sql);
        //echo $result;
    }

    function createTime($roomId){

        $sql = "INSERT INTO `time` (`ID`, `IDroom`, `gametime`) VALUES (NULL, $roomId, '0');";
        $result=$this->pdo->exec($sql);
        //echo $result;
    }

    function setTime($roomID, $time){
        $sql="UPDATE time SET gametime = $time WHERE IDroom = $roomID;";
        $result=$this->pdo->exec($sql);
        //echo $result;
    }

    function deleteTime($roomID){
        $sql ="DELETE FROM `time` WHERE IDroom=$roomID;";
        $result=$this->pdo->exec($sql);
        //echo $result
    }

    function getTime($roomID){
        $sql="SELECT * FROM time WHERE IDroom=$roomID";
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $date = $row->gametime;

        return $date;

    }

    function getItog($roomID){
        $sql = "SELECT COUNT(itog) as it FROM `games` WHERE IDroom=$roomID and itog=1 group BY itog";
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $itogPl1 = $row->it;

        $sql = "SELECT COUNT(itog) as it FROM `games` WHERE IDroom=$roomID and itog=2 group BY itog";
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $itogPl2 = $row->it;

        if($itogPl1>=3 or $itogPl2>=3){
            if($itogPl1 > $itogPl2) return 1;
            else return 2;
        }
        return 0;
    }
}
