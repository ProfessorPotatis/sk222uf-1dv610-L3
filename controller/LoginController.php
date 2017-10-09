<?php

class LoginController {

    private static $providedUsername = 'LoginView::UserName';
    private static $providedPassword = 'LoginView::Password';
    private static $logout = 'LoginView::Logout';
    private static $login = 'LoginView::Login';
    private static $keep = 'LoginView::KeepMeLoggedIn';
    private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';

    private $db;
    private $session;
    private $cookie;
    private $post;
    private $request;
    private $server;

    private $message = '';
    private $username;
    private $isAuthenticated = false;
    
    public function __construct() {
        require($_SERVER['DOCUMENT_ROOT'] . '/sk222uf-1dv610-L2/model/DBConfig.php');

        $this->db = new Database($db_host, $db_user, $db_password, $db_name);
        $this->session = new Session();
        $this->cookie = new Cookie();
        $this->post = new Post();
        $this->request = new Request();
        $this->server = new Server();
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
        } else if ($this->cookie->cookieIsSet(self::$cookiePassword) && $this->session->isLoggedIn() == false) {
            $cookieUsername = $this->cookie->getCookieVariable(self::$cookieName);
            $cookiePw = $this->cookie->getCookieVariable(self::$cookiePassword);

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
        if ($this->post->postVariableIsSet(self::$logout)) {
            $this->logout();
        } else if ($this->post->postVariableIsSet(self::$login) && $this->session->isLoggedIn() == false) {
            $postedUsername = $this->post->getPostVariable(self::$providedUsername);
            $this->session->setSessionVariable('username', $postedUsername);
            $this->username = $this->session->getSessionVariable('username');
            $this->validateInputFields();
        } else {
            $this->message = '';
        }
    }

    private function validateInputFields() {
        $postedUsername = $this->request->getRequestVariable(self::$providedUsername);
        $postedPassword = $this->request->getRequestVariable(self::$providedPassword);

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
        $postedUsername = $this->request->getRequestVariable(self::$providedUsername);
        $postedPassword = $this->request->getRequestVariable(self::$providedPassword);

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
        if ($this->post->postVariableIsSet(self::$keep)) {
            $this->session->setSessionVariable('message', 'Welcome and you will be remembered');
            $this->setCookies();
        }
    }

    private function setCookies() {
        $postedUsername = $this->request->getRequestVariable(self::$providedUsername);
        $randomStr = uniqid();

        $this->cookie->setCookieVariable(self::$cookieName, $postedUsername);
        $this->cookie->setCookieVariable(self::$cookiePassword, $randomStr);

        $this->saveCookiesToDatabase($randomStr);
    }

    private function saveCookiesToDatabase($randomStr) {
        $postedUsername = $this->request->getRequestVariable(self::$providedUsername);

        $this->db->saveUserCookie($postedUsername, $randomStr);
    }

    private function redirectToSelf() {
        header('Location: ' . $_SERVER['PHP_SELF']);
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
        $userCookieIsSet = $this->cookie->cookieIsSet(self::$cookieName);
        $userCookie = $this->cookie->getCookieVariable(self::$cookieName);

        $passwordCookieIsSet = $this->cookie->cookieIsSet(self::$cookiePassword);
        $passwordCookie = $this->cookie->getCookieVariable(self::$cookiePassword);

        if ($userCookieIsSet) {
            $this->cookie->unsetCookieVariable($userCookie);
            setcookie(self::$cookieName, '', time() - 3600, '/'); // empty value and old timestamp, to delete cookie
        }

        if ($passwordCookieIsSet) {
            $this->cookie->unsetCookieVariable($passwordCookie);
            setcookie(self::$cookiePassword, '', time() - 3600, '/'); // empty value and old timestamp, to delete cookie
        }
    }
}