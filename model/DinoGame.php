<?php

class DinoGame {
    
    private $tileSize; // Size of each tile
    private $gridSize; // Number of tiles per row
    private $left;
    private $top;
    private $posLeft;
    private $posTop;

    private $gameMap;

    public function __construct() {
        $this->tileSize = 32;
        $this->gridSize = 10;

        $this->left = 32;
        $this->top = 32;

        $this->posLeft = 1;
        $this->posTop = 1;
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

        return $this->gameMap;
    }

    public function dinoPositionLeft() {
        return $this->left;
    }

    public function dinoPositionTop() {
        return $this->top;
    }

    public function dinoMovesUp() {
        $this->moveDino(0, -1);
        //$this->posTop -= 32;
    }

    public function dinoMovesLeft() {
        $this->moveDino(-1, 0);
        //$this->turnLeft();
        //$this->posLeft -= 32;
    }

    public function dinoMovesDown() {
        $this->moveDino(0, 1);
        //$this->posTop += 32;
    }

    public function dinoMovesRight() {
        $this->moveDino(1, 0);
        //$this->turnRight();
        //$this->posLeft += 32;
    }

    private function moveDino($x, $y) {
		$this->posLeft += $x;
		$this->posTop += $y;

		$this->left = $this->posLeft*$this->tileSize;
		$this->top = $this->posTop*$this->tileSize;
	}
}