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
          </head>
          <body>
            <h1>Assignment 2</h1>
            ' . $this->renderRegisterLink($isLoggedIn) . '
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
}
