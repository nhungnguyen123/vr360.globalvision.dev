<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360HelperFolder
 */
class Vr360HelperFile
{

	public static function copy($src, $dest)
	{
		if (!self::exists($src))
		{
			return false;
		}

		return copy($src, $dest);
	}

	/**
	 * @param $filePath
	 *
	 * @return bool
	 */
	public static function exists($filePath)
	{
		return (file_exists($filePath) && is_file($filePath));
	}
}