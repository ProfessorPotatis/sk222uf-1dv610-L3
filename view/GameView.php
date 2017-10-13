<?php

class GameView {
    private static $reset = 'GameView::Reset';
    private static $leftArrow = 'GameView::LeftArrow';
    private static $upArrow = 'GameView::UpArrow';
    private static $rightArrow = 'GameView::RightArrow';
    private static $downArrow = 'GameView::DownArrow';

	private $session;
	private $get;
    private $gamesController;

    private $gameMap;
    private $playerWon;

	/**
	 * Create HTTP response
	 *
	 * Should be called after a play game attempt has been determined
	 *
	 * @return  void BUT writes to standard output.
	 */
	public function response() {
		$this->session = new Session();
        $this->get = new Get();

        $this->gamesController = new GameController();
        $this->playerWon = $this->gamesController->handleUserRequest();

		$gamesIsSet = $this->get->getVariableIsSet('games');

        if ($gamesIsSet) {
            $response = $this->generateGamesHTML();
        }
        
		return $response;
    }
	
	private function generateGamesHTML() {
        $winnerMessage = $this->playerWon();
        $tiles = $this->getTiles();

        $dinoPosition = $this->gamesController->getDinoPosition();

        $dinoFacingDirection = $this->gamesController->getDinoFacingDirection();

        $html = '
        <h1>Dinosaur Move Boxes</h1>
        <p>Control the dinosaur with the keys and move the boxes around.<br>
        1. Try moving all the boxes into the four corners.<br>
        2. Try moving all the boxes into the center of the map.</p>
        <p>' . $winnerMessage . '</p>
        <form class="keyPad" method="post" > 
            <div id="keyPad">
                <div class="gameUp"><input type="submit" name="' . self::$upArrow . '" value="Up" /></div><br>
                <div class="gameLeft"><input type="submit" name="' . self::$leftArrow . '" value="Left" /></div>
                <div class="gameDown"><input type="submit" name="' . self::$downArrow . '" value="Down" /></div>
                <div class="gameRight"><input type="submit" name="' . self::$rightArrow . '" value="Right" /></div>
            </div>
            <div class="reset"><input type="submit" name="' . self::$reset . '" value="Reset" /></div><br>
        </form>
        <div id="content" class="content">
        ';

        for($i = 0; $i < count($tiles); $i++) {
            $html .= $tiles[$i];
        }

        $html .= '<div id="dino" class="content ' . $dinoFacingDirection . '" style="margin-left:' . $dinoPosition[0] . 'px; margin-top:' . $dinoPosition[1] . 'px;"></div>
        </div>
        ';
        
        return $html;
    }
    
    private function getTiles() {
        $gameMap = $this->gamesController->getGameMap();

        $tiles = array();

        for($i = 0; $i < count($gameMap); $i++) {
            $tiles[] = '<div id="n' . $i . '" class="tile t' . $gameMap[$i] . '"></div>';
        }

        return $tiles;
    }

    public function getRequestReset() {
		return self::$reset;
	}

	public function getRequestLeftArrow() {
		return self::$leftArrow;
	}

	public function getRequestUpArrow() {
		return self::$upArrow;
	}

	public function getRequestRightArrow() {
		return self::$rightArrow;
	}

	public function getRequestDownArrow() {
		return self::$downArrow;
    }
    
    public function playerWon() {
        $message;

        if ($this->playerWon) {
            $message = 'Congratulations, you are a winner!';
        } else {
            $message = '';
        }

        return $message;
    }
}