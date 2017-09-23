<?php

defined('_VR360_EXEC') or die;

class Vr360HelperAuthorize
{
	public static function isAuthorized()
	{
		$session = Vr360Session::getInstance();

		if ($session->get('logged') === true && $session->get('user')->id)
		{
			return true;
		}

		return false;
	}

	public static function authorize($userName, $password)
	{
		if (empty($userName))
		{
			Vr360Session::getInstance()->addMessage('Invalid username');

			return false;
		}

		if (empty($password))
		{
			Vr360Session::getInstance()->addMessage('Invalid password');

			return false;
		}

		$modelUser = Vr360ModelUser::getInstance();

		return $modelUser->authorize($userName, $password);
	}
}