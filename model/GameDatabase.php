<?php

class GameDatabase {

    private $db_host;
    private $db_user;
    private $db_password;
    private $db_name;
    private $connection;

    private $userExist = false;
    private $posLeft;
    private $posTop;

    public function __construct($db_host, $db_user, $db_password, $db_name) {
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_password = $db_password;
        $this->db_name = $db_name;
    }

    private function connectToDatabase() {
        $this->connection = mysqli_connect($this->db_host, $this->db_user, $this->db_password, $this->db_name);

        $this->checkConnection();
    }

    private function checkConnection() {
        if (!$this->connection) {
            die('Connection failed: ' . mysqli_connect_error());
        } else {
            //echo 'Connected successfully';
        }
    }

    private function disconnect() {
        mysqli_close($this->connection);
    }

    public function addDinoPosition($user, $posLeft, $posTop) {
        $this->connectToDatabase();

        $sql = "INSERT INTO Position (user, posLeft, posTop)
        VALUES ('" . $user . "', '" . $posLeft . "', '" . $posTop . "')";
        
        if (mysqli_query($this->connection, $sql)) {
            //echo 'New record created successfully';
        } else {
            echo 'Error: ' . $sql . '<br>' . mysqli_error($this->connection);
        }

        $this->disconnect();
    }

    public function checkIfUserExist($user) {
        $this->connectToDatabase();
        
        // BINARY makes it case sensitive.
        $query = "SELECT user FROM Position WHERE BINARY user='" . $user . "'";
        
        if ($stmt = mysqli_prepare($this->connection, $query)) {
            mysqli_stmt_execute($stmt);
            
            /* bind result variables */
            mysqli_stmt_bind_result($stmt, $dbUser);
            
            /* fetch value */
            while (mysqli_stmt_fetch($stmt)) {
                $this->userExist = $this->compareUser($user, $dbUser);
            }
            
            /* close statement */
            mysqli_stmt_close($stmt);
        }

        $this->disconnect();
        return $this->userExist;
    }

    private function compareUser($user, $dbUser) {
        if ($user == $dbUser) {
            return true;
        } else {
            return false;
        }
    }

    public function updateDinoPosition($user, $posLeft, $posTop) {
        $this->connectToDatabase();

        $sql = "UPDATE Position SET user='" . $user . "',posLeft='" . $posLeft . "',posTop='" . $posTop . "' WHERE BINARY user='" . $user . "'";
        
        if (mysqli_query($this->connection, $sql)) {
            //echo 'New record created successfully';
        } else {
            echo 'Error: ' . $sql . '<br>' . mysqli_error($this->connection);
        }

        $this->disconnect();
    }

    public function getCurrentDinoPosition($user) {
        $this->connectToDatabase();
        
        // BINARY makes it case sensitive.
        $query = "SELECT posLeft, posTop FROM Position WHERE BINARY user='" . $user . "'";
        
        if ($stmt = mysqli_prepare($this->connection, $query)) {
            mysqli_stmt_execute($stmt);
            
            /* bind result variables */
            mysqli_stmt_bind_result($stmt, $dbPosLeft, $dbPosTop);
            
            /* fetch value */
            while (mysqli_stmt_fetch($stmt)) {
                $this->posLeft = $dbPosLeft;
                $this->posTop = $dbPosTop;
            }
            
            /* close statement */
            mysqli_stmt_close($stmt);
        }

        $this->disconnect();
        $position = array($this->posLeft, $this->posTop);
        return $position;
    }
}