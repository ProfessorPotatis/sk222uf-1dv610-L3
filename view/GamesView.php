<?php

class GamesView {
	private $session;
	private $get;
    //private $gamesController;

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

		/*$this->gamesController = new GamesController();
		$this->gamesController->handleUserRequest();*/
		/*$message = $this->gamesController->getMessage();*/

		$gamesIsSet = $this->get->getVariableIsSet('games');

        if ($gamesIsSet) {
            $response = $this->generateGamesHTML(/*$message*/);
        }
        
		return $response;
	}
	
	private function generateGamesHTML(/*$message*/) {
        return '
        <h1>Dinosaur Life</h1>
        <p>Control the character with the arrowkeys.</p>
        <div id="content" class="content">
            <div id="baddie" class="content"></div>
        </div>
	    ';
	}
}