<?php

defined('_VR360_EXEC') or die;

class Vr360ControllerUser extends Vr360Controller
{
	/**
	 * Login
	 */
	public function login()
	{
		$input = Vr360Factory::getInput();

		if (Vr360HelperAuthorize::authorize($input->getString('username'), $input->getRaw('password')))
		{
			$input->set('view', 'tours');
			$this->display();
		}
	}

	/**
	 * Logout by clear session
	 *
	 * @since  1.0.0
	 */
	public function logout()
	{
		Vr360Session::getInstance()->reset();

		header("Location: " . 'index.php');
	}
}
