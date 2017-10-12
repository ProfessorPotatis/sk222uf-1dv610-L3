<?php

class DinoGame {
    private $session;

    private $tileSize; // Size of each tile
    private $gridSize; // Number of tiles per row
    private $left;
    private $top;
    private $posLeft;
    private $posTop;

    private $gameMap;
    private $dinoFacingDirection;
    private $movable = false;

    public function __construct() {
        $this->session = new Session();

        $this->tileSize = 32;
        $this->gridSize = 10;

        $this->left = 0;
        $this->top = 0;

        $this->posLeft = 0;
        $this->posTop = 0;
    }

    public function getGameMap() {
        return $this->gameMap;
    }

    public function startGame() {
        $this->generateGameMap();
        $this->placeDinoOnMap();
    }

    private function generateGameMap() {
        if ($this->session->sessionVariableIsSet('gameMap')) {
            $this->gameMap = $this->session->getSessionVariable('gameMap');
        } else {
            $map = array(
                11,11,11,11,11,11,11,11,11,11,
                11,10,10,10,10,10,10,10,10,11,
                11,11,12,11,13,11,11,12,11,11,
                11,10,10,10,10,11,10,10,10,11,
                11,10,10,10,10,11,10,10,10,11,
                11,10,11,11,10,10,10,10,10,11,
                11,10,12,10,10,10,10,11,10,11,
                11,10,10,10,11,10,10,12,10,11,
                11,10,10,10,13,10,10,10,10,11,
                11,11,11,11,11,11,11,11,11,11);

            $this->gameMap = $map;

            $this->session->setSessionVariable('gameMap', $map);
        }
        
        return $this->gameMap;
    }

    private function placeDinoOnMap() {
        if ($this->playerIsSet()) {
            $this->moveDino(0, 0);
        } else {
            $this->moveDino(1, 1);

            $this->setPlayer();
            $this->setPosition(32, 32);
        }
    }

    private function playerIsSet() {
        return $this->session->sessionVariableIsSet('player');
    }

    private function setPlayer() {
        $player = $this->cookie->getCookieVariable('PHPSESSID');
        $this->session->setSessionVariable('player', $player);
    }

    private function setPosition($x, $y) {
        $this->session->setSessionVariable('dinoPosLeft', $x);
        $this->session->setSessionVariable('dinoPosTop', $y);
    }

    public function dinoPosition() {
        if ($this->playerIsSet()) {
            $this->left = $this->getPosition('dinoPosLeft');
            $this->top = $this->getPosition('dinoPosTop');
        }
        $dinoPosition = array($this->left, $this->top);
        return $dinoPosition;
    }

    private function getPosition($variable) {
        return $this->session->getSessionVariable($variable);
    }

    public function dinoFacingDirection() {
        return $this->dinoFacingDirection;
    }

    public function dinoMovesUp() {
        if ($this->isDinoMovable(0, -1)) {
            $this->moveDino(0, -1);
        }
    }

    public function dinoMovesLeft() {
        if ($this->isDinoMovable(-1, 0)) {
            $this->moveDino(-1, 0);
            $this->turnLeft();
        }
    }

    public function dinoMovesDown() {
        if ($this->isDinoMovable(0, 1)) {
            $this->moveDino(0, 1);
        }
    }

    public function dinoMovesRight() {
        if ($this->isDinoMovable(1, 0)) {
            $this->moveDino(1, 0);
            $this->turnRight();
        }
    }

    private function isDinoMovable($moveLeft, $moveTop) {
        $tile;
        $tilePos;
        $newLeft;
        $newTop;

        $newLeft = ($this->left / 32) + $moveLeft;
        $newTop = ($this->top / 32) + $moveTop;

        $tilePos = $newLeft + $newTop*$this->gridSize;

        $tile = $this->gameMap[$tilePos];

        switch($tile) {
            case 10: // grass
            case 13: // door
            case 14: // sand
                $this->movable = true;
                break;
            case 11: // wall
                break;
            case 12: // box
                $nextPos = $tilePos + $moveLeft + ($this->gridSize*$moveTop);
                $nextTile = $this->gameMap[$nextPos];

                // Only move if the sibling tile to be moved to is grass
                if ($nextTile == 10) {
                    $this->moveTile($tilePos, $nextPos);
                    // Allow dino to move to the current tile
                    $this->movable = true;
                }
                break;
            default:
                $this->movable = false;
                break;
        }

        return $this->movable;
    }

    private function moveTile($current, $next) {
        $boxTile = $this->gameMap[$current];

        // Switch the tiles
        // Place the box tile into the next positon in the array gameMap
        // Place the grass tile in the box tile previous place in the array gameMap
        $this->gameMap[$next] = $boxTile;
        $this->gameMap[$current] = 10;

        $this->session->setSessionVariable('gameMap', $this->gameMap);
    }

    private function moveDino($x, $y) {
		$this->posLeft = $x;
        $this->posTop = $y;
        
        if ($this->playerIsSet()) {
            $currentLeftPosition = $this->getPosition('dinoPosLeft');
            $currentTopPosition = $this->getPosition('dinoPosTop');
    
            $this->left = $currentLeftPosition + ($this->posLeft*$this->tileSize);
            $this->top = $currentTopPosition + ($this->posTop*$this->tileSize);
        } else {
            $this->left = $this->posLeft*$this->tileSize;
            $this->top = $this->posTop*$this->tileSize;
        }
        
        $this->updateDinoPosition();
    }

    private function updateDinoPosition() {
        $this->setPosition($this->left, $this->top);
    }

    private function turnLeft() {
        $this->dinoFacingDirection = 'left';
    }

    private function turnRight() {
        $this->dinoFacingDirection = 'right';
    }

    public function resetGame() {
        $this->session->unsetSessionVariable('gameMap');
        $this->setPosition(32, 32);
    }
}