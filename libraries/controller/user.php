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

		if (Vr360HelperAuthorize::authorize($input->getString('username'), $input->get('password', '', 'RAW')))
		{
			$input->set('view', 'tours');
			$this->display();
		}
		else
		{
			Vr360Session::getInstance()->addMessage('Invalid Username or password', 'warning');
			$input->set('view', 'user');
			$this->display();
		}
	}

	/**
	 * Logout by clear session
	 *
	 * @since  2.0.0
	 */
	public function logout()
	{
		Vr360Session::getInstance()->reset();

		header("Location: " . 'index.php');
	}

	/**
	 *
	 */
	public function ajaxGetUserHtml()
	{
		$html = Vr360Layout::getInstance()->fetch('user.profile');

		Vr360AjaxResponse::getInstance()->addData('html', $html)->success()->respond();
	}

	/**
	 *
	 */
	public function ajaxSaveProfile()
	{
		Vr360ModelUser::getInstance()->ajaxSaveProfile();
	}
}
