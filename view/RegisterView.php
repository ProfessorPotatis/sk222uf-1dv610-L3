<?php

class RegisterView {
	private static $registerName = 'RegisterView::UserName';
	private static $registerPassword = 'RegisterView::Password';
	private static $repeatPassword = 'RegisterView::PasswordRepeat';
	private static $registerMessage = 'RegisterView::Message';
	private static $register = 'RegisterView::Register';

	private $session;
	private $get;
    private $registerController;
    
    private $registerUserName;

	/**
	 * Create HTTP response
	 *
	 * Should be called after a register attempt has been determined
	 *
	 * @return  void BUT writes to standard output.
	 */
	public function response() {
		$this->session = new Session();
		$this->get = new Get();

		$this->registerController = new RegisterController();
		$this->registerController->handleUserRequest();
		$message = $this->registerController->getMessage();

		$registerIsSet = $this->get->getVariableIsSet('register');

        if ($registerIsSet) {
			$response = $this->generateRegisterFormHTML($message);
        }
        
		return $response;
	}
	
	private function generateRegisterFormHTML($message) {

        $this->registerUsername = $this->getUsername();

		return '
		<h2>Register new user</h2>
		<form method="post" > 
			<fieldset>
				<legend>Register a new user - Write username and password</legend>
				<p id="' . self::$registerMessage . '">' . $message . '</p>
				
				<label for="' . self::$registerName . '">Username :</label>
				<input type="text" id="' . self::$registerName . '" name="' . self::$registerName . '" value="' . $this->registerUsername . '" /><br>

				<label for="' . self::$registerPassword . '">Password :</label>
				<input type="password" id="' . self::$registerPassword . '" name="' . self::$registerPassword . '" /><br>

				<label for="' . self::$repeatPassword . '">Repeat password :</label>
				<input type="password" id="' . self::$repeatPassword . '" name="' . self::$repeatPassword . '" /><br>
				
				<input type="submit" name="' . self::$register . '" value="Register" />
			</fieldset>
		</form>
	';
	}
	
	private function getUsername() {
		return $this->registerController->getUsername();
	}

	public function getRequestUserName() {
		return self::$registerName;
	}

	public function getRequestPassword() {
		return self::$registerPassword;
	}
	public function getRequestRepeatPassword() {
		return self::$repeatPassword;
	}

	public function getRequestRegister() {
		return self::$register;
	}
}