<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Factory
 *
 * @since  2.0.0
 */
class Vr360Factory
{
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
}
