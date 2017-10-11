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

    public function generateGameMap() {
        $this->gameMap = array(
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

        $this->moveDino(0, 0);

        return $this->gameMap;
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
        $this->moveDino(0, -1);
    }

    public function dinoMovesLeft() {
        $this->moveDino(-1, 0);
        $this->turnLeft();
    }

    public function dinoMovesDown() {
        $this->moveDino(0, 1);
    }

    public function dinoMovesRight() {
        $this->moveDino(1, 0);
        $this->turnRight();
    }

    public function moveDino($x, $y) {
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
}