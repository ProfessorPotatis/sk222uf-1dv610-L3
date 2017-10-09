<?php

class Post {
    public function getPostVariable($postVariable) {
        if ($this->postVariableIsSet($postVariable)) {
            return $_POST[$postVariable];
        }
    }

    public function postVariableIsSet($postVariable) {
        return isset($_POST[$postVariable]);
    }
}