<?php

class LoginController {

    private $db;
    private $session;
    private $cookie;
    private $request;
    private $server;
    private $loginView;
    private $requestUsername;
    private $requestPassword;
    private $requestLogout;
    private $requestLogin;
    private $requestKeep;
    private $requestCookieName;
    private $requestCookiePassword;

    private $message = '';
    private $username;
    private $isAuthenticated = false;
    
    public function __construct() {
        require($_SERVER['DOCUMENT_ROOT'] . '/sk222uf-1dv610-L3/model/DBConfig.php');

        $this->db = new Database($db_host, $db_user, $db_password, $db_name);
        $this->session = new Session();
        $this->cookie = new Cookie();
        $this->request = new Request();
        $this->server = new Server();
        $this->loginView = new LoginView();

        $this->requestUsername = $this->loginView->getRequestUserName();
        $this->requestPassword = $this->loginView->getRequestPassword();
        $this->requestLogout = $this->loginView->getRequestLogout();
        $this->requestLogin = $this->loginView->getRequestLogin();
        $this->requestKeep = $this->loginView->getRequestKeep();
        $this->requestCookieName = $this->loginView->getRequestCookieName();
        $this->requestCookiePassword = $this->loginView->getRequestCookiePassword();
    }

    public function getMessage() : string {
        return $this->message;
    }

    public function getUsername() {
        return $this->username;
    }

    public function handleUserRequest() {
        $thereIsAMessage = $this->session->sessionVariableIsSet('message');
        $thereIsACookie = $this->cookie->cookieIsSet($this->requestCookiePassword);
        $userNotLoggedIn = $this->session->isLoggedIn() == false;

        if ($_POST) {
            $this->handleLoginRequest();
            $this->redirectToSelf();
        } else if ($thereIsAMessage) {
            $this->setMessage();
            $this->setUsername();
        } else if ($thereIsACookie && $userNotLoggedIn) {
            $this->checkCookieCredentials();
        } else {
            $this->message = '';
        }
    }

    private function handleLoginRequest() {
        $userWantsToLogOut = $this->request->requestVariableIsSet($this->requestLogout);
        $userWantsToLogIn = $this->request->requestVariableIsSet($this->requestLogin);
        $userNotLoggedIn = $this->session->isLoggedIn() == false;

        if ($userWantsToLogOut) {
            $this->logout();
        } else if ($userWantsToLogIn && $userNotLoggedIn) {
            $postedUsername = $this->request->getRequestVariable($this->requestUsername);
            $this->session->setSessionVariable('username', $postedUsername);
            $this->setUsername();

            $this->validateInputFields();
        } else {
            $this->message = '';
        }
    }

    private function logout() {
        $userIsLoggedIn = $this->session->isLoggedIn();

        if ($userIsLoggedIn) {
            $this->clearCookie($this->requestCookieName);
            $this->clearCookie($this->requestCookiePassword);

            $this->session->unsetSessionVariable('loggedIn');
            $this->session->setSessionVariable('message', 'Bye bye!');
            $this->session->regenerateSessionId();
        } else {
            $this->message = '';
        }
    }

    private function clearCookie($cookie) {
        $this->cookie->unsetCookieVariable($cookie);
    }

    private function validateInputFields() {
        $postedUsername = $this->request->getRequestVariable($this->requestUsername);
        $postedPassword = $this->request->getRequestVariable($this->requestPassword);

        if ($postedUsername == '') {
            $this->session->setSessionVariable('message', 'Username is missing');
        } else if ($postedPassword == '') {
            $this->session->setSessionVariable('username', $postedUsername);
            $this->session->setSessionVariable('message', 'Password is missing');
        } else {
            $this->authenticateUser($postedUsername, $postedPassword);
        }
    }

    private function authenticateUser($postedUsername, $postedPassword) {
        $this->isAuthenticated = $this->db->authenticate($postedUsername, $postedPassword);

        if ($this->isAuthenticated) {
            $httpUserAgent = $this->server->getServerVariable('HTTP_USER_AGENT');

            $this->session->regenerateSessionId();
            $this->session->setSessionVariable('user_agent', $httpUserAgent);
            $this->session->setSessionVariable('loggedIn', true);
            $this->session->setSessionVariable('message', 'Welcome');
            
            $this->keepLoggedIn();
        } else {
            $this->session->setSessionVariable('username', $postedUsername);
            $this->session->setSessionVariable('message', 'Wrong name or password');
        }
    }

    private function keepLoggedIn() {
        $checkedKeepLoggedInBox = $this->request->requestVariableIsSet($this->requestKeep);

        if ($checkedKeepLoggedInBox) {
            $this->session->setSessionVariable('message', 'Welcome and you will be remembered');
            $this->setCookies();
        }
    }

    private function setCookies() {
        $postedUsername = $this->request->getRequestVariable($this->requestUsername);
        $randomStr = uniqid();

        $this->cookie->setCookieVariable($this->requestCookieName, $postedUsername);
        $this->cookie->setCookieVariable($this->requestCookiePassword, $randomStr);

        $this->saveCookiesToDatabase($postedUsername, $randomStr);
    }

    private function saveCookiesToDatabase($postedUsername, $randomStr) {
        $this->db->saveUserCookie($postedUsername, $randomStr);
    }

    private function redirectToSelf() {
        header('Location: ' . $_SERVER['PHP_SELF']);
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

    private function checkCookieCredentials() {
        $cookieUsername = $this->cookie->getCookieVariable($this->requestCookieName);
        $cookiePw = $this->cookie->getCookieVariable($this->requestCookiePassword);

        if ($this->db->verifyCookie($cookieUsername, $cookiePw)) {
            $this->session->setSessionVariable('message', 'Welcome back with cookie');
            $this->session->setSessionVariable('loggedIn', true);
            $this->message = $this->session->getSessionVariable('message');
            $this->session->unsetSessionVariable('message');
        } else {
            $this->session->setSessionVariable('message', 'Wrong information in cookies');
            $this->session->unsetSessionVariable('loggedIn');
            $this->clearCookies();
            $this->redirectToSelf();
        }
    }
}