<?php

class GamesController {
    private $gameMap;

    public function __construct() {
        $this->gameMap = $this->generateGameMap();
    }

    public function getGameMap() {
        return $this->gameMap;
    }

    public function handleUserRequest() {        
        if (isset($_POST['GameView::UpArrow'])) {
            echo 'up';
        } else if (isset($_POST['GameView::LeftArrow'])) {
            echo 'left';
        } else if (isset($_POST['GameView::DownArrow'])) {
            echo 'down';
        } else if (isset($_POST['GameView::RightArrow'])) {
            echo 'right';
        }
    }

    private function generateGameMap() {
        $gameMap = array(
            11,11,11,11,11,11,11,11,11,11,
            11,10,10,10,10,10,10,10,10,11,
            11,11,11,11,13,11,11,11,10,12,
            11,10,10,10,10,11,10,10,10,11,
            11,10,10,10,10,10,10,11,11,11,
            11,10,11,11,11,10,10,10,10,11,
            11,10,12,10,11,10,10,10,10,11,
            11,10,10,10,11,10,10,10,10,13,
            11,10,10,10,10,10,10,10,10,11,
            11,11,11,11,11,11,11,11,11,11,);

        return $gameMap;
    }
}