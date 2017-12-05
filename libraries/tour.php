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
	 * @param   integer  $truncateLength  Truncate length
	 *
	 * @return  string
	 */
	public function getDescription($truncateLength = 300)
	{
		$description = !empty($this->description) ? $this->description : Vr360Configuration::getConfig('siteDescription');

		return trim(mb_substr($description, 0, $truncateLength));
	}

	/**
	 * @return  string|null
	 */
	public function getKeyword()
	{
		return !empty($this->keyword) ? $this->keyword : Vr360Configuration::getConfig('siteKeyword');
	}

	/**
	 * @return array|boolean
	 */
	public function getThumbnail()
	{
		$scenes = $this->getScenes();
		$scene  = $scenes[0];

		$pathInfo = pathinfo($scene->file);
		$filePath = VR360_PATH_DATA . '/' . $scene->tourId . '/vtour/panos/' . $pathInfo['filename'] . '.tiles/thumb.jpg';

		if (Vr360HelperFile::exists($filePath))
		{
			$imageSize           = getimagesize($filePath);
			$thumbnail['file']   = '/_/' . $this->id . '/vtour/panos/' . $pathInfo['filename'] . '.tiles/thumb.jpg';
			$thumbnail['url']    = VR360_URL_ROOT . $thumbnail['file'];
			$thumbnail['alt']    = $scene->name;
			$thumbnail['width']  = $imageSize[0];
			$thumbnail['height'] = $imageSize[1];
			$thumbnail['mime']   = $imageSize['mime'];

			return $thumbnail;
		}

		return false;
	}

	/**
	 * @param $file
	 *
	 * @return boolean|string
	 */
	public function getFile($file)
	{
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

	/**
	 * @return string
	 */
	public function getKrpanoEmbedPano()
	{
		$embed                      = new stdClass;
		$embed->swf                 = $this->getKrpanoSwfUrl();
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
	 * @return boolean
	 */
	public function isValidForRender()
	{
		if (!Vr360HelperFile::exists(Vr360HelperFile::clean($this->getDir() . '/vtour/tour.xml')))
		{
			return false;
		}

		if (!Vr360HelperKrpano::checkFile('viewer/skin/vtourskin.xml'))
		{
			return false;
		}

		if (!Vr360HelperKrpano::checkFile('viewer/skin/tour-vtskin.xml'))
		{
			return false;
		}

		if (!Vr360HelperKrpano::checkFile('viewer/skin/social-skin.xml'))
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
	 * @return string
	 */
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
			'scenes',
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
			$item['params'] = new Vr360Object(json_decode($item['params']));
			$scene->bind($item);
			$items[$key] = $scene;
		}

		return $items;
	}

	/**
	 * @return integer
	 */
	public function getHotspots()
	{
		$scenes = $this->getScenes();

		$count = 0;

		foreach ($scenes as $scene)
		{
			$hotspots = $scene->getHotspots();

			if (!$hotspots)
			{
				continue;
			}

			$count = $count + count($hotspots);
		}

		return $count;
	}

	/**
	 * @return boolean|Vr360TourXml
	 */
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
	 * @since   2.1.0
	 */
	public function delete()
	{
		if (!$this->id)
		{
			return false;
		}

		if (!Vr360Database::getInstance()->delete('tours', array('id' => $this->id)))
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

	public function hit()
	{
		$hits = $this->hit + 1;
		Vr360Database::getInstance()->update('tours', array('hits' => $hits), array('id' => $this->id));
	}
}
