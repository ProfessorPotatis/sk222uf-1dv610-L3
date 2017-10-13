<?php

class Cookie {
    public function setCookieVariable(string $name, string $value) {
        setcookie($name, $value, time() + (86400 * 30), '/');
    }

    public function getCookieVariable(string $cookieVariable) {
        if ($this->cookieIsSet($cookieVariable)) {
            return $_COOKIE[$cookieVariable];
        }
    }

    public function unsetCookieVariable(string $cookieVariable) {
        if ($this->cookieIsSet($cookieVariable)) {
            unset($_COOKIE[$cookieVariable]);
            setcookie($cookieVariable, '', time() - 3600, '/'); // empty value and old timestamp, to delete cookie
        }
    }

    public function cookieIsSet(string $cookieVariable) : bool {
        return isset($_COOKIE[$cookieVariable]);
    }
}