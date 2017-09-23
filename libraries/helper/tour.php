<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360HelperTour
 */
class Vr360HelperTour
{
	/**
	 * @param $filePath
	 *
	 * @return bool
	 */
	public static function fileValidate($filePath)
	{
		if (!file_exists($filePath))
		{
			return 'File not found: ' . $filePath;
		}

		if (!in_array(mime_content_type($filePath), Vr360Configuration::getConfig('allowMimeTypes')))
		{
			return 'Invalid file mime';
		}

		// Check image size  x*2x size
		$imgSize = getimagesize($filePath);

		if (!($imgSize[0] == 2 * $imgSize[1]))
		{
			return 'Invalid file dimension';
		}

		return true;
	}

	/**
	 * @param $fileName
	 *
	 * @return string
	 */
	public static function generateFilename($fileName)
	{
		//$fileName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $fileName);
		//$fileName = mb_ereg_replace("([\.]{2,})", '', $fileName);
		$md5 = md5(uniqid('sth', true));
		$md5 = substr($md5, 0, 12);

		return $md5 . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
	}

	/**
	 * @return bool|string
	 */
	public static function createDataDir()
	{
		// Create data directory first
		if (!file_exists(VR360_PATH_DATA))
		{
			mkdir(VR360_PATH_DATA);
		}

		$uId = Vr360HelperTour::generateUId();

		if (!mkdir(VR360_PATH_DATA . '/' . $uId))
		{
			return false;
		}

		return $uId;
	}

	/**
	 * @return string
	 */
	public static function generateUId()
	{
		$uId = uniqid('__', false);

		return $uId . '_' . md5(uniqid('', true));
	}

	/**
	 * @param   string $uId      Directory
	 * @param   array  $jsonData Json data
	 *
	 * @return string
	 */
	public static function generateTour($uId, $jsonData)
	{
		$preImageDir      = VR360_PATH_DATA . '/' . $uId . '/';
		$vTourXmlFilePath = $preImageDir . '/vtour/tour.xml';

		// Delete old tour.xml if exists
		if (Vr360HelperFile::exists($vTourXmlFilePath))
		{
			unlink($vTourXmlFilePath);
		}

		// There is no panos
		if (empty($jsonData['files']))
		{
			return false;
		}

		$files = array();

		foreach ($jsonData['files'] as $file)
		{
			$file = $preImageDir . $file;

			// Make sure file exists
			if (Vr360HelperFile::exists($file))
			{
				$files[] = $file;
			}
		}

		if (empty($files))
		{
			return false;
		}

		$krPanoPath      = './assets/krpano/krpanotools ';
		$krPanoCongig    = 'makepano -config=./assets/krpano/templates/vtour-normal.config ';
		$krPanoListImage = implode(' ', $files);

		// Generate tour via exec
		return exec($krPanoPath . $krPanoCongig . $krPanoListImage);
	}

