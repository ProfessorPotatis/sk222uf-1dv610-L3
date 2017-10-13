<?php

class Get {
    public function getGetVariable(string $getVariable) : string {
        if ($this->getVariableIsSet($getVariable)) {
            return $_GET[$getVariable];
        }
    }

    public function getVariableIsSet(string $getVariable) : bool {
        return isset($_GET[$getVariable]);
    }
}