<?php

class Session {
    private static $loggedIn = 'loggedIn';
    
    public function __construct() {
        $this->startSession();
    }

    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            ini_set('session.use_only_cookies', true);				
            ini_set('session.use_trans_sid', false);
            session_start();
        }
    }

    public function regenerateSessionId() {
        session_regenerate_id();
    }

    public function setSessionVariable(string $name, $value) {
        $_SESSION[$name] = $value;
    }

    public function getSessionVariable(string $sessionVariable) {
        if ($this->sessionVariableIsSet($sessionVariable)) {
            return $_SESSION[$sessionVariable];
        }
    }

    public function unsetSessionVariable(string $sessionVariable) {
        if ($this->sessionVariableIsSet($sessionVariable)) {
            unset($_SESSION[$sessionVariable]);
        }
    }

    public function sessionVariableIsSet(string $sessionVariable) : bool {
        return isset($_SESSION[$sessionVariable]);
    }

    public function isLoggedIn() : bool {
        if ($this->sessionVariableIsSet(self::$loggedIn) && $this->getSessionVariable(self::$loggedIn)) {
            return true;
        } else {
            return false;
        }
    }
}