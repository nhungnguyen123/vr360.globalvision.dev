<?php

class Vr360Authorise
{
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
	public function signIn()
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
		$this->user = new Vr360User($user);

		if (md5($password) !== $this->user->password)
		{
			return false;
		}

		$session = Vr360Session::getInstance();
		$session->set('logged', true);
		$session->set('user', $this->user);
		$session->set('token', Vr360Session::getInstance()->generateToken());

		$this->user->last_login = Vr360HelperDatetime::getMySqlFormat();
		$this->user->save();

		return true;
	}

	public function signOut()
	{
		if (isset($_GET ['signOut']))
		{
			$_SESSION ['auth'] = false;
			$_SESSION ['user'] = null;

			session_unset();
			session_destroy();

			return true;
		}
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