	/**
	 * @param   string $uId      Directory
	 * @param   array  $jsonData Json data
	 *
	 * @return  boolean|integer
	 */
	public static function generateXml($uId, $jsonData)
	{
		$tourDataDirPath = VR360_PATH_DATA . '/' . $uId . '/vtour/';
		$tagetXmlFile    = $tourDataDirPath . "/tour.xml";

		$xmlData = array();

		/**
		 * Prepare xmlData
		 * Don't change the order of this array or you will have foot on your head
		 */
		$xmlData['header'] = array();
		$xmlData['scenes'] = array();
		$xmlData['footer'] = array();

		// Assign xmlData to array
		foreach ($jsonData['files'] as $scene => $fileName)
		{
			$xmlData['scenes'][$scene] = array();
			$xmlFileName               = explode('.', $fileName)[0];

			$xmlData['scenes'][$scene]['xmlFileName'] = $xmlFileName;
			$xmlData['scenes'][$scene]['xmlTitle']    = $jsonData['panoTitle'][$scene];
			$xmlData['scenes'][$scene]['xmlSubTitle'] = $jsonData['panoDescription'][$scene];

			if (isset($jsonData['defaultViewList']))
			{
				$xmlData['scenes'][$scene]['fov']         = $jsonData['defaultViewList']["scene_$xmlFileName"]['fov'];
				$xmlData['scenes'][$scene]['hlookat']     = $jsonData['defaultViewList']["scene_$xmlFileName"]['hlookat'];
				$xmlData['scenes'][$scene]['vlookat']     = $jsonData['defaultViewList']["scene_$xmlFileName"]['vlookat'];
			}
			$xmlData['scenes'][$scene]['xmlHotspots'] = self::xmlHotspots($jsonData, $xmlData['scenes'][$scene]['xmlFileName']);
		}
		// Write xmlData to xml Template
		$targetXmlFileContents = '';

		// Process xmlData
		foreach ($xmlData as $templateKey => $data)
		{
			$xmlTemplateFile = VR360_PATH_ASSETS . '/krpano-' . $templateKey . '.xml';
			$xmlContent      = Vr360HelperFile::read($xmlTemplateFile);

			foreach ($data as $key => $keyData)
			{
				if (gettype($keyData) == 'string')
				{
					$xmlContent = preg_replace("/\{\{$key\}\}/", $keyData, $xmlContent);
				}
				// Okie array will be like scenes, loop thought all template, I know this not perfect but this is the best I can do at this times.
				elseif (gettype($keyData) == 'array')
				{
					// @TODO We don't need read file again. Cache it somewhere
					$xmlContent = Vr360HelperFile::read($xmlTemplateFile);

					foreach ($keyData as $subKey => $subKeyData)
					{
						$xmlContent = preg_replace("/\{\{$subKey\}\}/", $subKeyData, $xmlContent);
					}

					$targetXmlFileContents .= $xmlContent;
					$xmlContent            = '';
				}
				else
				{
					// 500 error, wrong data format or empty
				}
			}

			$targetXmlFileContents .= $xmlContent;
		}

		return file_put_contents($tagetXmlFile, $targetXmlFileContents);
	}

	/**
	 * @param   object $hotspotObj
	 *
	 * @return string
	 */
	protected static function xmlHotspot($hotspotObj)
	{
		$h = $hotspotObj;

		return "<hotspot name='spot_$h->hotspotID' dataId='$h->hotspotID' style='skin_hotspotstyle|$h->style' ath='$h->ath' atv='$h->atv' hotspot_type='$h->hotspot_type' $h->data /> \n";
	}

	/**
	 * @param $jsonData
	 * @param $xmlFileName
	 *
	 * @return string
	 */
	protected static function xmlHotspots($jsonData, $xmlFileName)
	{
		$returnValue = '';

		if (!isset($jsonData['hotspotList']))
		{
			return $returnValue;
		}

		if (count($jsonData['hotspotList']) < 1)
		{
			return $returnValue;
		}

		foreach ($jsonData['hotspotList'] as $scene => $value)
		{
			$file = str_replace('scene_', '', $scene);

			if ($file == $xmlFileName)
			{
				$hotspotObj = new stdClass;

				foreach ($jsonData['hotspotList'][$scene] as $hotspotID => $hotspot)
				{
					$hotspotObj->hotspotID = $hotspotID;
					$hotspotObj->ath       = $hotspot['ath'];
					$hotspotObj->atv       = $hotspot['atv'];

					if ($hotspot['hotspot_type'] == 'normal')
					{
						$hotspotObj->style        = 'tooltip';
						$hotspotObj->hotspot_type = 'normal';
						$hotspotObj->data         = 'linkedscene="scene_' . explode('.', $jsonData['files'][$hotspot['linkedscene']])[0] . '"';
					}
					elseif ($hotspot['hotspot_type'] == 'text')
					{
						$hotspotObj->style        = 'textpopup';
						$hotspotObj->hotspot_type = 'text';
						$hotspotObj->data         = 'hotspot_text="' . $hotspot['hotspot_text'] . '"';
					}

					$returnValue .= self::xmlHotspot($hotspotObj);
				}
			}
		}

		return $returnValue;
	}
}
