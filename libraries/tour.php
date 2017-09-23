<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Tour
 *
 * @since  2.0.0
 */
class Vr360Tour extends Vr360TableTour
{
	public function getName()
	{
		return trim($this->name);
	}

	public function getDefaultThumbnail()
	{
		$jsonData    = $this->getJsonData();
		$defaultPano = $jsonData['files'][0];

		$fileInfo = pathinfo($defaultPano);

		// Temporary code for B/C with old tour
		if (!isset($fileInfo['filename']) || empty($fileInfo['filename']))
		{
			$fileInfo['filename'] = 1;
		}

		$thumbnail         = array();
		$thumbnail['file'] = '/_/' . $this->dir . '/vtour/panos/' . $fileInfo['filename'] . '.tiles/thumb.jpg';
		$thumbnail['alt']  = $this->getDescription(150);

		if (Vr360Configuration::getConfig('user_thumb_dimension', true))
		{
			$thumbnailFile = $this->getDir() . '/vtour/panos/' . $fileInfo['filename'] . '.tiles/thumb.jpg';
			if (Vr360HelperFile::exists($thumbnailFile))
			{
				$imageSize           = getimagesize($this->getDir() . '/vtour/panos/' . $fileInfo['filename'] . '.tiles/thumb.jpg');
				$thumbnail['width']  = $imageSize[0];
				$thumbnail['height'] = $imageSize[1];
				$thumbnail['mime']   = $imageSize['mime'];
			}
		}

		return $thumbnail;
	}

	/**
	 * @return bool|array
	 */
	public function getJsonData()
	{
		$filePath = $this->getFile('data.json');

		if ($filePath === false)
		{
			return false;
		}

		$jsonContent = Vr360HelperFile::read($filePath);

		return json_decode($jsonContent, true);
	}

	public function getFile($file)
	{
		// @TODO Need clean file path to prevent path travel attacking

		$filePath = $this->getDir() . '/' . $file;

		if (!Vr360HelperFile::exists($filePath))
		{
			return false;
		}

		return $filePath;
	}

	public function getDir()
	{
		return VR360_PATH_DATA . '/' . $this->dir;
	}

	public function getDescription($truncate = 300)
	{
		$description = !empty($this->description) ? $this->description : Vr360Configuration::getConfig('siteDescription');

		return mb_substr($description, 0, $truncate);
	}

	public function canEmbed()
	{
		return $this->isValid() && (int) $this->status === VR360_TOUR_STATUS_PUBLISHED_READY;
	}

	/**
	 * @return bool
	 */
	public function isValid()
	{
		$dataDir = $this->getDir();

		if (!Vr360HelperFolder::exists($dataDir))
		{
			return false;
		}

		$tourDir = $dataDir . '/vtour';

		if (!Vr360HelperFolder::exists($tourDir))
		{
			return false;
		}

		return true;
	}

	public function canEdit()
	{
		return $this->isValid() && (int) $this->status === VR360_TOUR_STATUS_PUBLISHED_READY;
	}

	public function canEditHotspot()
	{
		return $this->isValid() && (int) $this->status === VR360_TOUR_STATUS_PUBLISHED_READY;
	}

	public function canPreview()
	{
		return $this->isValid() && (int) $this->status === VR360_TOUR_STATUS_PUBLISHED_READY;
	}
}
