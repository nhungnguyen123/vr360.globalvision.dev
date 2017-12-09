<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Model
 */
class Vr360Model
{
	/**
	 * @return static
	 */
	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static;

		return $instance;
	}
}