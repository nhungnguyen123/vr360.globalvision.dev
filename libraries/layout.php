<?php

class Vr360Layout
{
	public static function load($layout, $data = array())
	{
		extract($data);
		require VR360_PATH_ROOT . '/layouts/' . str_replace('.', DIRECTORY_SEPARATOR, $layout) . '.php';
	}
}