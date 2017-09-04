<?php

class Vr360HelperAjax
{
	public static function validate()
	{
		// Ajax verify
		$ajax = new Vr360AjaxResponse();
		$token = Vr360Session::getInstance()->get('token');

		if (!isset($_POST[$token]) || (isset($_POST[$token]) && $_POST[$token] != 1))
		{
			$ajax->setMessage('Invalid request')->fail()->respond();
		}

		return true;
	}
}