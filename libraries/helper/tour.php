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
			return false;
		}

		if (!in_array(mime_content_type($filePath), Vr360Configuration::getConfig('allowMimeTypes')))
		{
			return false;
		}

		//!!check image size  x*2x size
		$imgSize = getimagesize($filePath);

		if (!($imgSize[0] == 2 * $imgSize[1]))
		{
			return false;
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

	public static function generateUId()
	{
		$uId = uniqid('__', false);

		return $uId . '_' . md5(uniqid('', true));
	}

	/**
	 * @param $uId
	 * @param $jsonData
	 *
	 * @return string
	 */
	public static function generateTour($uId, $jsonData)
	{
		$pre_img_dir = "./_/$uId/";

		if (is_file("$pre_img_dir/vtour/tour.xml")) unlink("$pre_img_dir/vtour/tour.xml");

		$krPanoPath      = './assets/krpano/krpanotools ';
		$krPanoCongig    = 'makepano -config=./assets/krpano/templates/vtour-normal.config ';
		$krPanoListImage = $pre_img_dir . implode(" $pre_img_dir", $jsonData['files']);

		// Generate tour via exec
		return exec($krPanoPath . $krPanoCongig . $krPanoListImage);
	}

	/**
	 * @param $uId
	 * @param $jsonData
	 *
	 * @return bool|int
	 */
	public static function generateXml($uId, $jsonData)
	{
		$tagetXmlFile = "./_/$uId/vtour/tour.xml";

		$xmlData = [];

		// dont change the order of this array.
		// if you change the order, maybe you will have foot on your head
		$xmlData['header'] = [];
		$xmlData['scenes'] = [];
		$xmlData['footer'] = [];

		//assign xmlData to array

		foreach ($jsonData['files'] as $scene => $fileName)
		{
			$xmlData['scenes'][$scene] = [];
			$curentScene               = &$xmlData['scenes'][$scene];

			$curentScene['xmlFileName'] = explode('.', $fileName)[0];
			$curentScene['xmlTitle']    = $jsonData['panoTitle'][$scene];
			$curentScene['xmlHotspots'] = self::xmlHotspots($jsonData, $curentScene['xmlFileName']); //we will make hotspots later
		}

		//write xmlData to xml Template

		$tagetXmlFileContents = '';

		foreach ($xmlData as $template => $data) //thought file
		{
			$tmpStr = file_get_contents("./assets/krpano-$template.xml");

			foreach ($data as $key => $keyData) //thought key
			{
				if (gettype($keyData) == 'string')
				{
					$tmpStr = preg_replace("/\{\{$key\}\}/", $keyData, $tmpStr);
				}
				elseif (gettype($keyData) == 'array') //okie array will be like scenes, loop thought all template, I know this not perfect but this is the best I can do at this times.
				{
					$tmpStr = file_get_contents("./assets/krpano-$template.xml");
					foreach ($keyData as $subKey => $subKeyData) //thought key
					{
						$tmpStr = preg_replace("/\{\{$subKey\}\}/", $subKeyData, $tmpStr);
					}
					$tagetXmlFileContents .= $tmpStr;
					$tmpStr               = '';
				}
				else
				{
					//500 error, wrong data format or empty
				}

			}
			$tagetXmlFileContents .= $tmpStr;
		}

		return file_put_contents($tagetXmlFile, $tagetXmlFileContents); // need to check if cant overwrite
	}

	private static function xmlHotspot ($hotspotObj)
	{
		$h = $hotspotObj;
		return "<hotspot name='spot_$h->hotspotID' dataId='{{dataId}}' style='skin_hotspotstyle|$h->style' ath='$h->ath' atv='$h->atv' hotspot_type='$h->hotspot_type' $h->data /> \n";
	}

	private static function xmlHotspots($jsonData, $xmlFileName)
	{
		$returnValue = '';
		@foreach ($jsonData['hotspotList'] as $scene => $value)
		{
			$file = str_replace('scene_', '', $scene);
			if($file == $xmlFileName)
			{
				$hotspotObj = new stdClass();
				foreach ($scene as $hotspotID => $hotspot)
				{
					@$hotspotObj->hotspotID   = $hotspotID;
					@$hotspotObj->ath         = $hotspot['ath'];
					@$hotspotObj->atv         = $hotspot['atv'];

					if ($hotspot['hotspot_type'] == 'normal')
					{
						@$hotspotObj->style = 'tooltip';
						@$hotspotObj->data  = 'linkedscene="' . $hotspot['linkedscene'] . '"';
					}
					elseif ($hotspot['hotspot_type'] == 'text')
					{
						@$hotspotObj->style = 'textpopup';
						@$hotspotObj->data  = 'hotspot_text="' . $hotspot['hotspot_text'] . '"';
					}
					$returnValue .= xmlHotspot($hotspotObj);
				}
				return $returnValue;
				// break;
			}
		}
		return ''; // if no hotspot found.
	}
}
