<?php

class Cookie {
    public function setCookieVariable($name, $value) {
        setcookie($name, $value, time() + (86400 * 30), '/');
    }

    public function getCookieVariable($cookieVariable) {
        if ($this->cookieIsSet($cookieVariable)) {
            return $_COOKIE[$cookieVariable];
        }
    }

    public function unsetCookieVariable($cookieVariable) {
        unset($_COOKIE[$cookieVariable]);
    }

    public function cookieIsSet($cookieVariable) {
        return isset($_COOKIE[$cookieVariable]);
    }
}