<?php

//maintainer: nhan@globalvision.ch
class dataVerObj
//create enviroment include variables and upload file for process
{
	public function __construct($uId, $authObj)
	{

		$this->uId          = $uId;
		$this->email        = $authObj->getUserEmail();
		$this->userFullName = $authObj->getUserFullName();
		$this->currentDir   = "./_/$uId/";
		mkdir($this->currentDir);
		$this->panoList            = array();
		$this->sh_PANAROMA_ARR_str = '';
		$this->xmlStr              = '';
		$this->numberOfPanaroma    = $_POST['panoUploadCountrer'];
		//$this->jsonData = json_decode($_POST['jsonData'], true);
		$this->jsonData = json_decode(str_replace('\\"', '"', $_POST['jsonData']), true);

		//var_dump(json_decode(str_replace('\\"', '"', $_POST['jsonData'])), true);

		$Str = $_POST['tour_des'];
		$Str = preg_replace('/"/', '&#34;', $Str);
		$Str = preg_replace('/\'/', '&#39;', $Str);
		$rtr = '/[`~!@#$%^&*\.,;?_":\\><|+\'\/\[\]\{\}\=\-]+/'; //20 char
		$mtr = '-';
		$Str = preg_replace($rtr, $mtr, $Str);

		$this->tourDes      = $Str;
		$tour_rotation      = $_POST['tour_rotation'];
		$tour_rotation      = preg_replace('/"/', '&#34;', $tour_rotation);
		$tour_rotation      = preg_replace('/\'/', '&#39;', $tour_rotation);
		$this->tourRotation = $tour_rotation;
		$tour_social        = $_POST['start_social'];
		$end_social         = $_POST['end_social'];
		$this->startSocial  = $_POST['start_social'];
		$this->endSocial    = $_POST['end_social'];

		$this->defaultScene = 1;

		foreach ($this->jsonData as $ikey => $i)
		{
			$this->panoList["$i"]                = array();
			$tmpFile                             = $_FILES['img' . $i . '_file']['tmp_name'];
			$this->panoList["$i"]['currentFile'] = $_FILES['img' . $i . '_file']['tmp_name'];
			/////
			if (strtolower(pathinfo($_FILES['img' . $i . '_file']['name'], PATHINFO_EXTENSION)) == "php")
			{
				die();
			}
			/////
			$this->panoList["$i"]["currentFileName"]    = $this->currentDir . $i . '.' . pathinfo($_FILES['img' . $i . '_file']['name'], PATHINFO_EXTENSION);//explode($_FILES['img'.$i.'_file']['name'], '.');
			$this->panoList["$i"]["currentFileNameExt"] = pathinfo($_FILES['img' . $i . '_file']['name'], PATHINFO_EXTENSION);//explode($_FILES['img'.$i.'_file']['name'], '.');

			$Str = $_POST['img' . $i . '_des'];
			$Str = preg_replace('/"/', '&#34;', $Str);
			$Str = preg_replace('/\'/', '&#39;', $Str);
			$rtr = '/[`~!@#$%^&*\.,;?_":\\><|+\'\/\[\]\{\}\=\-]+/'; //20 char
			$mtr = '-';
			$Str = preg_replace($rtr, $mtr, $Str);

			$this->panoList["$i"]['des'] = $Str;

			$Str = $_POST['img' . $i . '_des_sub'];
			$Str = preg_replace('/"/', '&#34;', $Str);
			$Str = preg_replace('/\'/', '&#39;', $Str);
			$rtr = '/[`~!@#$%^&*\.,;?_":\\><|+\'\/\[\]\{\}\=\-]+/'; //20 char
			$mtr = '-';
			$Str = preg_replace($rtr, $mtr, $Str);

			$this->panoList["$i"]['des_sub'] = $Str;

			$this->sh_PANAROMA_ARR_str .= $this->panoList["$i"]["currentFileName"] . " ";
			move_uploaded_file($tmpFile, $this->panoList["$i"]["currentFileName"]);

			//echo $this->panoList["$i"]["currentFileName"];
		}
		$dataFile = fopen($this->currentDir . 'data.json', 'w+');
		fwrite($dataFile, json_encode($this));
		fclose($dataFile);
	}
}

