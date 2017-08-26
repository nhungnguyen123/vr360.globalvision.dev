<?php

class Vr360Layout
{
	public static function load($layout, $data = array())
	{
		require_once VR360_PATH_ROOT . '/layouts/' . str_replace('.', DIRECTORY_SEPARATOR, $layout) . '.php';
	}
}