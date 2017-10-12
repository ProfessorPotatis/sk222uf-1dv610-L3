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

    public function getMessage() {
        return $this->message;
    }

    public function getUsername() {
        return $this->username;
    }

    public function handleUserRequest() {
        if ($_POST) {
            $this->handleLoginRequest();
            $this->redirectToSelf();
        } else if ($this->session->sessionVariableIsSet('message')) {
            $this->message = $this->session->getSessionVariable('message');
            $this->session->unsetSessionVariable('message');

            if ($this->session->sessionVariableIsSet('username')) {
                $this->username = $this->session->getSessionVariable('username');
                $this->session->unsetSessionVariable('username');
            }
        } else if ($this->cookie->cookieIsSet($this->requestCookiePassword) && $this->session->isLoggedIn() == false) {
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
        } else {
            $this->message = '';
        }
    }

    private function handleLoginRequest() {
        if ($this->request->requestVariableIsSet($this->requestLogout)) {
            $this->logout();
        } else if ($this->request->requestVariableIsSet($this->requestLogin) && $this->session->isLoggedIn() == false) {
            $postedUsername = $this->request->getRequestVariable($this->requestUsername);
            $this->session->setSessionVariable('username', $postedUsername);
            $this->username = $this->session->getSessionVariable('username');
            $this->validateInputFields();
        } else {
            $this->message = '';
        }
    }

    private function logout() {
        if ($this->session->isLoggedIn()) {
            $this->clearCookies();
            $this->session->unsetSessionVariable('loggedIn');
            $this->session->setSessionVariable('message', 'Bye bye!');
        } else {
            $this->message = '';
        }
    }

    private function clearCookies() {
        $userCookieIsSet = $this->cookie->cookieIsSet($this->requestCookieName);
        $userCookie = $this->cookie->getCookieVariable($this->requestCookieName);

        $passwordCookieIsSet = $this->cookie->cookieIsSet($this->requestCookiePassword);
        $passwordCookie = $this->cookie->getCookieVariable($this->requestCookiePassword);

        if ($userCookieIsSet) {
            $this->cookie->unsetCookieVariable($userCookie);
            setcookie($this->requestCookieName, '', time() - 3600, '/'); // empty value and old timestamp, to delete cookie
        }

        if ($passwordCookieIsSet) {
            $this->cookie->unsetCookieVariable($passwordCookie);
            setcookie($this->requestCookiePassword, '', time() - 3600, '/'); // empty value and old timestamp, to delete cookie
        }
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
            $this->authenticateUser();
        }
    }

    private function authenticateUser() {
        $postedUsername = $this->request->getRequestVariable($this->requestUsername);
        $postedPassword = $this->request->getRequestVariable($this->requestPassword);

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
        if ($this->request->requestVariableIsSet($this->requestKeep)) {
            $this->session->setSessionVariable('message', 'Welcome and you will be remembered');
            $this->setCookies();
        }
    }

    private function setCookies() {
        $postedUsername = $this->request->getRequestVariable($this->requestUsername);
        $randomStr = uniqid();

        $this->cookie->setCookieVariable($this->requestCookieName, $postedUsername);
        $this->cookie->setCookieVariable($this->requestCookiePassword, $randomStr);

        $this->saveCookiesToDatabase($randomStr);
    }

    private function saveCookiesToDatabase($randomStr) {
        $postedUsername = $this->request->getRequestVariable($this->requestUsername);

        $this->db->saveUserCookie($postedUsername, $randomStr);
    }

    private function redirectToSelf() {
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
}