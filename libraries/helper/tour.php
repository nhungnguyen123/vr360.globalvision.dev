<?php

class Vr360HelperTour
{
	public static function fileValidate($filePath)
	{
		if (!file_exists($filePath))
		{
			return false;
		}

		if (filesize($filePath) < (5 * 1024 * 1024))
		{
			return false;
		}

		return true;
	}
}