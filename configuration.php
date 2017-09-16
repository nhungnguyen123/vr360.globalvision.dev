<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Configuration
 */
class Vr360Configuration extends Vr360Object
{
	public $dbName = 'globalvision_vr360';
	public $dbUser = 'root';
	public $dbPassword = 'root';
	public $dbServer = 'localhost';
	public $siteName = 'Vr360 - Globalvision';

	public $allowMimeTypes = array('image/png', 'image/jpeg');

	public $salt = '?xlE7%:EmC$u yt;l;wBw&dyt^@/E+*`>bO&oGS&jZn|!AIQ/%XY/23nK/{h2.AJ\')';
	public $cookieTime = 86400;
	public $sessionNamespace = 'VR360';

	public $mailHost = '';
	public $mailUsername = '';
	public $mailPassword = '';
	public $mailSecure = '';
	public $mailPort = '';
	public $mailer = 'sendmail';

	public $siteDescription = '';
	public $siteKeyword = '';

	public $debug = true;

	public static function getInstance()
	{
		static $instance;
		if (empty($instance))
		{
			$instance = new Vr360Configuration();
		}

		return $instance;
	}

	public static function getConfig($property, $default = null)
	{
		$instance = self::getInstance();

		return $instance->get($property, $default);
	}
}
