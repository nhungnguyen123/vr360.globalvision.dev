<?php

defined('_VR360_EXEC') or die;

class Vr360Factory
{
	public static function getInput()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new \Joomla\Input\Input();

		return $instance;
	}

	public static function getSession()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new \Joomla\Session\Session();

		return $instance;
	}

	public static function getUser()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = Vr360Session::getInstance()->get('user', new Vr360TableUser());

		return $instance;
	}
}