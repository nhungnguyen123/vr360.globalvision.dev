<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableUser
 *
 * @since   2.0.0
 */
class Vr360TableUser extends Vr360Table
{
	/**
	 * @var null
	 */
	public $id = null;

	/**
	 * @var  string
	 */
	public $username = null;

	/**
	 * @var  string
	 */
	public $name = null;

	/**
	 * @var  string
	 */
	public $password = null;

	/**
	 * @var  string
	 */
	public $email = null;

	/**
	 * @var  string
	 */
	public $last_login = null;

	/**
	 * @var  string
	 */
	public $last_visit = null;

	/**
	 * @var  string
	 */
	public $params = null;

	/**
	 * @var string
	 */
	protected $_table = 'users';

	/**
	 * @return bool|PDOStatement
	 */
	public function updateLastLogin()
	{
		$this->last_login = Vr360HelperDatetime::getMySqlFormat();

		return $this->save();
	}

	/**
	 * @return boolean
	 */
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
