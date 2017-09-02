<?php/** * Class Vr360Task */class Vr360Task{	/**	 *	 */	public static function login()	{		$authorise = Vr360Authorise::getInstance();		$authorise->signIn();	}	public static function unpublished()	{		$auth = Vr360Authorise::getInstance();		if (!$auth->isAuth())		{			echo '{"error": "notAuth"}';			die();		}		$db     = Vr360Database::getInstance();		$result = $db->change_vtour_status($_GET['UIDx'], VR360_TOUR_STATUS_UNPUBLISHED);		header('Content-Type: application/json');		echo(json_encode(array(			'status' => ($result !== false) ? true : false		)));		exit();	}	/**	 * Create new tour	 */	public static function createTour()	{		$token = Vr360Session::getInstance()->get('token');		$ajax  = new Vr360AjaxResponse();		if (isset($_POST[$token]) && $_POST[$token] == 1)		{			$ajax->setMessage('Invalid request')->fail()->respond();		}		if (!Vr360Authorise::isLogged())		{			$ajax = new Vr360AjaxResponse();			$ajax->setMessage('User is not logged')->fail()->respond();		}		$step = $_POST['step'];		switch ($step)		{			case 'upload':				// @TODO We need to get things we need and validate it before use!				$jsonData          = json_decode(json_encode($_POST), true);				$jsonData['files'] = [];				// Create data directory first				if (!file_exists(VR360_PATH_DATA))				{					mkdir(VR360_PATH_DATA);				}				$uId = Vr360HelperTour::createDataDir();				if ($uId === false)				{					$ajax->setMessage('Can not create data directory');					$ajax->fail()->respond();				}				$numberOfFiles = count($_FILES['file']['name']);				// @TODO Use foreach instead				// @TODO One invalid file will break whole process				for ($i = 0; $i < $numberOfFiles; $i++)				{					// File upload and validate					//!!!!! need to check file size!! if not krpano will hang 4ever!					if (!Vr360HelperTour::fileValidate($_FILES['file']['tmp_name'][$i]))					{						$ajax->setMessage('Invalid file: ' . $_FILES['file']['name'][$i]);						$ajax->fail()->respond();					}					$newFileName = Vr360HelperTour::generateFilename($_FILES['file']['name'][$i]);					if (!move_uploaded_file($_FILES['file']['tmp_name'][$i], '_/' . $uId . '/' . $newFileName))					{						$ajax->setMessage("Cant move upload file: " . $_FILES['file']['name'][$i]);						$ajax->fail()->respond();					}					$jsonData['files'][] = $newFileName;				}				//**************************************************************				//we need to create here! cause multi post is not a good option.				//additionaly, do you want to post files double times?				//if we plan to spearate this, we need a plan to change the js				//**************************************************************				//1 save to json				$jsonFile = VR360_PATH_DATA . "/$uId/data.json";				if (!file_put_contents($jsonFile, json_encode($jsonData)))				{					$ajax->setMessage("Cant create JSON file")->fail()->respond();				}				$tour = new Vr360Tour();				$tour->setProperties(					array(						'created_by' => Vr360Authorise::getInstance()->getUser()->id,						'name'       => $_POST['name'],						'dir'        => $uId,						'alias'      => $_POST['alias'], //alias must be unique						'status'     => VR360_TOUR_STATUS_PENDING					)				);				//2 save to db //Validate input?				// Check return false or similar				$tour->save();				//3 reponse				$ajax->setData('uId', $uId)->setMessage('Upload & create tour record success: ' . $uId);				$ajax->setData('id', $uId);				$ajax->success()->respond();				break;			//It is insecurity if generate only by req uID! TOKEN?			case 'generate':				$uId      = $_POST['uId'];				$jsonFile = VR360_PATH_DATA . "/$uId/data.json";				if (!file_exists($jsonFile) || !is_file($jsonFile))				{					$ajax->setMessage("Cant read JSON file: " . $jsonFile)->fail()->respond();				}				$jsonData = json_decode(file_get_contents($jsonFile), true);				//Using krpano tool to cut images				Vr360HelperTour::tourCreate($uId, $jsonData);				//Create xml for tour				Vr360HelperTour::xmlCreate($uId, $jsonData);				//reponse if success				$ajax->setData('uId', $_POST['uId'])->setMessage('Tour generated success');				$ajax->success()->respond();				break;			case 'data-update-only':				//this use when you only need to rebuild xml only				//like when you edit the hotspots/sences data only				/*						$uId = $_POST['uId'];						$jsonFile = "_/$uId/data.json";						$jsonOldData = json_decode(file_get_contents($jsonFile), true); //check if cant read file OR cant read json format?						//update db; allow update only some field								Vr360Database::getInstance()->insert_vtour([									'name'       => $_POST['name'],									'alias'      => $_POST['alias'], //alias must be uniq								]);						//update json file							//bla bla bla bla						//update xml file							Vr360HelperTour::xmlCreate($uId, $jsonData);						*/				break;			default:				//??????				$ajax->setMessage('Unknow step!')->fail()->respond();				break;		}	}}