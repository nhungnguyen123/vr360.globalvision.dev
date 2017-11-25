<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelTour
 *
 * @since  2.0.0
 */
class Vr360ModelTour extends Vr360Model
{
	/**
	 * @return static
	 */
	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static;

		return $instance;
	}

	/**
	 *
	 */
	public function ajaxSave()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		/**
		 * @var $tour Vr360TableTour
		 */
		$tour = $this->getItem();
		$tour->bind($_REQUEST);
		$params       = new Vr360Object(isset($_REQUEST['params']) ? $_REQUEST['params'] : array());
		$tour->params = $params;

		$files = Vr360Factory::getInput()->files->get('newSceneFile');

		// Check if tour is create new. Scene Files must have.
		if (!$tour->id && empty($files))
		{
			$ajax->addDanger("Missing scenes files.")->fail()->respond();

			return false;
		}

		// Need to be validate files.
		if (!empty($files))
		{
			// File validate
			foreach ($files as $i => $file)
			{
				// Respond ajax if anything failed
				if (!Vr360HelperTour::fileValidate($file['tmp_name']))
				{
					unset($files[$i]);
					$ajax->addDanger('Invalid scene: ' . $file['name'])->fail()->respond();
				}
			}
		}

		if ($tour->save() === false)
		{
			$ajax->addDanger($tour->getError())->fail()->respond();
		}

		// Make sure tour data folder exist.
		$tourDataDir = VR360_PATH_DATA . '/' . $tour->id;

		if (!Vr360HelperFolder::exists($tourDataDir))
		{
			Vr360HelperFolder::create($tourDataDir);
		}

		// Update / delete current scenes
		$currentScenes = $this->saveScenes($tour);

		// Try to save new scenes files
		$newScenes = $this->saveNewScenes($tour, $files);

		$vTourFolder = Vr360HelperFile::clean($tourDataDir . '/vtour');

		// If there are new scenes or missing vtour folder. Regenerate with krpano program.
		if ((is_array($newScenes) && !empty($newScenes)) || !Vr360HelperFolder::exists($vTourFolder))
		{
			$newScenes = is_array($newScenes) && !empty($newScenes) ? array_merge($newScenes, $currentScenes) : $currentScenes;

			try
			{
				// Remove old folder vtour
				if (Vr360HelperFolder::exists($vTourFolder))
				{
					Vr360HelperFolder::delete($vTourFolder);
				}

				// And now generate tour
				$command = '';
				$krPano  = new Vr360Krpano(Vr360Configuration::getConfig('krPanoPath'), Vr360Configuration::getConfig('krPanoLicense'));
				$krPano->useConfigFile(Vr360Configuration::getConfig('krPanoConfigFile'));
				$krPano->addFiles($newScenes);
				$krPano->makePano($command);
			}
			catch (Exception $exception)
			{
				$ajax->addInfo($exception->getMessage());
			}
		}

		$this->modifyXML($tour, $ajax);

		// Save scene
		$ajax->addInfo('Tour is created')->success()->respond();
	}

	public function getItem()
	{
		$id = Vr360Factory::getInput()->getInt('id');

		$table = new Vr360Tour;

		if ($id)
		{
			$table->load(
				array
				(
					'id'         => (int) Vr360Factory::getInput()->getInt('id'),
					'created_by' => Vr360Factory::getUser()->id
				)
			);
		}

		return $table;
	}

	/**
	 * Method for store new scenes
	 *
	 * @param   Vr360Tour  $tour   Tour data
	 * @param   array      $files  List of new scenes files.
	 *
	 * @return  array|false        Array of new scenes files if success. False otherwise.
	 *
	 * @since   3.0.0
	 */
	protected function saveNewScenes($tour, $files)
	{
		$input       = Vr360Factory::getInput();
		$ajax        = Vr360AjaxResponse::getInstance();
		$tourDataDir = VR360_PATH_DATA . '/' . $tour->id;

		// Just check if user upload new files.
		if (empty($files))
		{
			return false;
		}

		$uploadedFiles = array();

		try
		{
			// Okay now we can process
			$sceneNames        = $input->get('newSceneName', array(), 'Array');
			$sceneDescriptions = $input->get('newSceneDescription', array(), 'Array');

			foreach ($files as $index => $file)
			{
				$fileName = Vr360HelperTour::generateFilename($file['name']);

				if (!move_uploaded_file($file['tmp_name'], $tourDataDir . '/' . $fileName))
				{
					continue;
				}

				$uploadedFiles[] = $tourDataDir . '/' . $fileName;

				$scene = new Vr360Scene;

				$scene->set('tourId', $tour->id);
				$scene->set('name', $sceneNames[$index]);
				$scene->set('description', $sceneDescriptions[$index]);
				$scene->set('file', $fileName);

				if (!$scene->save())
				{
					$ajax->addDanger('Can not save scene: ' . $sceneNames[$index]);
				}
				else
				{
					$ajax->addSuccess('Save scene: ' . $sceneNames[$index] . ' successed');
				}
			}
		}
		catch (Exception $exception)
		{
			$ajax->addDanger($exception->getMessage())->fail()->respond();
		}

		return empty($uploadedFiles) ? false : $uploadedFiles;
	}

	/**
	 * Method for store current scenes
	 *
	 * @param   Vr360Tour  $tour  Tour data
	 *
	 * @return  array             List of scene files.
	 *
	 * @since   3.0.0
	 */
	protected function saveScenes($tour)
	{
		$input = Vr360Factory::getInput();
		$ajax  = Vr360AjaxResponse::getInstance();

		$sceneNames        = $input->get('sceneName', array(), 'Array');
		$sceneDescriptions = $input->get('sceneDescription', array(), 'Array');
		$sceneDefault      = $input->getInt('sceneDefault');
		$sceneIds          = $input->get('sceneId', array(), 'Array');

		$files = array();

		// Clean up current default
		Vr360Database::getInstance()->update(
			'v2_scenes',
			array('default' => 0),
			array('tourId' => $tour->id)
		);

		// Get current scenes of tour
		$currentScenes = $tour->getScenes();

		if (empty($currentScenes))
		{
			return $files;
		}

		foreach ($currentScenes as $currentScene)
		{
			/** @var Vr360Scene $currentScene */

			// This means user delete this scene.
			if (!in_array($currentScene->id, $sceneIds))
			{
				if (!$currentScene->delete())
				{
					$ajax->addWarning('Can not delete Scene: ' . $currentScene->name . ' [' . $currentScene->id . ']');
				}
				else
				{
					$ajax->addWarning('Delete Scene: ' . $currentScene->name . ' [' . $currentScene->id . '] success');
				}

				continue;
			}

			$currentScene->name        = $sceneNames[$currentScene->id];
			$currentScene->description = $sceneDescriptions[$currentScene->id];

			if ($currentScene->id == $sceneDefault)
			{
				$currentScene->default = 1;
			}

			if (!$currentScene->save())
			{
				$ajax->addWarning('Can not update Scene: ' . $currentScene->name . ' [' . $currentScene->id . ']');
			}
			else
			{
				$ajax->addSuccess('Update Scene: ' . $currentScene->name . ' [' . $currentScene->id . '] successful');
			}

			$files[] = VR360_PATH_DATA . '/' . $tour->id . '/' . $currentScene->file;
		}

		return $files;
	}

	/**
	 * Method for modify XML file with new data.
	 *
	 * @param   Vr360Tour          $tour    Tour data
	 * @param   Vr360AjaxResponse  $ajax    Ajax response.
	 *
	 * @return  array                     List of scene files.
	 *
	 * @since   3.0.0
	 */
	public function modifyXML($tour, $ajax = null)
	{
		$ajax = null === $ajax ? Vr360AjaxResponse::getInstance() : $ajax;

		$tourDataDirPath = VR360_PATH_DATA . '/' . $tour->id . '/vtour';

		if (!Vr360HelperFolder::exists($tourDataDirPath))
		{
			$ajax->addDanger('Missing folder Tour: ' . $tourDataDirPath)->fail()->respond();
		}

		$sceneFiles  = array();
		$panoFolders = array();
		$scenes      = $tour->getScenes();

		// Clean up folder
		if (!empty($scenes))
		{
			foreach ($scenes as $scene)
			{
				$sceneFiles[]  = $scene->file;
				$panoFolders[] = explode('.', $scene->file)[0] . '.tiles';
			}
		}

		$files = scandir(Vr360HelperFile::clean(VR360_PATH_DATA . '/' . $tour->id));

		foreach ($files as $file)
		{
			if (!in_array($file, $sceneFiles))
			{
				Vr360HelperFile::delete(VR360_PATH_DATA . '/' . $tour->id . '/' . $file);
			}
		}

		$folders = scandir(Vr360HelperFile::clean($tourDataDirPath . '/panos'));

		foreach ($folders as $folder)
		{
			if ($folder == '.' || $folder == '..')
			{
				continue;
			}

			if (!in_array($folder, $panoFolders))
			{
				Vr360HelperFolder::delete($tourDataDirPath . '/panos/' . $folder);
			}
		}

		$xmlData = Vr360Layout::getInstance()->fetch('tour.tour', array('tour' => $tour, 'scenes' => $scenes));
		$xmlData = simplexml_load_string($xmlData);

		return $xmlData->asXML($tourDataDirPath . '/tour.xml');
	}

	/**
	 * File upload process only
	 */
	public function ajaxUploadFile()
	{
		$ajax          = Vr360AjaxResponse::getInstance();
		$numberOfFiles = isset($_FILES['panoFile']['name']) ? count($_FILES['panoFile']['name']) : 0;

		// This json included all form post
		$jsonData = json_decode(json_encode($_POST), true);

		$input  = Vr360Factory::getInput();
		$tourId = $input->getInt('id');

		// Edit old tour
		if ($tourId)
		{
			$tour        = $this->getItem();
			$tourDataDir = $tour->dir;

			// This is old tour than we will respond with ID
			$ajax->addData('id', $tourId);
		}

		// Have file uploaded
		if ($numberOfFiles > 0)
		{
			$ajax->addInfo($numberOfFiles . ' uploaded');

			// Create data directory

			// New tour
			if (!$tourId)
			{
				// All files are validated than loop again to make json data
				$tourDataDir = Vr360HelperTour::createDataDir();

				$ajax->addInfo('Created data directory: ' . $tourDataDir);
			}
			else
			{
				$tourDataDir = VR360_PATH_DATA . '/' . $tourId;
			}

			if ($tourDataDir === false)
			{
				$ajax->addWarning('Can not create data directory')->fail()->respond();
			}

			$jsonData ['uId'] = $tourDataDir;
			$destDir          = VR360_PATH_DATA . '/' . $tourDataDir;

			// Prepare list of uploaded files
			$jsonFiles = array();

			// @TODO We need to get things we need and validate it before use!
			$validFiles = array();

			// Files validate
			for ($i = 0; $i < $numberOfFiles; $i++)
			{
				$uploadedFile     = $_FILES['panoFile']['tmp_name'][$i];
				$uploadedFileName = $_FILES['panoFile']['name'][$i];

				// File upload and validate
				// Need to check file size!! if not krpano will hang 4ever!
				$message = Vr360HelperTour::fileValidate($uploadedFile);

				if ($message === false || !is_array($message))
				{
					$ajax->addWarning($message)->fail()->respond();

					// @TODO Add error message but no need respond yet
					$ajax->addWarning('Invalid file: ' . $uploadedFileName)->fail()->respond();
				}
				else
				{
					// Generate filename
					$validFiles[$i] = Vr360HelperTour::generateFilename($uploadedFileName);

					// Move uploaded file to right data directory
					if (!move_uploaded_file($uploadedFile, $destDir . '/' . $validFiles[$i]))
					{
						$ajax->addWarning('Cant move upload file: ' . $uploadedFileName);

						// @TODO Add error message but no need respond yet
						$ajax->fail()->respond();
					}

					$ajax->addInfo('Valid file: ' . $message[0] . 'x' . $message[1] . ' ' . $message['mime']);

					// Save generated filename
					$jsonFiles[] = $validFiles[$i];
				}
			}

			// Create json file
			$jsonFile = $destDir . "/data.json";

			if (Vr360HelperFile::exists($jsonFile))
			{
				$ajax->addInfo('JSON File already exists');

				$jsonData = json_decode(Vr360HelperFile::read($jsonFile), true);

				// Merge with new uploaded files
				$jsonData['files'] = array_merge($_POST['panoFile'], $jsonFiles);

				unset($jsonData['panoFile']);

				// Update
				$jsonData['name']  = $input->getString('name');
				$jsonData['alias'] = $input->getString('alias');
			}
			else
			{
				$jsonData['files'] = $jsonFiles;
			}

			$jsonData['panos'] = array();

			/**
			 * @TODO Until now we are assumed files matched with index of title / sub-title.
			 * Need improve this one later
			 */
			foreach ($jsonData['files'] as $index => $file)
			{
				$pano                = array();
				$pano['file']        = $file;
				$pano['title']       = $_REQUEST['panoTitle'][$index];
				$pano['description'] = $_REQUEST['panoDescription'][$index];
				$jsonData['panos'][] = $pano;
			}

			if ($input->getString('panoTitle'))
			{
				$jsonData['panoTitle'] = $input->getString('panoTitle');
			}

			if ($input->getString('panoDescription'))
			{
				$jsonData['panoDescription'] = $input->getString('panoDescription');
			}

			// Update params
			$jsonData['params'] = isset($_REQUEST['params']) ? $_REQUEST['params'] : array();

			// Save json
			if ($this->saveJson($jsonData, $jsonFile) === false)
			{
				$ajax->addWarning('Can not create JSON file')->fail()->respond();
			}

			$ajax->addInfo('JSON File saved success');
			$ajax->addData('tour', $jsonData);
			$ajax->addSuccess('File uploaded success')->success()->respond();
		}

		// No new upload file
		if ($tourId)
		{
			// Update old tour
			if ($tour->id)
			{
				$jsonFile = $tour->getFile('data.json');

				if ($jsonFile !== false)
				{
					$ajax->addInfo('JSON File already exists');

					$jsonData = $tour->getJsonData();

					// Update name & alias
					/**
					 * @TODO Actually getString will return string not ARRAY.
					 * Need double check this case
					 */
					$jsonData['name']  = $input->getString('name');
					$jsonData['alias'] = $input->getString('alias');
					$jsonData['files'] = $_REQUEST['panoFile'];

					if ($input->getString('panoTitle'))
					{
						$jsonData['panoTitle'] = $input->getString('panoTitle');
					}

					if ($input->getString('panoDescription'))
					{
						$jsonData['panoDescription'] = $input->getString('panoDescription');
					}

					$jsonData['panos'] = array();
					/**
					 * @TODO Until now we are assumed files matched with index of title / sub-title.
					 * Need improve this one later
					 */
					foreach ($jsonData['files'] as $index => $file)
					{
						$pano                = array();
						$pano['file']        = $file;
						$pano['title']       = $_REQUEST['panoTitle'][$index];
						$pano['description'] = $_REQUEST['panoDescription'][$index];
						$jsonData['panos'][] = $pano;
					}

					$jsonData['params'] = isset($_REQUEST['params']) ? $_REQUEST['params'] : array();

					// Save json
					if ($this->saveJson($jsonData, $jsonFile) === false)
					{
						$ajax->addWarning('Can not create JSON file')->fail()->respond();
					}

					$ajax->addInfo('JSON File saved success');
					$ajax->addData('tour', $jsonData);
					$ajax->addData('id', $tourId);
					$ajax->addSuccess('Panos updated success')->success()->respond();
				}
				else
				{
					$ajax->addWarning('File not found')->fail()->respond();
				}
			}
		}

		$ajax->addWarning('No pano. Please add at least 1 pano to create a vTour')->fail()->respond();
	}

	/**
	 * @param $jsonData
	 * @param $jsonFile
	 *
	 * @return bool|int
	 */
	protected function saveJson($jsonData, $jsonFile)
	{
		// Save json
		return file_put_contents($jsonFile, json_encode($jsonData));
	}

	/**
	 * @return  void
	 */
	public function ajaxCreateTour()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		$table = new Vr360Tour;
		$table->bind($_REQUEST);

		$params          = $table->params;
		$params['panos'] = $_REQUEST['panos'];

		$table->params = $params;
		$table->dir    = $input->get('uId');
		$table->status = VR360_TOUR_STATUS_PUBLISHED_READY;

		if ($id = $table->save())
		{
			$ajax->addData('id', $id);
		}

		// @TODO Clean up files

		if ($id)
		{
			if (isset($_POST['id']))
			{
				$ajax->addSuccess('Tour updated success')->success()->respond();
			}
			else
			{
				$ajax->addSuccess('Tour created success')->success()->respond();
			}
		}

		$ajax->addWarning('Tour created fail')->fail()->respond();
	}
}
