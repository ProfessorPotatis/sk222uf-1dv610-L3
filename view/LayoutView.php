<?php

class LayoutView {

    private $get;
    
    public function render($isLoggedIn, $v, DateTimeView $dtv) {
      $this->get = new Get();

      echo '<!DOCTYPE html>
        <html>
          <head>
            <meta charset="utf-8">
            <title>Login Example</title>
            <link rel="stylesheet" type="text/css" href="style/style.php">
          </head>
          <body>
            <h1>Assignment 2</h1>
            ' . $this->renderRegisterLink($isLoggedIn) . '
            ' . $this->renderGamesLink($isLoggedIn) . '
            ' . $this->renderIsLoggedIn($isLoggedIn) . '
            
            <div class="container">
                ' . $v->response() . '
                
                ' . $dtv->show() . '
            </div>
          </body>
        </html>
      ';
    }
    
    private function renderIsLoggedIn($isLoggedIn) {
      if ($isLoggedIn) {
        return '<h2>Logged in</h2>';
      }
      else {
        return '<h2>Not logged in</h2>';
      }
    }

    private function renderRegisterLink($isLoggedIn) {
      if ($isLoggedIn) {
        return;
      } else {
        if ($this->get->getVariableIsSet('register')) {
          return '<a href="?">Back to login</a>';
        } else {
          return '<a href="?register">Register a new user</a>';
        }
      }
    }

    private function renderGamesLink($isLoggedIn) {
      if ($isLoggedIn && $this->get->getVariableIsSet('games')) {
        return '<a href="?">Back to login</a>';
      } else if ($isLoggedIn) {
        return '<a href="?games">Play games</a>';
      } else {
        return;
      }
    }
}
