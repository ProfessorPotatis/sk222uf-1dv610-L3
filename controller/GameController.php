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

    public function handleUserRequest() : bool {
        $userWantsToMoveUp = $this->request->requestVariableIsSet($this->requestUpArrow);
        $userWantsToMoveLeft = $this->request->requestVariableIsSet($this->requestLeftArrow);
        $userWantsToMoveDown = $this->request->requestVariableIsSet($this->requestDownArrow);
        $userWantsToMoveRight = $this->request->requestVariableIsSet($this->requestRightArrow);
        $userWantsToResetGame = $this->request->requestVariableIsSet($this->requestReset);

        if ($userWantsToMoveUp) {
            $this->dinoGame->dinoMovesUp();
        } else if ($userWantsToMoveLeft) {
            $this->dinoGame->dinoMovesLeft();
        } else if ($userWantsToMoveDown) {
            $this->dinoGame->dinoMovesDown();
        } else if ($userWantsToMoveRight) {
            $this->dinoGame->dinoMovesRight();
        } else if ($userWantsToResetGame) {
            $this->dinoGame->resetGame();
            $this->redirectToSelf();
        }

        $isPlayerWinner = $this->dinoGame->isPlayerWinner();
        return $isPlayerWinner;
    }

    /**
    * @return gameMap[]
    */
    public function getGameMap() {
        $this->gameMap = $this->dinoGame->getGameMap();
        return $this->gameMap;
    }

    /**
    * @return dinoPosition[]
    */
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