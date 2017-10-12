<?php

class GameController {
    private $dinoGame;
    private $gameView;
    private $request;

    private $requestReset;
    private $requestLeftArrow;
    private $requestUpArrow;
    private $requestRightArrow;
    private $requestDownArrow;

    private $gameMap;
    private $dinoPosition;
    private $dinoFacingDirection;

    public function __construct() {
        $this->dinoGame = new DinoGame();
        $this->dinoGame->startGame();
        $this->dinoPosition = $this->dinoGame->dinoPosition();

        $this->gameView = new GameView();
        $this->requestReset = $this->gameView->getRequestReset();
        $this->requestLeftArrow = $this->gameView->getRequestLeftArrow();
        $this->requestUpArrow = $this->gameView->getRequestUpArrow();
        $this->requestRightArrow = $this->gameView->getRequestRightArrow();
        $this->requestDownArrow = $this->gameView->getRequestDownArrow();

        $this->request = new Request();
    }

    public function getGameMap() {
        $this->gameMap = $this->dinoGame->getGameMap();
        return $this->gameMap;
    }

    public function handleUserRequest() {
        if ($this->request->requestVariableIsSet($this->requestUpArrow)) {
            $this->dinoGame->dinoMovesUp();
        } else if ($this->request->requestVariableIsSet($this->requestLeftArrow)) {
            $this->dinoGame->dinoMovesLeft();
        } else if ($this->request->requestVariableIsSet($this->requestDownArrow)) {
            $this->dinoGame->dinoMovesDown();
        } else if ($this->request->requestVariableIsSet($this->requestRightArrow)) {
            $this->dinoGame->dinoMovesRight();
        } else if ($this->request->requestVariableIsSet($this->requestReset)) {
            $this->dinoGame->resetGame();
            $this->redirectToSelf();
        }
    }

    public function getDinoPosition() {
        $this->dinoPosition = $this->dinoGame->dinoPosition();
        return $this->dinoPosition;
    }

    public function getDinoFacingDirection() {
        $this->dinoFacingDirection = $this->dinoGame->dinoFacingDirection();
        return $this->dinoFacingDirection;
    }

    private function redirectToSelf() {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?games');
    }
}