<?php

class Get {
    public function getGetVariable($getVariable) {
        if ($this->getVariableIsSet($getVariable)) {
            return $_GET[$getVariable];
        }
    }

    public function getVariableIsSet($getVariable) {
        return isset($_GET[$getVariable]);
    }
}