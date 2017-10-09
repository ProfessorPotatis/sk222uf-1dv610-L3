<?php

class Server {
    public function getServerVariable($serverVariable) {
        if ($this->serverVariableIsSet($serverVariable)) {
            return $_SERVER[$serverVariable];
        }
    }

    public function serverVariableIsSet($serverVariable) {
        return isset($_SERVER[$serverVariable]);
    }
}