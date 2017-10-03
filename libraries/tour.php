<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Tour
 *
 * @since  2.0.0
 */
class Vr360Tour extends Vr360TableTour
{
	/**
	 * @return string
	 */
	public function getName()
	{
		return trim($this->name);
	}

	/**
	 * @param   int $truncateLength
	 *
	 * @return  string
	 */
	public function getDescription($truncateLength = 300)
	{
		$description = !empty($this->description) ? $this->description : Vr360Configuration::getConfig('siteDescription');

		return mb_substr($description, 0, $truncateLength);
	}

	/**
	 * @param      $property
	 * @param null $default
	 *
	 * @return null
	 */
	public function getParam($property, $default = null)
	{
		if (!is_object($this->params) || !isset($this->params->$property))
		{
			return $default;
		}

		return $this->params->$property;
	}

	/**
	 * @return array
	 */
	public function getPanos()
	{
		return $this->getParam('panos', array());
	}

	/**
	 * @return bool|mixed
	 */
	public function getDefaultPano()
	{
		$panos = $this->getPanos();

		// There are no panos
		if (empty($panos))
		{
			return false;
		}

		$defaultPano = $this->getParam('defaultPano', false);

		if ($defaultPano)
		{
			foreach ($panos as $pano)
			{
				if ($pano->file === $defaultPano)
				{
					return $pano;
				}
			}
		}

		return $panos[0];
	}

	/**
	 * @param $pano
	 *
	 * @return string
	 */
	protected function getTiles($pano)
	{
		$fileInfo = pathinfo($pano->file);

		return $fileInfo['filename'] . '.tiles';
	}

	/**
	 * @return array
	 */
	public function getThumbnail()
	{
		$defaultPano = $this->getDefaultPano();

		if ($defaultPano === false)
		{
			return false;
		}

		// Get title
		$tiles = $this->getTiles($defaultPano);

		$thumbnail         = array();
		$thumbnail['file'] = '/_/' . $this->dir . '/vtour/panos/' . $tiles . '/thumb.jpg';
		$thumbnail['url']  = VR360_URL_ROOT . $thumbnail['file'];
		$thumbnail['alt']  = $defaultPano->title;

		if (Vr360Configuration::getConfig('user_thumb_dimension', true))
		{
			$thumbnailFile = $this->getDir() . '/vtour/panos/' . $tiles . '/thumb.jpg';

			// @TODO Store dimension for next time use without calc again

			if (Vr360HelperFile::exists($thumbnailFile))
			{
				$imageSize           = getimagesize($thumbnailFile);
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

	public function getKrpanoJsUrl()
	{
		return VR360_URL_ROOT . '/_/' . $this->dir . '/vtour/tour.js';
	}

	public function getKrpanoSwfUrl()
	{
		return VR360_URL_ROOT . '/_/' . $this->dir . '/vtour/tour.swf';
	}

	public function getKrpanoEmbedPano()
	{
		$embed      = new stdClass;
		$embed->swf = $this->getKrpanoSwfUrl();
		// @TODO Verify this file exists or not or use t.xml instead ( old method )
		$embed->xml                 = '_/' . $this->dir . '/vtour/tour.xml';
		$embed->target              = 'pano';
		$embed->html5               = 'auto';
		$embed->mobilescale         = '1.0';
		$embed->passQueryParameters = 'true';

		return 'embedpano(' . json_encode($embed) . ');';
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

	public function isValidForRender()
	{
		if (!Vr360HelperFile::exists(VR360_PATH_ROOT . '/krpano/viewer/skin/vtourskin.xml'))
		{
			return false;
		}

		if (!Vr360HelperFile::exists(VR360_PATH_ROOT . '/krpano/viewer/skin/tour-vtskin.xml'))
		{
			return false;
		}

		if (!Vr360HelperFile::exists(VR360_PATH_ROOT . '/krpano/viewer/skin/social-skin.xml'))
		{
			return false;
		}

		return true;
	}

	public function canEmbed()
	{
		return $this->isValid() && (int) $this->status === VR360_TOUR_STATUS_PUBLISHED_READY;
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

	public function migrate()
	{
		$oldJsonData = $this->getJsonData();

		// Old format
		if ($this->params == null || isset($oldJsonData['editID']))
		{
			$newParams        = new stdClass;
			$newParams->files = array();
			$newParams->panos = array();

			foreach ($oldJsonData['panoList'] as $index => $pano)
			{
				$parts                      = explode('/', $pano['currentFileName']);
				$newPanoData['file']        = end($parts);
				$newPanoData['title']       = $pano['des'];
				$newPanoData['description'] = $pano['des'];

				$newParams->panos[] = $newPanoData;

				if ($index == $oldJsonData['defaultScene'])
				{
					$newParams->defaultPano = $newPanoData['file'];
				}

				$newParams->files[] = $newPanoData['file'];
			}

			$this->params = json_decode(json_encode($newParams));

			// Save to database
			$this->save();

			// Write back to data.json
			$jsonFile = $this->getFile('data.json');
			$handler  = fopen($jsonFile, 'w');

			if ($handler)
			{
				fwrite($handler, json_encode($newParams));
			}

			// Old XML format
			$xmlFile = $this->getFile('vtour/t.xml');

			if (!Vr360HelperFile::exists($xmlFile))
			{
				$xmlFile = $this->getFile('vtour/tour.xml');
			}

			$xmlBuffer = Vr360HelperFile::read($xmlFile);
			$xmlBuffer = str_replace('<include url="http://data.globalvision.ch/krpano/1.19/skin/vtourskin.xml" />', '<include url="/krpano/viewer/skin/vtourskin.xml" />', $xmlBuffer);
			$xmlBuffer = str_replace('<include url="http://data.globalvision.ch/krpano/1.19/skin/tour-vtskin.xml" />', '<include url="/krpano/viewer/skin/tour-vtskin.xml" />', $xmlBuffer);
			$xmlBuffer = str_replace('<include url="http://data.globalvision.ch/krpano/1.19/skin/social-skin.xml" />', '<include url="/krpano/viewer/skin/social-skin.xml" />', $xmlBuffer);

			// Write back to XML
			$handler = fopen($xmlFile, 'w');

			if ($handler)
			{
				fwrite($handler, $xmlBuffer);
			}

			// Copy files
			$src = VR360_PATH_ROOT . '/krpano/viewer/plugins';
			$dst = VR360_PATH_ROOT . '/_/' . $this->dir . '/vtour/plugins';

			$files = glob($src . "/*.*");

			foreach ($files as $file)
			{
				copy($file, str_replace($src, $dst, $file));
			}

			// Update new krpano scripts
			copy(VR360_PATH_ROOT . '/krpano/licenses/tour.js', VR360_PATH_ROOT . '/_/' . $this->dir . '/vtour/tour.js');
			copy(VR360_PATH_ROOT . '/krpano/licenses/tour.swf', VR360_PATH_ROOT . '/_/' . $this->dir . '/vtour/tour.swf');

			// Clean up
			Vr360HelperFile::delete($this->getDir() . '/kr.log.err.html');
			Vr360HelperFile::delete($this->getDir() . '/kr.log.html');
			Vr360HelperFile::delete($this->getDir() . '/php.mail.log.html');
		}
	}

	/**
	 * @return string
	 */
	public function getKrpanoVersion()
	{
		$xmlFile = $this->getFile('vtour/tour.xml');

		if ($xmlFile)
		{
			$simpleXml = simplexml_load_file($xmlFile);

			return (string) $simpleXml->attributes()->version;
		}

		return 'Invalid';
	}
}
