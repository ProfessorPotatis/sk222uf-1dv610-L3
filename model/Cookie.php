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
        }
    }

    public function cookieIsSet(string $cookieVariable) : bool {
        return isset($_COOKIE[$cookieVariable]);
    }

    public function deleteCookie(string $cookie) {
        if ($this->cookieIsSet($cookie)) {
            $this->unsetCookieVariable($cookie);
            setcookie($cookie, '', time() - 3600, '/'); // empty value and old timestamp, to delete cookie
        }
    }
}