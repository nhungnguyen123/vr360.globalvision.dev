<?php

defined('_VR360_EXEC') or die;

spl_autoload_register(function ($className) {
	$prefix = 'Vr360';
	$parts  = preg_split('/(?=[A-Z])/', $className, -1, PREG_SPLIT_NO_EMPTY);

	$classPrefix = array_shift($parts);

	if ($classPrefix == $prefix)
	{
		$filePath = strtolower(VR360_PATH_LIBRARIES . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts) . '.php');;

		if (is_file($filePath) && file_exists($filePath))
		{
			require_once $filePath;
		}

	}
});

if (!file_exists(VR360_PATH_ROOT . DIRECTORY_SEPARATOR . 'configuration.php'))
{
	echo 'Configuration file is not ready to use';
	die;
}

require_once VR360_PATH_ROOT . DIRECTORY_SEPARATOR . 'configuration.php';
require_once VR360_PATH_VENDOR . DIRECTORY_SEPARATOR . 'autoload.php';

Vr360Session::getInstance()->start();