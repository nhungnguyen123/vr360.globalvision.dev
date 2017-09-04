<?php

defined('_VR360') or die;

/**
 * Class Vr360ModelTour
 */
class Vr360ModelTour extends Vr360Database
{
	/**
	 * @return static
	 */
	public static function getInstance()
	{
		static $instance;

		if (!isset($instance))
		{
			$instance = new static();
		}

		return $instance;
	}


	/**
	 * Create new tour
	 */
	public function ajaxCreateTour()
	{
		$ajax = new Vr360AjaxResponse();

		// Fields validate
		if (
			empty($_POST['name'])
			|| empty($_POST['alias'])
			|| empty($_FILES)
		)
		{
			$ajax->setMessage('Missed fields')->fail()->respond();
		}

		$numberOfFiles = count($_FILES['file']['name']);

		if ($numberOfFiles > 0)
		{
			// @TODO We need to get things we need and validate it before use!
			$jsonData          = json_decode(json_encode($_POST), true);
			$jsonData['files'] = [];

			$validFiles = array();

			// Files validate
			for ($i = 0; $i < $numberOfFiles; $i++)
			{
				// File upload and validate
				//!!!!! need to check file size!! if not krpano will hang 4ever!
				if (!Vr360HelperTour::fileValidate($_FILES['file']['tmp_name'][$i]))
				{
					$ajax->setMessage('Invalid file: ' . $_FILES['file']['name'][$i]);
					$ajax->fail()->respond();
				}

				$newFileName    = Vr360HelperTour::generateFilename($_FILES['file']['name'][$i]);
				$validFiles[$i] = $newFileName;
			}

			// All files are validated than loop again to make json data
			$uId = Vr360HelperTour::createDataDir();

			if ($uId === false)
			{
				$ajax->setMessage('Can not create data directory');
				$ajax->fail()->respond();
			}

			$destDir = VR360_PATH_DATA . '/' . $uId;

			foreach ($validFiles as $index => $fileName)
			{
				// Move uploaded file to right data directory
				if (!move_uploaded_file($_FILES['file']['tmp_name'][$index], $destDir . '/' . $fileName))
				{
					$ajax->setMessage("Cant move upload file: " . $_FILES['file']['name'][$index]);
					$ajax->fail()->respond();
				}

				// Save generated filename
				$jsonData['files'][] = $fileName;
			}

			// Create json file
			$jsonFile = $destDir . "/data.json";
			if (!file_put_contents($jsonFile, json_encode($jsonData)))
			{
				$ajax->setMessage("Cant create JSON file")->fail()->respond();
			}

			$tour = new Vr360TableTour();
			$tour->setProperties(
				array(
					'name'   => $_POST['name'],
					'dir'    => $uId,
					'alias'  => $_POST['alias'], //alias must be unique
					'status' => VR360_TOUR_STATUS_PENDING
				)
			);

			// Create tour record
			if ($tour->save() === false)
			{
				// Delete directory was created
				Vr360HelperFolder::delete($destDir);
			}


			$ajax->setData('id', $tour->id)->setMessage('Tour created success. Please wait for generate');
			$ajax->success()->respond();
		}
	}

	public function ajaxGenerateTour()
	{
		$ajax = new Vr360AjaxResponse();

		$tour = new Vr360TableTour();
		$tour->load(array(
			'id'         => (int) $_POST['id'],
			'created_by' => Vr360Authorise::getInstance()->getUser()->id
		));

		// Found tour
		if ($tour->id !== null)
		{
			$uId         = $tour->dir;
			$jsonContent = $tour->getJsonContent();

			if ($jsonContent === false)
			{
				$ajax->setMessage("Cant read JSON file: " . $tour->dir)->fail()->respond();
			}

			$jsonData = json_decode($jsonContent, true);

			//Using krpano tool to cut images
			if (Vr360HelperTour::generateTour($uId, $jsonData) === false)
			{
				$ajax->setMessage('Can not generate vTour')->fail()->respond();
			}

			//Create xml for tour
			if (Vr360HelperTour::generateXml($uId, $jsonData) === false)
			{
				$ajax->setMessage('Can not generate xml for vTour')->fail()->respond();
			}

			// Until now everything was success

			// @TODO Only update status if tour generated success
			$tour->status = VR360_TOUR_STATUS_PUBLISHED_READY;
			$tour->save();

			// Send mail
			$mailer = new Vr360Email();
			$mailer->isHTML(true);
			$mailer->Subject = 'Your tour was created and generated success';
			$mailer->Body    = Vr360Layout::fetch('email.tour.created');
			$mailer->send();

			$ajax->setMessage('Tour generated success');
			$ajax->success()->respond();
		}
	}
}