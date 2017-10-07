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

		return trim(mb_substr($description, 0, $truncateLength));
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
	 * @param $property
	 * @param $value
	 */
	public function setParam($property, $value)
	{
		$this->params->$property = $value;
	}

	public function getHotspots()
	{
		$jsonData = $this->getJsonData();

		if (isset($jsonData['hotspotList']))
		{
			return $jsonData['hotspotList'];
		}

		return array();
	}

	/**
	 * @return array
	 */
	public function getPanos()
	{
		static $panos;

		if (!isset($panos))
		{
			$panos = $this->getParam('panos', array());

			foreach ($panos as $index => $pano)
			{
				$panos[$index] = new Vr360Pano($pano);
			}
		}

		return $panos;
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

		// By default return first pano
		return $panos[0];
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

		$panoThumbnail = $defaultPano->getThumbnail();

		if ($panoThumbnail === false)
		{
			return false;
		}

		$thumbnail         = array();
		$thumbnail['file'] = '/_/' . $this->dir . '/vtour/panos/' . $panoThumbnail;
		$thumbnail['url']  = VR360_URL_ROOT . $thumbnail['file'];
		$thumbnail['alt']  = $defaultPano->title;

		if (Vr360Configuration::getConfig('user_thumb_dimension', true))
		{
			$thumbnailFile = $this->getDir() . '/vtour/panos/' . $panoThumbnail;

			// No need to cache this because we already have whole tour view caching
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
		return './_/' . $this->dir . '/vtour/tour.js';
	}

	public function getKrpanoSwfUrl()
	{
		return './_/' . $this->dir . '/vtour/tour.swf';
	}

	public function getKrpanoEmbedPano()
	{
		$embed      = new stdClass;
		$embed->swf = $this->getKrpanoSwfUrl();
		// @TODO Verify this file exists or not or use t.xml instead ( old method )
		$embed->xml                 = '_/' . $this->dir . '/vtour/tour.xml';
		$embed->target              = 'pano';
		$embed->html5               = Vr360Configuration::getConfig('krPanoEmbedHtml5', 'auto');
		$embed->mobilescale         = '1.0';
		$embed->passQueryParameters = 'true';

		return 'embedpano(' . json_encode($embed) . ');';
	}

	/**
	 * @return boolean
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

	/**
	 * @return bool
	 */
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

	/**
	 * @return boolean
	 */
	public function canEmbed()
	{
		return $this->isValid();
	}

	/**
	 * @return boolean
	 */
	public function canEdit()
	{
		return $this->isValid();
	}

	/**
	 * @return boolean
	 */
	public function canEditHotspot()
	{
		return $this->isValid();
	}

	/**
	 * @return boolean
	 */
	public function canPreview()
	{
		return $this->isValid();
	}

	/**
	 * Migrate from old json format
	 */
	public function migrate()
	{

		$oldJsonData = $this->getJsonData();

		// Old format
		if (
			$this->params == null ||
			isset($oldJsonData['editID']) ||
			isset($oldJsonData['jsonData']) ||
			isset($oldJsonData['email'])
		)
		{
			// Prepare new params
			$newParams = new stdClass;

			// List of files. Actually we'll not use this
			$newParams->files = array();
			// List of panos
			$newParams->panos = array();
			$newHotspots = array();

			if (isset($oldJsonData['panoList']))
			{
				// Work on old panos list
				foreach ($oldJsonData['panoList'] as $index => $pano)
				{
					$parts = explode('/', $pano['currentFileName']);

					// Store filename only
					$newPanoData['file']        = end($parts);
					$newPanoData['title']       = $pano['des'];
					$newPanoData['description'] = $pano['des_sub'];

					// Store list of panos
					$newParams->panos[] = $newPanoData;

					// Store default pano
					if ($index == $oldJsonData['defaultScene'])
					{
						$newParams->defaultPano = $newPanoData['file'];
					}

					// Store list of files
					$newParams->files[] = $newPanoData['file'];
				}

				$this->params = json_decode(json_encode($newParams));

				// Save to database
				//$this->save();

				copy($this->getFile('data.json'), $this->getFile('data.json') . '.bak');

				// Write back to data.json
				Vr360HelperFile::write($this->getFile('data.json'), json_encode($newParams));
			}
		}

		$this->migrateXml();
		$this->updateKrpano();
		$this->cleanup();
	}

	protected function migrateXml()
	{
		// Old XML format
		$xmlFile = $this->getFile('vtour/t.xml');

		if (!Vr360HelperFile::exists($xmlFile))
		{
			$xmlFile = $this->getFile('vtour/tour.xml');
		}

		// Replace include XML to correct link
		$xmlBuffer = Vr360HelperFile::read($xmlFile);
		$xmlBuffer = str_replace('<include url="http://data.globalvision.ch/krpano/1.19/skin/vtourskin.xml" />', '<include url="/krpano/viewer/skin/vtourskin.xml" />', $xmlBuffer);
		$xmlBuffer = str_replace('<include url="http://data.globalvision.ch/krpano/1.19/skin/tour-vtskin.xml" />', '<include url="/krpano/viewer/skin/tour-vtskin.xml" />', $xmlBuffer);
		$xmlBuffer = str_replace('<include url="http://data.globalvision.ch/krpano/1.19/skin/social-skin.xml" />', '<include url="/krpano/viewer/skin/social-skin.xml" />', $xmlBuffer);

		$xmlBuffer = str_replace('<include url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/vtourskin.xml" />', '<include url="/krpano/viewer/skin/vtourskin.xml" />', $xmlBuffer);
		$xmlBuffer = str_replace('<include url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/tour-vtskin.xml" />', '<include url="/krpano/viewer/skin/tour-vtskin.xml" />', $xmlBuffer);
		$xmlBuffer = str_replace('<include url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/social-skin.xml" />', '<include url="/krpano/viewer/skin/social-skin.xml" />', $xmlBuffer);

		// Write back to XML
		Vr360HelperFile::write($xmlFile, $xmlBuffer);
	}

	protected function cleanup()
	{
		// Clean up
		Vr360HelperFile::delete($this->getDir() . '/kr-tool.sh');
		Vr360HelperFile::delete($this->getDir() . '/kr.log.err.html');
		Vr360HelperFile::delete($this->getDir() . '/kr.log.html');
		Vr360HelperFile::delete($this->getDir() . '/php.mail.log.html');
		Vr360HelperFile::delete($this->getDir() . '/tour_testingserver.exe');
		Vr360HelperFile::delete($this->getDir() . '/tour_testingserver_macos');
	}

	protected function updateKrpano()
	{
		// Copy new files
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
