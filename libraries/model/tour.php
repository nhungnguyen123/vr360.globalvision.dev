<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelTour
 */
class Vr360ModelTour extends Vr360Model
{
	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static();

		return $instance;
	}

	public function getItem()
	{
		$alias = Vr360Factory::getInput()->getRaw('alias');
		$id    = Vr360Factory::getInput()->getInt('id');

		$table = new Vr360TableTour;

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
		}

		if ($numberOfFiles > 0)
		{
			$ajax->addInfo($numberOfFiles . ' uploaded');

			// @TODO We need to get things we need and validate it before use!
			$validFiles = array();

			// Files validate
			for ($i = 0; $i < $numberOfFiles; $i++)
			{
				// File upload and validate
				// Need to check file size!! if not krpano will hang 4ever!
				$message = Vr360HelperTour::fileValidate($_FILES['panoFile']['tmp_name'][$i]);

				if ($message !== true)
				{
					$ajax->addWarning($message)->fail()->respond();
					$ajax->addWarning('Invalid file: ' . $_FILES['panoFile']['name'][$i])->fail()->respond();
				}

				$newFileName    = Vr360HelperTour::generateFilename($_FILES['panoFile']['name'][$i]);
				$validFiles[$i] = $newFileName;
			}

			// New tour
			if (!$tourId)
			{
				// All files are validated than loop again to make json data
				$uId = Vr360HelperTour::createDataDir();

				$ajax->addInfo('Created data directory');
			}

			if ($uId === false)
			{
				$ajax->addWarning('Can not create data directory')->fail()->respond();
			}

			$destDir = VR360_PATH_DATA . '/' . $uId;

			$jsonData ['uId'] = $uId;

			// Prepare list of files
			$jsonFiles = array();

			// Move uploaded files
			foreach ($validFiles as $index => $fileName)
			{
				// Move uploaded file to right data directory
				if (!move_uploaded_file($_FILES['panoFile']['tmp_name'][$index], $destDir . '/' . $fileName))
				{
					$ajax->addWarning("Cant move upload file: " . $_FILES['panoFile']['name'][$index]);
					$ajax->fail()->respond();
				}

				// Save generated filename
				$jsonFiles[] = $fileName;
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

			if ($input->getString('panoTitle'))
			{
				$jsonData['panoTitle'] = $input->getString('panoTitle');
			}

			if ($input->getString('panoDescription'))
			{
				$jsonData['panoDescription'] = $input->getString('panoDescription');
			}

			// Save json
			if (!file_put_contents($jsonFile, json_encode($jsonData)))
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
				$jsonFile = $tour->getDataFilePath();

				if (Vr360HelperFile::exists($jsonFile))
				{
					$ajax->addInfo('JSON File already exists');

					$jsonData = $tour->getData();

					$jsonData['name']  = $input->getString('name');
					$jsonData['alias'] = $input->getString('alias');

					if ($input->getString('panoTitle'))
					{
						$jsonData['panoTitle'] = $input->getString('panoTitle');
					}
					elseif (isset($jsonData['panoTitle']))
					{
						unset($jsonData['panoTitle']);
					}

					if ($input->getString('panoDescription'))
					{
						$jsonData['panoDescription'] = $input->getString('panoDescription');
					}
					elseif (isset($jsonData['panoDescription']))
					{
						unset($jsonData['panoDescription']);
					}

					$jsonData['params'] = isset($_REQUEST['params']) ? $_REQUEST['params'] : array();

					// Save json
					if (!file_put_contents($jsonFile, json_encode($jsonData)))
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
	 *
	 */
	public function ajaxCreateTour()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		$table = new Vr360TableTour;
		$table->bind($_REQUEST);

		$params = $table->params;

		if ($input->get('panoTitle') && $input->get('panoDescription'))
		{
			$params['panos'] = array(
				'title'       => $_POST['panoTitle'],
				'description' => $_POST['panoDescription'],
				'files'       => $_POST['files']
			);
		}
		else
		{
			$params['panos'] = null;
		}

		$table->params = $params;
		$table->dir    = $input->get('uId');
		$table->status = VR360_TOUR_STATUS_PENDING;

		if ($id = $table->save())
		{
			$ajax->addData('id', $id);
		}

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
