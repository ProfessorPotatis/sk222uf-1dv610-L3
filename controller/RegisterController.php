<?php

class RegisterController {

    private $db;
    private $session;
    private $request;
    private $validator;
    private $registerView;

    private $requestUsername;
    private $requestPassword;
    private $requestRegister;

    private $username;
    private $password;
    private $passwordRepeat;
    private $message = '';
    
    public function __construct() {
        require($_SERVER['DOCUMENT_ROOT'] . '/sk222uf-1dv610-L3/model/DBConfig.php');

        $this->db = new Database($db_host, $db_user, $db_password, $db_name);
        $this->session = new Session();
        $this->request = new Request();
        $this->validator = new FormValidator();
        $this->registerView = new RegisterView();

        $this->requestUsername = $this->registerView->getRequestUserName();
        $this->requestPassword = $this->registerView->getRequestPassword();
        $this->requestRegister = $this->registerView->getRequestRegister();
    }

    public function handleUserRequest() {
        $thereIsAMessage = $this->session->sessionVariableIsSet('message');

        if ($_POST) {
            $successfullyRegistered = $this->handleRegisterRequest();
            $this->redirect($successfullyRegistered);
        } else if ($thereIsAMessage) {
            $this->setMessage();
            $this->setUsername();
        } else {
            $this->message = '';
        }
    }

    private function handleRegisterRequest() : bool {
        $registerIsSet = $this->request->requestVariableIsSet($this->requestRegister);

        if ($registerIsSet) {
            $userRegistered = $this->registerUser();
            return $userRegistered;
        } else {
            $this->message = '';
        }
        return false;
    }

    private function registerUser() : bool {
        $inputIsValid = $this->validator->validateInputFields();

        if ($inputIsValid) {
            $newUsername = $this->request->getRequestVariable($this->requestUsername);
            $newPassword = $this->request->getRequestVariable($this->requestPassword);

            $userExist = $this->userExist($newUsername);
    
            if ($userExist == false) {
                $this->db->addUser($newUsername, $newPassword);
                $this->session->setSessionVariable('username', $newUsername);
                $this->session->setSessionVariable('message', 'Registered new user.');
                return true;
            } else {
                $this->session->setSessionVariable('username', $newUsername);
                $this->session->setSessionVariable('message', 'User exists, pick another username.');
            }
        }
        return false;
    }

    private function userExist(string $newUsername) : bool {
        $userExist = $this->db->checkIfUserExist($newUsername);
        return $userExist;
    }

    private function redirect(bool $successfullyRegistered) {
        if ($successfullyRegistered) {
            $this->redirectToLogin();
        } else {
            $this->redirectToSelf();
        }
    }

    private function redirectToLogin() {
        header('Location: ' . $_SERVER['PHP_SELF']);
    }

    private function redirectToSelf() {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?register');
    }

    private function setMessage() {
        $this->message = $this->session->getSessionVariable('message');
        $this->session->unsetSessionVariable('message');
    }

    private function setUsername() {
        if ($this->session->sessionVariableIsSet('username')) {
            $this->username = $this->session->getSessionVariable('username');
            $this->session->unsetSessionVariable('username');
        }
    }

    public function getMessage() : string {
        return $this->message;
    }

    public function getUsername() {
        return $this->username;
    }
}