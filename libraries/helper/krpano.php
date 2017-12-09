<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360HelperKrpano
 *
 * @since  2.1.0
 */
class Vr360HelperKrpano
{
	public static function getListOfSkins()
	{
		return Vr360HelperFolder::files(VR360_PATH_ASSETS . '/vendor/krpano/skins');
	}

	public static function checkFile($file)
	{
		$filePath = VR360_PATH_ASSETS . '/vendor/krpano/' . $file;

		return Vr360HelperFile::exists($filePath);
	}

	public static function getIncludes()
	{
		$includes = array('vtourskin.xml', 'tour-vtskin.xml');

		return $includes;
	}
}
