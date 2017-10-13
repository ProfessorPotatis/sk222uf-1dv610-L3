<?php

class Database {

    private $db_host;
    private $db_user;
    private $db_password;
    private $db_name;
    private $connection;
    private $passwordIsValid = false;
    private $usernameExist = false;
    private $cookieIsValid = false;

    public function __construct(string $db_host, string $db_user, string $db_password, string $db_name) {
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

    public function addUser(string $newUsername, string $newPassword) {
        $this->connectToDatabase();

        $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = "INSERT INTO Users (username, password)
        VALUES ('" . $newUsername . "', '" . $hashPassword . "')";
        
        if (mysqli_query($this->connection, $sql)) {
            //echo 'New record created successfully';
        } else {
            echo 'Error: ' . $sql . '<br>' . mysqli_error($this->connection);
        }

        $this->disconnect();
    }

    public function checkIfUserExist(string $username) : bool {
        $this->connectToDatabase();
        
        // BINARY makes it case sensitive.
        $query = "SELECT username FROM Users WHERE BINARY username='" . $username . "'";
        
        if ($stmt = mysqli_prepare($this->connection, $query)) {
            mysqli_stmt_execute($stmt);
            
            /* bind result variables */
            mysqli_stmt_bind_result($stmt, $dbUsername);
            
            /* fetch value */
            while (mysqli_stmt_fetch($stmt)) {
                $this->usernameExist = $this->compareUsername($username, $dbUsername);
            }
            
            /* close statement */
            mysqli_stmt_close($stmt);
        }

        $this->disconnect();
        return $this->usernameExist;
    }

    private function compareUsername(string $username, string $dbUsername) : bool {
        if ($username == $dbUsername) {
            return true;
        } else {
            return false;
        }
    }

    public function authenticate(string $username, string $password) : bool {
        $this->connectToDatabase();

        // BINARY makes it case sensitive.
        $query = "SELECT * FROM Users WHERE BINARY username='" . $username . "'";

        if ($stmt = mysqli_prepare($this->connection, $query)) {
            mysqli_stmt_execute($stmt);
            
            /* bind result variables */
            mysqli_stmt_bind_result($stmt, $dbUsername, $dbPassword, $dbCookiePassword);
            
            /* fetch value */
            while (mysqli_stmt_fetch($stmt)) {
                $this->passwordIsValid = $this->verifyPassword($password, $dbPassword);
            }
            
            /* close statement */
            mysqli_stmt_close($stmt);
        }

        $this->disconnect();
        return $this->passwordIsValid;
    }

    private function verifyPassword(string $password, string $dbPassword) : bool {
        if (password_verify($password, $dbPassword)) {
            return true;
        } else {
            return false;
        }
    }

    public function saveUserCookie(string $username, string $cookiePassword) {
        $this->connectToDatabase();
        
        $hashedCookiePassword = password_hash($cookiePassword, PASSWORD_DEFAULT);

        $sql = "UPDATE Users SET cookie='" . $hashedCookiePassword . "' WHERE BINARY username='" . $username . "'";
                
        if (mysqli_query($this->connection, $sql)) {
            //echo 'New cookie record created successfully';
        } else {
            echo 'Error: ' . $sql . '<br>' . mysqli_error($this->connection);
        }
        
        $this->disconnect();
    }

    public function verifyCookie(string $inputUsername, string $inputCookie) : bool {
        $this->connectToDatabase();
        
        // BINARY makes it case sensitive.
        $query = "SELECT * FROM Users WHERE BINARY username='" . $inputUsername . "'";
        
        if ($stmt = mysqli_prepare($this->connection, $query)) {
            mysqli_stmt_execute($stmt);
            
            /* bind result variables */
            mysqli_stmt_bind_result($stmt, $dbUsername, $dbPassword, $dbCookiePassword);
            
            /* fetch value */
            while (mysqli_stmt_fetch($stmt)) {
                $this->cookieIsValid = $this->compareCookie($inputCookie, $dbCookiePassword);
            }
            
            /* close statement */
            mysqli_stmt_close($stmt);
        }

        $this->disconnect();
        return $this->cookieIsValid;
    }

    private function compareCookie(string $inputCookie, string $dbCookiePassword) : bool {
        if (password_verify($inputCookie, $dbCookiePassword)) {
            return true;
        } else {
            return false;
        }
    }
}