<?php

defined('_VR360_EXEC') or die;

class Vr360TableUser extends Vr360Table
{

	/**
	 * @var null
	 */
	public $id = null;
	public $username = null;
	public $name = null;
	public $password = null;
	public $email = null;
	public $last_login = null;
	public $last_visit = null;
	public $params = null;

	protected $_table = 'users';

	public function updateLastLogin()
	{
		$this->last_login = Vr360HelperDatetime::getMySqlFormat();
		$this->save();
	}

	public function check()
	{
		if (
			empty($this->username)
			|| empty($this->name)
			|| empty($this->password)
			|| empty($this->email)
		)
		{
			return false;
		}

		$this->last_visit = Vr360HelperDatetime::getMySqlFormat();

		return parent::check();
	}
}