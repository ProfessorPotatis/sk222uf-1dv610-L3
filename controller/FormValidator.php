<?php

class FormValidator {
    
    private $session;
    private $request;
    private $registerView;
    
    private $requestUsername;
    private $requestPassword;
    private $requestRegister;
    
    private $username;
    private $password;
    private $repeatPassword;
    private $errorCounter = 0;

    public function __construct() {
        $this->session = new Session();
        $this->request = new Request();
        $this->registerView = new RegisterView();

        $this->requestUsername = $this->registerView->getRequestUserName();
        $this->requestPassword = $this->registerView->getRequestPassword();
        $this->requestRepeatPassword = $this->registerView->getRequestRepeatPassword();
    }

    public function validateInputFields() {
        $this->username = $this->request->getRequestVariable($this->requestUsername);
        $this->password = $this->request->getRequestVariable($this->requestPassword);
        $this->repeatPassword = $this->request->getRequestVariable($this->requestRepeatPassword);

        $this->checkPasswordLength();
        $this->checkUsernameLength();
        $this->emptyInputFields();
        $this->comparePasswords();
        $this->invalidCharUsername();

        if ($this->errorCounter > 0) {
            return false;
        } else {
            return true;
        }
    }

    private function checkPasswordLength() {
        if (strlen($this->password) < 6) {
            $this->session->setSessionVariable('username', $this->username);
            $this->session->setSessionVariable('message', 'Password has too few characters, at least 6 characters.');
            $this->errorCounter = + 1;
        }
    }

    private function checkUsernameLength() {
        if (strlen($this->username) < 3) {
            $this->session->setSessionVariable('username', $this->username);
            $this->session->setSessionVariable('message', 'Username has too few characters, at least 3 characters.');
            $this->errorCounter = + 1;
        }
    }

    private function emptyInputFields() {
        if (strlen($this->username) == 0 && strlen($this->password) == 0) {
            $this->session->setSessionVariable('message', 'Username has too few characters, at least 3 characters. Password has too few characters, at least 6 characters.');
            $this->errorCounter = + 1;
        }
    }

    private function comparePasswords() {
        if ($this->repeatPassword !== $this->password) {
            $this->session->setSessionVariable('username', $this->username);
            $this->session->setSessionVariable('message', 'Passwords do not match.');
            $this->errorCounter = + 1;
        }
    }

    private function invalidCharUsername() {
        if (strip_tags($this->username) !== $this->username) {
            $this->session->setSessionVariable('username', strip_tags($this->username));
            $this->session->setSessionVariable('message', 'Username contains invalid characters.');
            $this->errorCounter = + 1;
        }
    }
}
