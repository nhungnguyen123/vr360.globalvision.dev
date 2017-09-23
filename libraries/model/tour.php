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
	 * File upload process only
	 */
	public function ajaxUploadFile()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		$numberOfFiles = isset($_FILES['panoFile']['name']) ? count($_FILES['panoFile']['name']) : 0;

		// This json included all form post
		$jsonData = json_decode(json_encode($_POST), true);

		$input  = Vr360Factory::getInput();
		$tourId = $input->getInt('id');

		// Edit old tour
		if ($tourId)
		{
			$tour = $this->getItem();
			$uId  = $tour->dir;

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
				$uId = Vr360HelperTour::createDataDir();

				$ajax->addInfo('Created data directory: ' . $uId);
			}

			if ($uId === false)
			{
				$ajax->addWarning('Can not create data directory')->fail()->respond();
			}

			$jsonData ['uId'] = $uId;
			$destDir          = VR360_PATH_DATA . '/' . $uId;

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

				if ($message !== true)
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
				));
		}

		if ($alias)
		{

			$table->load(array(
				'alias' => $alias
			));
		}


		return $table;
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
		$table->status = VR360_TOUR_STATUS_PENDING;

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
