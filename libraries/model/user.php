<?php

defined('_VR360_EXEC') or die;

class Vr360ModelUser extends Vr360Model
{

	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static();

		return $instance;
	}

	public function authorize($userName, $password)
	{
		$user = $this->getByUsername($userName);

		if ($user === false)
		{
			return false;
		}

		if (
			$user->password === md5($password) ||
			$user->password === md5(Vr360Configuration::getConfig('salt') . $password)
		)
		{
			$session = Vr360Session::getInstance();
			$session->set('logged', true);
			$session->set('user', $user);

			return true;
		}
	}

	public function getByUsername($userName)
	{
		$db = Vr360Database::getInstance();

		$row = $db->load('users', array
		('OR' =>
			 array
			 (
				 'username' => $userName,
				 'email'    => $userName
			 )
		));

		if ($row === false)
		{
			return false;
		}

		$user = new Vr360TableUser();
		$user->bind($row);

		return $user;
	}

	/**
	 *
	 */
	public function ajaxSaveProfile()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		$user            = Vr360Factory::getUser();
		$password        = $input->getString('password');
		$confirmpassword = $input->getString('confirmpassword');

		$table = new Vr360TableUser;
		$table->load(array('id' => $user->id));

		if ($table->id)
		{
			$table->email  = $input->getString('email');
			$table->name   = $input->getString('name');

			if ($password && !empty($password) && $password == $confirmpassword)
			{
				$table->password = md5($password);
			}

			$file = Vr360Factory::getInput()->files->get('logo');

			if ($file && !empty($file) && !empty($file['name']))
			{
				$userDataDir = VR360_PATH_DATA . '/user/' . $user->id;

				if (!Vr360HelperFolder::exists($userDataDir))
				{
					Vr360HelperFolder::create($userDataDir);
				}

				if (!move_uploaded_file($file['tmp_name'], $userDataDir . '/' . 'logo.png'))
				{
					$ajax->addDanger('Can not upload logo')->fail()->respond();
				}

				$table->params->logo = 'logo.png';
			}

			if ($table->save())
			{
				$session = Vr360Session::getInstance();
				$session->set('user', $table);

				$ajax->addSuccess('User profile is updated')->success()->respond();
			}
		}


		$ajax->addDanger('Can not update profile')->fail()->respond();
	}
}
