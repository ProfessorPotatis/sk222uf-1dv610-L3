<?php

class DinoGame {
    private $gdb;
    
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
        require($_SERVER['DOCUMENT_ROOT'] . '/sk222uf-1dv610-L3/model/DBConfigGame.php');
        
        $this->gdb = new GameDatabase($db_host, $db_user, $db_password, $db_name);

        $this->tileSize = 32;
        $this->gridSize = 10;

        $this->left = 0;
        $this->top = 0;

        $this->posLeft = 0;
        $this->posTop = 0;
    }

    public function startGame() {
        $this->generateGameMap();
        $this->placeDinoOnMap();
    }

    public function getGameMap() {
        return $this->gameMap;
    }

    private function generateGameMap() {
        if (isset($_SESSION['gameMap'])) {
            $this->gameMap = $_SESSION['gameMap'];
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

            $_SESSION['gameMap'] = $map;

            $this->gameMap = $map;
        }
        
        return $this->gameMap;
    }

    private function placeDinoOnMap() {
        if ($this->gdb->checkIfUserExist($_REQUEST['PHPSESSID'])) {
            $this->moveDino(0, 0);
        } else {
            $this->moveDino(1, 1);
        }
    }

    public function dinoPositionLeft() {
        if ($this->gdb->checkIfUserExist($_REQUEST['PHPSESSID'])) {
            $positionArray = $this->gdb->getCurrentDinoPosition($_REQUEST['PHPSESSID']);
            $this->left = $positionArray[0];
        }
        return $this->left;
    }

    public function dinoPositionTop() {
        if ($this->gdb->checkIfUserExist($_REQUEST['PHPSESSID'])) {
            $positionArray = $this->gdb->getCurrentDinoPosition($_REQUEST['PHPSESSID']);
            $this->top = $positionArray[1];
        }
        return $this->top;
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
                // Move dino to tile
                $this->movable = true;
                break;
            case 11:
                // Wall, don't move dino
                break;
            case 12:
                // Tile was a box, move it and then dino
                $nextPos;
                $nextTile;

                // Calculate where the sibling tile to be checked is in the array
                $nextPos = $tilePos + $moveLeft + ($this->gridSize*$moveTop);

                // Get the next tile from gameMap and place it in the variable nextTile
                $nextTile = $this->gameMap[$nextPos];

                // Only move if the sibling tile to be moved to is empty
                if($nextTile == 10) {
                    $this->moveTile($tilePos, $nextPos);
                    // Allow dino to move to the current tile
                    $this->movable = true;
                } else {
                    // if not empty - don't do anything else
                }
                break;
            default:
                // Tile was impassible - collided, do not move dino
                $this->movable = false;
        }

        return $this->movable;
    }

    private function moveTile($current, $next) {
        $boxTile = $this->gameMap[$current];

        // Switch the tiles
        // Place tile into the next positon in the array gameMap
        // Then make sure the current tile is grass in the array gameMap
        $this->gameMap[$next] = $boxTile;
        $this->gameMap[$current] = 10;

        $_SESSION['gameMap'] = $this->gameMap;
    }

    private function moveDino($x, $y) {
		$this->posLeft = $x;
        $this->posTop = $y;
        
        if ($this->gdb->checkIfUserExist($_REQUEST['PHPSESSID'])) {
            $positionArray = $this->gdb->getCurrentDinoPosition($_REQUEST['PHPSESSID']);
            $currentLeftPosition = $positionArray[0];
            $currentTopPosition = $positionArray[1];
    
            $this->left = $currentLeftPosition + ($this->posLeft*$this->tileSize);
            $this->top = $currentTopPosition + ($this->posTop*$this->tileSize);
        } else {
            $this->left = $this->posLeft*$this->tileSize;
            $this->top = $this->posTop*$this->tileSize;
        }
        
        $this->updateDinoPosition();
    }

    private function updateDinoPosition() {
        if ($this->gdb->checkIfUserExist($_REQUEST['PHPSESSID'])) {
            $this->gdb->updateDinoPosition($_REQUEST['PHPSESSID'], $this->left, $this->top);
        } else {
            $this->gdb->addDinoPosition($_REQUEST['PHPSESSID'], $this->left, $this->top);
        }
    }

    private function turnLeft() {
        $this->dinoFacingDirection = 'left';
    }

    private function turnRight() {
        $this->dinoFacingDirection = 'right';
    }

    public function resetGame() {
        unset($_SESSION['gameMap']);
        $this->gdb->updateDinoPosition($_REQUEST['PHPSESSID'], 32, 32);
    }
}