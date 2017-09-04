<?php

defined('_VR360') or die;

/**
 * Class Vr360HelperFolder
 */
class Vr360HelperFolder
{

	/**
	 * @param $dirPath
	 */
	public static function delete($dirPath)
	{
		if (is_dir($dirPath))
		{
			//GLOB_MARK adds a slash to directories returned
			$files = glob($dirPath . '*', GLOB_MARK);

			foreach ($files as $file)
			{
				self::delete($file);
			}

			if (file_exists($dirPath))
			{
				rmdir($dirPath);
			}
		}
		elseif (is_file($dirPath))
		{
			unlink($dirPath);
		}
	}
}