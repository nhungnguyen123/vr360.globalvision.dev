<?php

defined('_VR360_EXEC') or die;

class Vr360Template
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

	public function fetch($templateName = 'default')
	{
		ob_start();
		require_once VR360_PATH_TEMPLATES . DIRECTORY_SEPARATOR . $templateName . DIRECTORY_SEPARATOR . 'index.php';

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}