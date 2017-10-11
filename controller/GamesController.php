<?php

class GamesController {
    private $gdb;
    private $dinoGame;

    private $gameMap;
    private $dinoMarginLeft;
    private $dinoMarginTop;
    private $dinoFacingDirection;

    public function __construct() {
        $this->dinoGame = new DinoGame();
        $this->gameMap = $this->dinoGame->generateGameMap();
        $this->dinoMarginLeft = $this->dinoGame->dinoPositionLeft();
        $this->dinoMarginTop = $this->dinoGame->dinoPositionTop();
    }

    public function getGameMap() {
        return $this->gameMap;
    }

    public function handleUserRequest() {        
        if (isset($_POST['GameView::UpArrow'])) {
            echo 'up';
            $this->dinoGame->dinoMovesUp();
        } else if (isset($_POST['GameView::LeftArrow'])) {
            echo 'left';
            $this->dinoGame->dinoMovesLeft();
        } else if (isset($_POST['GameView::DownArrow'])) {
            echo 'down';
            $this->dinoGame->dinoMovesDown();
        } else if (isset($_POST['GameView::RightArrow'])) {
            echo 'right';
            $this->dinoGame->dinoMovesRight();
        }
    }

    public function getDinoMarginLeft() {
        $this->dinoMarginLeft = $this->dinoGame->dinoPositionLeft();
        return $this->dinoMarginLeft;
    }

    public function getDinoMarginTop() {
        $this->dinoMarginTop = $this->dinoGame->dinoPositionTop();
        return $this->dinoMarginTop;
    }

    public function getDinoFacingDirection() {
        $this->dinoFacingDirection = $this->dinoGame->dinoFacingDirection();
        return $this->dinoFacingDirection;
    }
}