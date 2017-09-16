<?php

defined('_VR360_EXEC') or die;

class Vr360ControllerLogin extends Vr360Controller
{
	public function login()
	{
		$input = Vr360Factory::getInput();

		if (Vr360HelperAuthorize::authorize($input->getString('username'), $input->getRaw('password')))
		{
			$input->set('view', 'tours');
			$this->display();
		}
	}
}