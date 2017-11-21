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

	public function ajaxSave()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		// Do file validate first
		try
		{
			$files = $input->files->get('sceneFile');

			if (empty($files))
			{
				$ajax->addDanger('No scenes')->fail()->respond();
			}

			// File validate
			foreach ($files as $file)
			{
				// Respond ajax if anything faile
				if (!Vr360HelperTour::fileValidate($file['tmp_name']))
				{
					$ajax->addDanger('Invalid scene: ' . $file['name'])->fail()->respond();
				}
			}
		}
		catch (Exception $exception)
		{

		}

		// Okay at least all scenes files are validated. Now try to create tour

		/**
		 * @var $tour Vr360TableTour
		 */
		$tour = $this->getItem();
		$tour->bind($_REQUEST);
		$params       = new Vr360Object(isset($_REQUEST['params']) ? $_REQUEST['params'] : array());
		$tour->params = $params;

		if ($tour->save() === false)
		{
			$ajax->addDanger($tour->getError())->fail()->respond();
		}

		// Tour saved success
		if ($tour->id)
		{
			try
			{
				$files       = $input->files->get('sceneFile');
				$tourDataDir = VR360_PATH_DATA . '/' . $tour->id;

				if (!Vr360HelperFolder::exists($tourDataDir))
				{
					Vr360HelperFolder::create($tourDataDir);
				}

				// Okay now we can process
				$uploadedFiles = array();

				foreach ($files as $index => $file)
				{
					$fileName = Vr360HelperTour::generateFilename($file['name']);

					if (!move_uploaded_file($file['tmp_name'], $tourDataDir . '/' . $fileName))
					{

					}

					$uploadedFiles[] = $tourDataDir . '/' . $fileName;
					$scene           = new Vr360Scene();
					$scene->set('tourId', $tour->id);
					$scene->set('name', $input->get('sceneName')[$index]);
					$scene->set('description', $input->get('sceneDescription')[$index]);
					$scene->set('file', $fileName);
					$scene->set('ordering', $index);
					$scene->save();
				}

				// And now generate tour
				$command = '';
				$krPano  = new Vr360Krpano(Vr360Configuration::getConfig('krPanoPath'), Vr360Configuration::getConfig('krPanoLicense'));
				$krPano->useConfigFile(Vr360Configuration::getConfig('krPanoConfigFile'));
				$krPano->addFiles($uploadedFiles);

				$krPano->makePano($command);
			}
			catch (Exception $exception)
			{

			}
		}


		// Save scene
		$ajax->addInfo('Tour is created')->respond();
	}

	public function getItem()
	{
		$alias = Vr360Factory::getInput()->getRaw('alias');
		$id    = Vr360Factory::getInput()->getInt('id');

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

		if ($alias)
		{

			$table->load(
				array(
					'alias' => $alias
				)
			);
		}

		return $table;
	}

	/**
	 * File upload process only
	 */
	public function ajaxUploadFile()
	{


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
