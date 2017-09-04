<?php

/**
 * Class Vr360Authorise
 */
class Vr360Authorise
{
	/**
	 * @var  Vr360TableUser
	 */
	public $user;

	public static function getInstance()
	{
		static $instance;

		if (!isset($instance))
		{
			$instance = new static();
		}

		return $instance;
	}

	/**
	 * Execute login
	 *
	 * @return bool
	 */
	public function login()
	{
		if (isset($_POST ['username']) && isset($_POST ['password']) && !self::isLogged())
		{
			if ($this->authorise($_POST ['username'], $_POST ['password']))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $userName
	 * @param $password
	 *
	 * @return bool
	 */
	public function authorise($userName, $password)
	{
		$user       = Vr360Database::getInstance()->getUserData($userName);
		$this->user = new Vr360TableUser($user);

		// Password verify
		if (md5($password) !== $this->user->password)
		{
			return false;
		}

		// Save session
		$session = Vr360Session::getInstance();
		$session->set('logged', true);
		$session->set('user', $this->user);
		$session->set('token', Vr360Session::getInstance()->generateToken());

		// Update last login
		$this->user->updateLastLogin();

		return true;
	}

	public function logout()
	{
		$session = Vr360Session::getInstance();
		$session->reset();
		$session->set('logged', false);
		$session->set('token', Vr360Session::getInstance()->generateToken());
	}

	/**
	 * @return   boolean
	 */
	public static function isLogged()
	{
		return Vr360Session::getInstance()->get('logged', false);
	}

	public function getUser()
	{
		return Vr360Session::getInstance()->get('user');
	}
}
