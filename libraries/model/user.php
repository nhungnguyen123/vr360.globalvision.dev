<?php

defined('_VR360_EXEC') or die;

class Vr360ModelUser extends Vr360Model
{

	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static();

		return $instance;
	}

	public function authorize($userName, $password)
	{
		$user = $this->getByUsername($userName);

		if ($user === false)
		{
			return false;
		}

		if (
			$user->password === md5($password) ||
			$user->password === md5(Vr360Configuration::getConfig('salt') . $password)
		)
		{
			$session = Vr360Session::getInstance();
			$session->set('logged', true);
			$session->set('user', $user);

			return true;
		}
	}

	public function getByUsername($userName)
	{
		$db = Vr360Database::getInstance();

		$row = $db->load('users', array
		('OR' =>
			 array
			 (
				 'username' => $userName,
				 'email'    => $userName
			 )
		));

		if ($row === false)
		{
			return false;
		}

		$user = new Vr360TableUser();
		$user->bind($row);

		return $user;
	}
}