class xmlData
//create xml data for krpano
{
	public function __construct($dataVerObj)
	{
		$this->data     = $dataVerObj;
		$this->sceneTmp = <<<EOF
<scene name="scene_{{sceneName}}" title="{{sceneTitle}}" subtitle="{{sceneSubTitle}}" thumburl="panos/{{sceneName}}.tiles/thumb.jpg" lat="" lng="" heading="">
    <view hlookat="{{hlookat}}" vlookat="{{vlookat}}" fovtype="MFOV" fov="{{fov}}" maxpixelzoom="1.2" fovmin="90" fovmax="100" limitview="auto" />
    <preview url="panos/{{sceneName}}.tiles/preview.jpg" />
    <image>
        <cube url="panos/{{sceneName}}.tiles/pano_%s.jpg" />
        <mobile>
            <cube url="panos/{{sceneName}}.tiles/mobile_%s.jpg" />
        </mobile>
    </image>
{{hotspot}}
</scene>\n\n
EOF;

		$this->xmlTmp = <<<EOF
<krpano version="1.18" title="Virtual Tour" onstart="startup();">

	<include url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/vtourskin.xml" />
	<include url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/tour-vtskin.xml" />
	<include url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/social-skin.xml" />
         <autorotate enabled="{{tour_rotation}}" waittime="2.0" speed="2.0" horizon="0.0"/>
	<skin_settings bingmaps="false"
	               bingmaps_key=""
	               bingmaps_zoombuttons="false"
	               gyro="true"
	               thumbs_width="120" thumbs_height="80" thumbs_padding="10" thumbs_crop="0|40|240|160"
	               thumbs_opened="false"
	               thumbs_text="false"
	               thumbs_dragging="true"
	               thumbs_onhoverscrolling="false"
	               thumbs_scrollbuttons="false"
	               thumbs_scrollindicator="false"
	               tooltips_thumbs="false"
	               tooltips_hotspots="false"
	               tooltips_mapspots="false"
	               controlbar_offset="20"
	               />

	<!-- set optional skin logo url -->
	<layer name="skin_logo" url="" scale="0.25" opened_onclick="openurl(\'...\',_blank);" />

    <action name="startup">
        showlog(false);
        if(startscene === null, copy(startscene,scene[{{defaultScene}}].name));
        loadscene(get(startscene), null, MERGE);
    </action>
      {{start_social}}
      <layer name="social_share" type="container" keep="true" align="left" width="50" height="200" x="20" y="0" bgcolor="0xffffff" bgalpha="0.0">
         <layer name="face" align="righttop" x="0" y="0" url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/images/f.jpg" zorder="90" onclick="js(shareFacebook(););"/>
         <layer name="twit" align="righttop" x="0" y="50" url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/images/t.jpg" zorder="90" onclick="js(shareTwitter(););"/>
         <layer name="goog" align="righttop" x="0" y="100" url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/images/g.jpg" zorder="90" onclick="js(shareGooglePlus(););"/>
         <layer name="embed" type="image" url="http://data.globalvision.ch/krpano/1.19/skin/images/embed.png" keep="true" align="lefttop" width="50" height="50" crop="0|0|50|50" onovercrop="50|0|50|50" onclick="js(toggleEmbedcode(););" />
      </layer>
      {{end_social}}
{{scenes}}
</krpano>
EOF;

		$this->hotspotTmp     = "\n" . '        <hotspot name="spot{{hotspotID}}" dataId="{{dataId}}" style="skin_hotspotstyle|{{additionalStyle}}" ath="{{hotSpotAth}}" atv="{{hotSpotAtv}}" hotspot_type="{{hotspot_type}}" {{data}} />';
		$this->hotSpotCounter = 0;
	}

	public function XMLHotSpotGet($hotSpotList)
		//give a list of hotspot of $sceneName in xml
	{
		$re = '';
		//$hotSpotList = $this->data[$sceneName]['hotSpotList'];
		foreach ($hotSpotList as $key => $hotSpot)
		{
			//$tmp = $this->hotspotTmp;
			$tmp = preg_replace('/\{\{hotspotID\}\}/i', $this->hotSpotCounter, $this->hotspotTmp);
			$this->hotSpotCounter++;
			$tmp = preg_replace('/\{\{dataId\}\}/i', $key, $tmp);
			$tmp = preg_replace('/\{\{hotSpotAth\}\}/i', $hotSpot['ath'], $tmp);
			$tmp = preg_replace('/\{\{hotSpotAtv\}\}/i', $hotSpot['atv'], $tmp);
			$tmp = preg_replace('/\{\{hotspot_type\}\}/i', $hotSpot['hotspot_type'], $tmp);
			if ($hotSpot['hotspot_type'] == 'normal')
			{
				// $tmp = preg_replace('/\{\{data\}\}/i', $hotSpot['linkedscene'], $tmp);
				$tmp = preg_replace('/\{\{additionalStyle\}\}/i', 'tooltip', $tmp);
				$tmp = preg_replace('/\{\{data\}\}/i', 'linkedscene="scene_' . $hotSpot['linkedscene'] . '"', $tmp);
			}
			elseif ($hotSpot['hotspot_type'] == 'text')
			{
				// $tmp = preg_replace('/\{\{data\}\}/i', $hotSpot['linkedscene'], $tmp);
				$tmp = preg_replace('/\{\{additionalStyle\}\}/i', 'textpopup', $tmp);
				$tmp = preg_replace('/\{\{data\}\}/i', 'hotspot_text="' . $hotSpot['hotspot_text'] . '"', $tmp);
			}
			$re = $re . $tmp;
		}

		//$this->lastXMLHotSpotList = $re; //.
		return $re;
	}

	public function getSceneXml($i)
	{
		$tmp = preg_replace('/\{\{sceneName\}\}/i', $i, $this->sceneTmp);
		$tmp = preg_replace('/\{\{sceneTitle\}\}/i', $this->data->panoList["$i"]['des'], $tmp);
		$tmp = preg_replace('/\{\{sceneSubTitle\}\}/i', $this->data->panoList["$i"]['des_sub'], $tmp);

		if (isset($this->data->panoList["$i"]['defaultView']['hlookat']) && isset($this->data->panoList["$i"]['defaultView']['vlookat']) && isset($this->data->panoList["$i"]['defaultView']['fov']))
		{
			$tmp = preg_replace('/\{\{hlookat\}\}/i', $this->data->panoList["$i"]['defaultView']['hlookat'], $tmp);
			$tmp = preg_replace('/\{\{vlookat\}\}/i', $this->data->panoList["$i"]['defaultView']['vlookat'], $tmp);
			$tmp = preg_replace('/\{\{fov\}\}/i', $this->data->panoList["$i"]['defaultView']['fov'], $tmp);
		}
		else  // default value for view if not givent
		{
			$tmp = preg_replace('/\{\{hlookat\}\}/i', '0', $tmp);
			$tmp = preg_replace('/\{\{vlookat\}\}/i', '0', $tmp);
			$tmp = preg_replace('/\{\{fov\}\}/i', '100', $tmp);
		}

		if (isset($this->data->panoList["$i"]['hotspotList']))
		{
			$tmp = preg_replace('/\{\{hotspot\}\}/i', $this->XMLHotSpotGet($this->data->panoList["$i"]['hotspotList']), $tmp);
		}
		else $tmp = preg_replace('/\{\{hotspot\}\}/i', '', $tmp);

		return $tmp;
	}

	public function getAllSceneXml()
	{
		if (!isset($this->data->allSceneXml))
		{
			$this->data->allSceneXml = "";
		}
		foreach ($this->data->jsonData as $ikey => $i)
		{
			$this->data->allSceneXml .= $this->getSceneXml($i);
		}

		return $this->data->allSceneXml;
	}

	public function getXml()
	{
		$tmp = preg_replace('/\{\{scenes\}\}/i', $this->getAllSceneXml(), $this->xmlTmp);
		$tmp = preg_replace('/\{\{defaultScene\}\}/i', ($this->data->defaultScene - 1), $tmp);
		$tmp = preg_replace('/\{\{tour_rotation\}\}/i', ($this->data->tourRotation), $tmp);
		$tmp = preg_replace('/\{\{start_social\}\}/i', ($this->data->startSocial), $tmp);
		$tmp = preg_replace('/\{\{end_social\}\}/i', ($this->data->endSocial), $tmp);

		return $tmp;
	}
}

