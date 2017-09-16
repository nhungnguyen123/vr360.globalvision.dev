<?php

defined('_VR360_EXEC') or die;

class Vr360ControllerTour extends Vr360Controller
{
	/**
	 * Create new tour
	 */
	public function ajaxCreateTour()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning('User is not authorized')->fail()->respond();
		}

		$input = Vr360Factory::getInput();

		$tourName  = $input->getString('name');
		$tourAlias = $input->getString('alias');

		if (empty($tourName) || empty($tourAlias))
		{
			$ajax->addWarning('Missed fields')->fail()->respond();
		}

		switch ($input->getString('step'))
		{
			case 'uploadFile':
				$this->uploadFile();
				break;
			case 'createTour':
				$this->createTour();
				break;
		}
	}

	/**
	 *
	 */
	protected function uploadFile()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		$numberOfFiles = isset($_FILES['panoFile']['name']) ? count($_FILES['panoFile']['name']) : 0;

		$jsonData = json_decode(json_encode($_POST), true);

		$input  = Vr360Factory::getInput();
		$tourId = $input->getInt('id');

		if ($tourId)
		{
			$tour = new Vr360TableTour();
			$tour->load(array(
				'id'         => (int) $tourId,
				'created_by' => Vr360Factory::getUser()->id
			));

			$uId = $tour->dir;
		}

		if ($numberOfFiles > 0)
		{
			// @TODO We need to get things we need and validate it before use!
			$validFiles = array();

			// Files validate
			for ($i = 0; $i < $numberOfFiles; $i++)
			{
				// File upload and validate
				//!!!!! need to check file size!! if not krpano will hang 4ever!
				if (!Vr360HelperTour::fileValidate($_FILES['panoFile']['tmp_name'][$i]))
				{
					$ajax->addWarning('Invalid file: ' . $_FILES['panoFile']['name'][$i])->fail()->respond();
				}

				$newFileName    = Vr360HelperTour::generateFilename($_FILES['panoFile']['name'][$i]);
				$validFiles[$i] = $newFileName;
			}

			if (!$tourId)
			{
				// All files are validated than loop again to make json data
				$uId = Vr360HelperTour::createDataDir();
			}

			if ($uId === false)
			{
				$ajax->addWarning('Can not create data directory')->fail()->respond();
			}

			$destDir = VR360_PATH_DATA . '/' . $uId;

			$jsonData ['uId']     = $uId;
			$jsonData ['destDir'] = $destDir;

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
				$jsonData = json_decode(file_get_contents($jsonFile), true);

				// Merge with new uploaded files
				$jsonData['files'] = array_merge($_POST['panoFile'], $jsonFiles);

				unset($jsonData['panoFile']);
			}
			else
			{
				$jsonData['files'] = $jsonFiles;
			}

			$jsonData['name']            = $_POST['name'];
			$jsonData['alias']           = $_POST['alias'];
			$jsonData['panoTitle']       = $_POST['panoTitle'];
			$jsonData['panoDescription'] = $_POST['panoDescription'];

			if (!file_put_contents($jsonFile, json_encode($jsonData)))
			{
				$ajax->addWarning('Can not create JSON file')->fail()->respond();
			}

			$ajax->addData('tour', $jsonData);
			$ajax->addData('uId', $uId);
			$ajax->addData('dir', $destDir)->addSuccess('File uploaded success')->success()->respond();
		}

		// No new upload file
		if ($tourId)
		{
			if ($tour->id)
			{
				$uId      = $tour->dir;
				$destDir  = VR360_PATH_DATA . '/' . $uId;
				$jsonFile = $destDir . "/data.json";

				if (Vr360HelperFile::exists($jsonFile))
				{
					$jsonData = json_decode(file_get_contents($jsonFile), true);

					$jsonData['name']  = $_POST['name'];
					$jsonData['alias'] = $_POST['alias'];

					if ($input->get('panoTitle'))
					{
						$jsonData['panoTitle'] = $input->get('panoTitle');
					}
					else
					{
						unset($jsonData['panoTitle']);
					}

					if ($input->get('panoDescription'))
					{
						$jsonData['panoDescription'] = $input->get('panoDescription');
					}
					else
					{
						unset($jsonData['panoDescription']);
					}

					if (!file_put_contents($jsonFile, json_encode($jsonData)))
					{
						$ajax->addWarning('Can not create JSON file')->fail()->respond();
					}

					$ajax->addData('tour', $jsonData);
					$ajax->addData('id', $tourId);
					$ajax->addData('uId', $uId);
					$ajax->addData('dir', $destDir)->addSuccess('File uploaded success')->success()->respond();
				}
				else
				{
					$ajax->addWarning('File not found')->fail()->respond();
				}
			}
		}

		$ajax->addWarning('No pano')->fail()->respond();
	}

	/**
	 * Create tour record
	 */
	protected function createTour()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		$table = new Vr360TableTour();
		$table->bind($_POST);

		$input = Vr360Factory::getInput();

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
		$table->dir    = $_POST['uId'];
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

	/**
	 * Generate tour
	 *
	 * @return bool
	 */
	public function ajaxGenerateTour()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning('User is not authorized')->fail()->respond();
		}

		$tour = new Vr360TableTour();
		$tour->load(array(
			'id'         => (int) $_POST['id'],
			'created_by' => Vr360Factory::getUser()->id
		));

		// Found tour
		if ($tour->id !== null)
		{
			$uId      = $tour->dir;
			$jsonFile = VR360_PATH_DATA . '/' . $tour->dir . '/data.json';

			if (!file_exists($jsonFile) || !is_file($jsonFile))
			{
				$ajax->addWarning('File not found')->fail()->respond();
			}

			$jsonContent = file_get_contents($jsonFile);
			$jsonData    = json_decode($jsonContent, true);

			if (!isset($jsonData['panoTitle']))
			{
				$ajax->addWarning('No panos')->fail()->respond();
			}

			//Using krpano tool to cut images
			if (Vr360HelperTour::generateTour($uId, $jsonData) === false)
			{
				$ajax->addWarning('Can not generate vTour')->fail()->respond();
			}

			//Create xml for tour
			if (Vr360HelperTour::generateXml($uId, $jsonData) === false)
			{
				$ajax->addWarning('Can not generate xml for vTour')->fail()->respond();
			}

			// Have done
			$tour->status = VR360_TOUR_STATUS_PUBLISHED_READY;
			$tour->save();

			// Send mail
			$mailer = new Vr360Email();
			$mailer->isHTML(true);
			$mailer->Subject = 'Your tour was created and generated success';
			$mailer->Body    = '';
			$mailer->send();

			$ajax->addSuccess('Tour generated success')->success()->respond();
		}

	}

	public function ajaxRemoveTour()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		$input = Vr360Factory::getInput();
		$tour  = new Vr360TableTour();
		$tour->load(array(
			'id'         => (int) $input->getInt('id'),
			'created_by' => Vr360Factory::getUser()->id
		));

		$tour->status = VR360_TOUR_STATUS_UNPUBLISHED;

		if ($tour->save() !== false)
		{
			$ajax->addData('id', $tour->id);
			$ajax->addSuccess('Tour unpublished')->success()->respond();
		}

		$ajax->addWarning('Something wrong')->fail()->respond();
	}

	public function ajaxEditTour()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning('User is not authorized')->fail()->respond();
		}

		$input = Vr360Factory::getInput();

		$tourName  = $input->getString('name');
		$tourAlias = $input->getString('alias');

		if (empty($tourName) || empty($tourAlias))
		{
			$ajax->addWarning('Missed fields')->fail()->respond();
		}

		switch ($input->getString('step'))
		{
			case 'uploadFile':
				// Handle new file uploaded
				$this->uploadFile();

				break;
			case 'createTour':
				$this->createTour();
				break;
		}
	}

	public function ajaxGetTourHtml()
	{
		$input = Vr360Factory::getInput();
		$tour  = new Vr360TableTour();
		$tour->load(array(
			'id'         => (int) $input->getInt('id'),
			'created_by' => Vr360Factory::getUser()->id
		));

		if ($tour !== false)
		{
			$html = Vr360Layout::getInstance()->fetch('form.tour', array('tour' => $tour));
		}

		else
		{
			$html = Vr360Layout::getInstance()->fetch('form.tour');
		}


		Vr360AjaxResponse::getInstance()->addData('html', $html)->success()->respond();
	}


}
