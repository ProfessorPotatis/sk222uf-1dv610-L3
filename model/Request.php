<?php

class Request {
    public function getRequestVariable(string $requestVariable) : string {
        if ($this->requestVariableIsSet($requestVariable)) {
            return $_REQUEST[$requestVariable];
        }
    }

    public function requestVariableIsSet(string $requestVariable) : bool {
        return isset($_REQUEST[$requestVariable]);
    }
}