<?php

class Vr360Configuration
{
	public $dbName = 'globalvision_vr360';
	public $dbUser = 'root';
	public $dbPassword = '123123123';
	public $dbServer = 'db';
	public $siteName = 'Vr360 - Globalvision';

	public static function getInstance()
	{
		static $instance;
		if (empty($instance))
		{
			$instance = new Vr360Configuration();
		}

		return $instance;
	}

}
