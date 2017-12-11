<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Factory
 *
 * @since  2.0.0
 */
class Vr360Factory
{
	/**
	 * @return \Joomla\Input\Input
	 */
	public static function getInput()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new \Joomla\Input\Input;

		return $instance;
	}

	/**
	 * @return \Joomla\Session\Session
	 */
	public static function getSession()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new \Joomla\Session\Session;

		return $instance;
	}

	/**
	 * @return  null|Vr360TableUser
	 */
	public static function getUser()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = Vr360Session::getInstance()->get('user', new Vr360TableUser);

		return $instance;
	}

	/**
	 * @return \Joomla\Database\DatabaseDriver
	 */
	public static function getDbo()
	{
		$factory = new \Joomla\Database\DatabaseFactory;
		$db      = $factory->getDriver('mysqli', array(
			'host'     => Vr360Configuration::getConfig('dbServer'),
			'user'     => Vr360Configuration::getConfig('dbUser'),
			'password' => Vr360Configuration::getConfig('dbPassword'),
			'database' => Vr360Configuration::getConfig('dbName'),
			'utf8mb4'  => true
		));

		return $db;
	}
}
