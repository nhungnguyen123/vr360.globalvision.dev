<?php

class editorSave
{
	public function __construct()
	{
		$editID       = date('U');
		$this->editID = $editID;
		$jsonDataRaw  = str_replace('\\"', '"', $_POST ['jsonData']);

		//var_dump($jsonDataRaw);
		$jsonDataArrRaw = json_decode($jsonDataRaw, true);
		$jsonDataArr    = $jsonDataArrRaw ['jsonData'];

		if (!isset ($jsonDataArrRaw ['jsonData']['defaultScene']))
			$this->defaultScene = 1;
		else
			$this->defaultScene = $jsonDataArrRaw ['jsonData']['defaultScene'];

		//var_dump($this->jsonDataArr);

		// rebuild data as dataVerObj type
		$this->jsonData = array();
		$this->panoList = array();
		foreach ($jsonDataArr ['panoList'] as $key => $value)
		{
			$this->jsonData []     = $key;
			$this->panoList [$key] = $value;
		}
		$this->tourDes      = $jsonDataArr ['tourDes'];
		$this->tourRotation = $jsonDataArr ['tourRotation'];
		$this->startSocial  = $jsonDataArr ['startSocial'];
		$this->endSocial    = $jsonDataArr ['endSocial'];

		$this->uId        = $jsonDataArr ['uId'];
		$this->email      = $jsonDataArr ['email'];
		$this->currentDir = "./_/" . $this->uId . "/";

		//////////////////////////////////// is new panao /////////////////////////////////////
		if (isset ($_POST ['newPano']) && $_POST ['newPano'] == 'true')
		{
			$tmpDir   = $this->currentDir . $editID . '_krEditorTmp/';
			$ext      = pathinfo($_FILES ['imgNewPano_file'] ['name'], PATHINFO_EXTENSION);
			$newFName = $_POST ['newFName'];

			$this->jsonData [] = $newFName;

			$this->panoList [$newFName] = array(
				'currentFileNameExt' => $ext,
				'des'                => $_POST ['imgNewPano_des'],
				'des_sub'            => $_POST ['imgNewPano_des_sub']
			);

			mkdir($tmpDir);
			move_uploaded_file($_FILES ['imgNewPano_file'] ['tmp_name'], $tmpDir . $newFName . '.' . $ext);

			$PANAROMA_ARR_str = $tmpDir . $newFName . '.' . $ext;
			$KR_CONF_NOMA     = __DIR__ . "/assets/krpano/krpanotools-1.16.8-win64-working-only/templates/vtour-normal.config";
			// begin krpano processing

			$CMD = __DIR__ . "/assets/krpano/krpanotools-1.16.8-win64-working-only/kmakemultires -config=$KR_CONF_NOMA $PANAROMA_ARR_str";
			exec($CMD);
			echo shell_exec('chmod -Rf a+rw ' . $tmpDir);
			// echo shell_exec('cd '.$tmpDir.'vtour/panos');
			// echo shell_exec("mkdir ./../../../vtour/panos/$newFName".".tiles/");
			echo shell_exec("cp -Rf $tmpDir/vtour/panos/$newFName.tiles $this->currentDir/vtour/panos");
			// echo shell_exec("cd ./../../");
			echo shell_exec("cp $tmpDir/$newFName.$ext $this->currentDir/$newFName.$ext");
		}

		// /////////////////////////////////////////////////////////////////////////

		rename($this->currentDir . 'data.json', $this->currentDir . $editID . '.data.json');
		$dataFile = fopen($this->currentDir . 'data.json', 'w+');
		fwrite($dataFile, json_encode($this));
		fclose($dataFile);
	}
}

?>
