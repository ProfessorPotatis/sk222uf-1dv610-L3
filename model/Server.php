<?php

class Server {
    public function getServerVariable(string $serverVariable) : string {
        if ($this->serverVariableIsSet($serverVariable)) {
            return $_SERVER[$serverVariable];
        }
    }

    public function serverVariableIsSet(string $serverVariable) : bool {
        return isset($_SERVER[$serverVariable]);
    }
}