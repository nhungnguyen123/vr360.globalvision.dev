<?php

defined('_VR360') or die;

/**
 * Class Vr360Layout
 */
class Vr360Layout
{
	public static function load($layout, $data = array())
	{
		extract($data);
		require VR360_PATH_ROOT . '/layouts/' . str_replace('.', DIRECTORY_SEPARATOR, $layout) . '.php';
	}

	public static function fetch($layout, $data = array())
	{
		ob_start();
		extract($data);
		require VR360_PATH_ROOT . '/layouts/' . str_replace('.', DIRECTORY_SEPARATOR, $layout) . '.php';

		$html =ob_get_contents();
		ob_end_clean();

		return $html;
	}
}