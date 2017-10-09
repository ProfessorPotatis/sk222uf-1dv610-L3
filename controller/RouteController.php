<?php

class RouteController {
    private $layoutView;
    private $dateTimeView;
    private $loginView;
    private $registerView;

    private $session;
    private $server;
    private $get;
    private $cookie;

    public function __construct() {
        $this->layoutView = new LayoutView();
        $this->dateTimeView = new dateTimeView();
        $this->loginView = new LoginView();
        $this->registerView = new RegisterView();
        $this->gamesView = new GamesView();

        $this->session = new Session();
        $this->server = new Server();
        $this->get = new Get();
        $this->cookie = new Cookie();
    }

    public function route() {
        if ($this->session->isLoggedIn()) {
            if ($this->userWantsToPlayGames()) {
                $isLoggedIn = true;
                $this->renderGamesPage($isLoggedIn);
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

    private function notHijacked() {
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

    private function renderLoginPage($isLoggedIn) {
        $this->layoutView->render($isLoggedIn, $this->loginView, $this->dateTimeView);
    }

    private function userWantsToRegister() {
        return $this->get->getVariableIsSet('register');
    }

    private function renderRegisterPage($isLoggedIn) {
        $this->layoutView->render($isLoggedIn, $this->registerView, $this->dateTimeView);
    }

    private function userHasCookies() {
        $cookiePasswordIsSet = $this->cookie->cookieIsSet('LoginView::CookiePassword');
        $cookiePassword = $this->cookie->getCookieVariable('LoginView::CookiePassword');

        if ($cookiePasswordIsSet && !empty($cookiePassword)) {
            return true;
        }
        return false;
    }

    private function userWantsToPlayGames() {
        return $this->get->getVariableIsSet('games');
    }

    private function renderGamesPage($isLoggedIn) {
        $this->layoutView->render($isLoggedIn, $this->gamesView, $this->dateTimeView);
    }
}