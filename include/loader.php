<?php

class Vr360Loader
{
	public static function autoload()
	{
		spl_autoload_register(function ($className)
		{
			$parts = preg_split('/(?=[A-Z])/', $className, -1, PREG_SPLIT_NO_EMPTY);

			$prefix = array_shift($parts);

			switch ($prefix)
			{
				case 'Vr360':
					require_once strtolower(VR360_PATH_LIBRARIES . '/' . implode('/' , $parts) . '.php');
					break;
			}
		});

	}
}