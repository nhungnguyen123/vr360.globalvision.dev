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
		$this->params->{$property} = $value;
	}

	/**
	 * @return array|mixed
	 */
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

			if (!is_array($panos) || empty($panos))
			{
				return array();
			}

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
		$thumbnail['file'] = '/_/' . $this->id . '/vtour/panos/' . $panoThumbnail;
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

		$filePath = Vr360HelperFile::clean($this->getDir() . '/' . $file);

		if (!Vr360HelperFile::exists($filePath))
		{
			return false;
		}

		return $filePath;
	}

	public function getDir()
	{
		return VR360_PATH_DATA . '/' . $this->id;
	}

	public function getKrpanoJsUrl()
	{
		return './_/' . $this->id . '/vtour/tour.js';
	}

	public function getKrpanoSwfUrl()
	{
		return './_/' . $this->id . '/vtour/tour.swf';
	}

	public function getKrpanoEmbedPano()
	{
		$embed      = new stdClass;
		$embed->swf = $this->getKrpanoSwfUrl();
		// @TODO Verify this file exists or not or use t.xml instead ( old method )
		$embed->xml                 = '_/' . $this->id . '/vtour/tour.xml';
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
		$dataDir = Vr360HelperFile::clean($this->getDir());

		if (!Vr360HelperFolder::exists($dataDir))
		{
			return false;
		}

		$tourDir = Vr360HelperFile::clean($dataDir . '/vtour');

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
		if (!Vr360HelperFile::exists(Vr360HelperFile::clean($this->getDir() . '/vtour/skin/vtourskin.xml')))
		{
			return false;
		}

		/*if (!Vr360HelperFile::exists(Vr360HelperFile::clean($this->getDir() . '/vtour/skin/tour-vtskin.xml')))
		{
			return false;
		}

		if (!Vr360HelperFile::exists(Vr360HelperFile::clean($this->getDir() . '/vtour/skin/social-skin.xml')))
		{
			return false;
		}*/

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

	public function getKrpanoVersion()
	{
		$xml = $this->getXml();

		if ($xml)
		{
			$nodes = $xml->getNodes();
			return $nodes['@attributes']['version'];
		}

		return 'Invalid';
	}

	/**
	 * Method for get all scenes of tour
	 *
	 * @return   array|false  Array of scenes. False otherwise.
	 *
	 * @since  3.0.0
	 */
	public function getScenes()
	{
		if (!$this->id)
		{
			return false;
		}

		$items = Vr360Database::getInstance()->select(
			'v2_scenes',
			array(
				'id', 'tourId', 'name', 'description', 'file', 'ordering', 'status', 'default', 'params'
			),
			array(
				'status[!]' => VR360_TOUR_STATUS_UNPUBLISHED,
				'tourId'    => $this->id,
				'ORDER'     => array('ordering' => 'ASC')
			)
		);

		if (empty($items))
		{
			return false;
		}

		foreach ($items as $key => $item)
		{
			$scene          = new Vr360Scene;
			$item['params'] = json_decode($item['params']);
			$scene->bind($item);
			$items[$key] = $scene;
		}

		return $items;
	}

	public function getXml()
	{
		$tourXml = $this->getFile('vtour/tour.xml');

		if (Vr360HelperFile::exists($tourXml))
		{
			$xml = new Vr360TourXml;
			$xml->load($tourXml);

			return $xml;
		}

		return false;
	}

	/**
	 * Delete tour
	 *
	 * @return  boolean
	 *
	 * @since   3.0.0
	 */
	public function delete()
	{
		if (!$this->id)
		{
			return false;
		}

		if (!Vr360Database::getInstance()->delete('v2_tours', array('id' => $this->id)))
		{
			return false;
		}

		$scenes = $this->getScenes();

		if (!empty($scenes))
		{
			foreach ($scenes as $scene)
			{
				/** @var  Vr360Scene $scene */
				$scene->delete();
			}
		}

		Vr360HelperFolder::delete(Vr360HelperFile::clean(VR360_PATH_DATA . '/' . $this->id));

		return true;
	}
}
