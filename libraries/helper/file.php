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
	 * @param   string $filePath File path
	 *
	 * @return  boolean
	 */
	public static function exists($filePath)
	{
		return (file_exists($filePath) && is_file($filePath));
	}

	/**
	 * @param $filePath
	 *
	 * @return bool|string
	 */
	public static function read($filePath)
	{
		if (self::exists($filePath))
		{
			return file_get_contents($filePath);
		}

		return false;
	}
}