class fileObj
//create .sh file
//create xml with scene name and title
{
	public function __construct($dataVerObj, $shFile = true)
	{
		$this->data = $dataVerObj;
		$this->xml  = new xmlData($this->data);
		//cp sh file
		if ($shFile)
		{
			copy($this->data->currentDir . '../../kr-tool.tmp.sh', $this->data->currentDir . '/kr-tool.sh');
		}
	}

	public function xmlWrite()
		//write xml file [include vtour.html]
	{
		$xmlfile = fopen($this->data->currentDir . 't.xml', 'w+');
		fwrite($xmlfile, $this->xml->getXml());
		fclose($xmlfile);

	}

	public function shWriteExec()
	{
		// make changes to .sh file
		$filename = $this->data->currentDir . 'kr-tool.sh';

		echo '<pre>' . $filename . '</pre>';
		var_dump(file_exists($filename));

		$handle   = fopen($filename, "r"); //echo '<textarea> fopen return: ', var_dump($handle),' </textarea>';
		$buffer       = fread($handle, filesize($filename)); //echo '<textarea> fopen return: ', var_dump($fr),' </textarea>';
		fclose($handle);

		$handle = fopen($filename, "w");
		// $frr = preg_replace( '/{{PANAROMA_ARR}}/', $this->data->sh_KR_FILE_ARR, $fr  );
		$newBuffer = preg_replace('/{{TOKEN}}/', "ThisisSecreatTOKEN_Kkjsdk^&#^jhbdjnJHDASjajsdoKSDJkjwdasJKASJ@HSDjasdbncxvloas", $buffer);
		$newBuffer = preg_replace('/{{PANAROMA_ARR_str}}/', $this->data->sh_PANAROMA_ARR_str, $buffer);
		$newBuffer = preg_replace('/{{UID}}/', $this->data->uId, $newBuffer);
		$newBuffer = preg_replace('/{{TOUR_URL}}/', $this->data->tour_url, $newBuffer);
		$newBuffer = preg_replace('/{{VTOUR_NAME}}/', $this->data->tourDes, $newBuffer);
		$newBuffer = preg_replace('/{{EMAIL_DEMO}}/', $this->data->email, $newBuffer);
		$newBuffer = preg_replace('/{{USER_FULL_NAME}}/', $this->data->userFullName, $newBuffer);

		fwrite($handle, $newBuffer);
		fclose($handle);

		var_dump(exec("chmod +x " . $filename));
		// echo('<textarea>'."$filename > $this->panaromaLocalte/log.php 2>&1".'</textarea>');
		$cDir = $this->data->currentDir;
		var_dump(exec("$filename"));//>$cDir/kr.log.html 2>$cDir/kr.log.err.html &");
	}

	public function writeAll()
	{
		$this->xmlWrite();
		$this->shWriteExec();
	}
}

?>
