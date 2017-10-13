<?php

class RouteController {
    private $layoutView;
    private $dateTimeView;
    private $loginView;
    private $registerView;
    private $gameView;

    private $session;
    private $server;
    private $get;
    private $cookie;

    private $requestCookiePassword;

    public function __construct() {
        $this->layoutView = new LayoutView();
        $this->dateTimeView = new DateTimeView();
        $this->loginView = new LoginView();
        $this->registerView = new RegisterView();
        $this->gameView = new GameView();

        $this->session = new Session();
        $this->server = new Server();
        $this->get = new Get();
        $this->cookie = new Cookie();

        $this->requestCookiePassword = $this->loginView->getRequestCookiePassword();
    }

    public function route() {
        if ($this->session->isLoggedIn()) {
            if ($this->userWantsToPlayGame()) {
                $isLoggedIn = true;
                $this->renderGamePage($isLoggedIn);
            } else {
                $isLoggedIn = $this->notHijacked();
                $this->renderLoginPage($isLoggedIn);
            }
        } else if ($this->userWantsToRegister()) {
            $isLoggedIn = false;
            $this->renderRegisterPage($isLoggedIn);
        } else if ($this->userHasCookies()) {
            $isLoggedIn = true;
            $this->renderLoginPage($isLoggedIn);
		} else {
            $isLoggedIn = false;
            $this->renderLoginPage($isLoggedIn);
        }
    }

    private function userWantsToPlayGame() : bool {
        return $this->get->getVariableIsSet('games');
    }

    private function renderGamePage(bool $isLoggedIn) {
        $this->layoutView->render($isLoggedIn, $this->gameView, $this->dateTimeView);
    }

    private function notHijacked() : bool {
        $stayLoggedIn;

        $userAgentIsSet = $this->session->sessionVariableIsSet('user_agent');
        $sessionUserAgent = $this->session->getSessionVariable('user_agent');
        $serverUserAgent = $this->server->getServerVariable('HTTP_USER_AGENT');

        if ($userAgentIsSet && $sessionUserAgent !== $serverUserAgent) {
            $stayLoggedIn = false;
        } else {
            $stayLoggedIn = true;
        }
        
        return $stayLoggedIn;
    }

    private function renderLoginPage(bool $isLoggedIn) {
        $this->layoutView->render($isLoggedIn, $this->loginView, $this->dateTimeView);
    }

    private function userWantsToRegister() : bool {
        return $this->get->getVariableIsSet('register');
    }

    private function renderRegisterPage(bool $isLoggedIn) {
        $this->layoutView->render($isLoggedIn, $this->registerView, $this->dateTimeView);
    }

    private function userHasCookies() : bool {
        $cookiePasswordIsSet = $this->cookie->cookieIsSet($this->requestCookiePassword);
        $cookiePassword = $this->cookie->getCookieVariable($this->requestCookiePassword);

        if ($cookiePasswordIsSet && !empty($cookiePassword)) {
            return true;
        }
        return false;
    }
}