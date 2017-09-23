<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Controller
 *
 * @since  2.0.0
 */
class Vr360Controller
{
	protected $defaultView = 'tours';

	protected $defaultTask = 'display';

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

	public function execute()
	{
		$view = Vr360Factory::getInput()->getString('view', $this->defaultView);
		$task = Vr360Factory::getInput()->getString('task', $this->defaultTask);

		$controllerClassname = 'Vr360Controller' . ucfirst($view);

		if (class_exists($controllerClassname))
		{
			$controllerClass = new $controllerClassname;

			return $controllerClass->$task();
		}
		else
		{
			$this->display();
		}

		return false;
	}

	/**
	 * @return  void
	 */
	public function display()
	{
		$view = Vr360Factory::getInput()->getString('view', $this->defaultView);

		if ($view != 'tour')
		{
			if (!Vr360HelperAuthorize::isAuthorized())
			{
				$view = 'user';
			}
		}

		$viewClassname = 'Vr360View' . ucfirst($view);

		if (class_exists($viewClassname))
		{
			$viewClass = new $viewClassname;

			$viewHtml = $viewClass->display();
		}
		else
		{
			$viewHtml = '';
		}

		if ($view == 'tour')
		{
			echo $viewHtml;
		}
		else
		{
			$template = Vr360Template::getInstance()->fetch();
			$template = str_replace('{content}', $viewHtml, $template);

			echo $template;
		}
	}

	/**
	 * @param   string $url Redirect URL
	 *
	 * @return  void
	 */
	public function redirect($url)
	{
		header("Location: " . $url);
		die();
	}
}
