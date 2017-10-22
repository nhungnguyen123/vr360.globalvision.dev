<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Pano
 */
class Vr360Pano extends Vr360Object
{

	public function getTilesDir()
	{
		$file = $this->get('file');

		if ($file)
		{
			if (Vr360HelperFile::exists($file))
			{
				$fileInfo = pathinfo($file);

				return $fileInfo['filename'] . '.tiles';
			}
		}

		return false;
	}

	public function getThumbnail()
	{
		$tiles = $this->getTilesDir();

		if ($tiles)
		{
			return $tiles . '/thumb.jpg';
		}

		return false;
	}
}
