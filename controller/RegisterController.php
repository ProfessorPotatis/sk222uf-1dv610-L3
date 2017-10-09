<?php

class RegisterController {

    private static $registerName = 'RegisterView::UserName';
	private static $registerPassword = 'RegisterView::Password';
	private static $register = 'RegisterView::Register';

    private $db;
    private $session;
    private $post;
    private $request;
    private $validator;

    private $username;
    private $password;
    private $passwordRepeat;
    private $message = '';
    
    public function __construct() {
        require($_SERVER['DOCUMENT_ROOT'] . '/sk222uf-1dv610-L2/model/DBConfig.php');

        $this->db = new Database($db_host, $db_user, $db_password, $db_name);
        $this->session = new Session();
        $this->post = new Post();
        $this->request = new Request();
        $this->validator = new FormValidator();
    }

    public function handleUserRequest() {
        if ($_POST) {
            $successfullyRegistered = $this->handleRegisterRequest();
            if ($successfullyRegistered) {
                $this->username = $this->session->getSessionVariable('username');
                $this->redirectToLogin();
            } else {
                $this->redirectToSelf();
            }
        } else if ($this->session->sessionVariableIsSet('message')) {
            $this->message = $this->session->getSessionVariable('message');
            $this->session->unsetSessionVariable('message');

            if ($this->session->sessionVariableIsSet('username')) {
                $this->username = $this->session->getSessionVariable('username');
                $this->session->unsetSessionVariable('username');
            }
        } else {
            $this->message = '';
        }
    }

    private function handleRegisterRequest() {
        $registerIsSet = $this->post->postVariableIsSet(self::$register);

        if ($registerIsSet) {
            $inputIsValid = $this->validator->validateInputFields();

            if ($inputIsValid) {
                $newUsername = $this->request->getRequestVariable(self::$registerName);
                $newPassword = $this->request->getRequestVariable(self::$registerPassword);

                $userExist = $this->db->checkIfUserExist($newUsername);

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
        } else {
            $this->message = '';
        }
        return false;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getUsername() {
        return $this->username;
    }

    private function redirectToSelf() {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?register');
    }

    private function redirectToLogin() {
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
}