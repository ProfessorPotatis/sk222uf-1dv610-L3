<?php

class Request {
    public function getRequestVariable($requestVariable) {
        if ($this->requestVariableIsSet($requestVariable)) {
            return $_REQUEST[$requestVariable];
        }
    }

    public function requestVariableIsSet($requestVariable) {
        return isset($_REQUEST[$requestVariable]);
    }
}