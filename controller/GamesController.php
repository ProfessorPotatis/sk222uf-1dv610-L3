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
        $this->dinoGame->startGame();
        $this->dinoMarginLeft = $this->dinoGame->dinoPositionLeft();
        $this->dinoMarginTop = $this->dinoGame->dinoPositionTop();
    }

    public function getGameMap() {
        $this->gameMap = $this->dinoGame->getGameMap();
        return $this->gameMap;
    }

    public function handleUserRequest() {        
        if (isset($_POST['GameView::UpArrow'])) {
            $this->dinoGame->dinoMovesUp();
        } else if (isset($_POST['GameView::LeftArrow'])) {
            $this->dinoGame->dinoMovesLeft();
        } else if (isset($_POST['GameView::DownArrow'])) {
            $this->dinoGame->dinoMovesDown();
        } else if (isset($_POST['GameView::RightArrow'])) {
            $this->dinoGame->dinoMovesRight();
        } else if (isset($_POST['GameView::Reset'])) {
            $this->dinoGame->resetGame();
            $this->redirectToSelf();
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

    private function redirectToSelf() {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?games');
    }
}