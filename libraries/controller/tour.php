<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ControllerTour
 */
class Vr360ControllerTour extends Vr360Controller
{
	public function ajaxSaveTour()
	{
		$ajax  = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning('User is not authorized')->fail()->respond();
		}

		Vr360ModelTour::getInstance()->ajaxSave();
	}
	/**
	 * Create new tour
	 *
	 * @since  2.0.0
	 */
	public function ajaxCreateTour()
	{
		$input = Vr360Factory::getInput();

		$tourName  = $input->getString('name');
		$tourAlias = $input->getString('alias');

		if (empty($tourName) || empty($tourAlias))
		{
			Vr360AjaxResponse::getInstance()->addWarning('Missed fields')->fail()->respond();
		}

		switch ($input->getString('step'))
		{
			case 'uploadFile':
				Vr360ModelTour::getInstance()->ajaxUploadFile();
				break;
			case 'createTour':
				Vr360ModelTour::getInstance()->ajaxCreateTour();
				break;
		}
	}

	/**
	 * Generate tour
	 *
	 * @return bool
	 */
	public function ajaxGenerateTour()
	{
		var_dump('here');
		exit;

		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning('User is not authorized')->fail()->respond();
		}

		$tour = Vr360ModelTour::getInstance()->getItem();

		// Found tour
		if ($tour->id !== null)
		{
			$uId      = $tour->dir;
			$jsonFile = $tour->getFile('data.json');

			if (!file_exists($jsonFile) || !is_file($jsonFile))
			{
				$ajax->addWarning('File not found')->fail()->respond();
			}

			$jsonData = $tour->getJsonData();

			if (!is_array($jsonData))
			{
				$ajax->addWarning('Data not found')->fail()->respond();
			}

			$jsonData['rotation'] = isset($tour->params->rotation) ? $tour->params->rotation : 0;
			$jsonData['socials']  = isset($tour->params->socials) ? $tour->params->socials : 0;

			// There is no panos then we'll not execute generate
			if (!isset($jsonData['panos']))
			{
				$ajax->addWarning('No panos')->fail()->respond();
			}

			// Using krpano tool to cut images
			$command = '';
			$result  = Vr360HelperTour::generateTour($uId, $jsonData, $command);
			if ($result === null || $result === false)
			{
				$ajax->addWarning('Can not generate vTour: ' . $command)->fail()->respond();
			}

			$ajax->addInfo($command);

			// Create xml for tour
			if (Vr360HelperTour::generateXml($uId, $jsonData) === false)
			{
				$ajax->addWarning('Can not generate xml for vTour')->fail()->respond();
			}

			// Have done
			// No need to save tour again

			// Send mail
			$mailer = new Vr360Email;
			$mailer->isHTML(true);
			$mailer->Subject = 'Your tour was created and generated success';
			$mailer->Body    = '';
			$mailer->send();

			$ajax->addSuccess('Tour generated success')->success()->respond();
		}
	}

	/**
	 * Edit tour use same flow of create tour
	 */
	public function ajaxEditTour()
	{
		$this->ajaxCreateTour();
	}

	public function ajaxRemoveTour()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		$tour = new Vr360Tour;
		$tour->load(
			array
			(
				'id'         => (int) $input->getInt('id'),
				'created_by' => Vr360Factory::getUser()->id
			)
		);

		$tour->status = VR360_TOUR_STATUS_UNPUBLISHED;

		if ($tour->save() !== false)
		{
			$ajax->addData('id', $tour->id);
			$ajax->addSuccess('Tour unpublished')->success()->respond();
		}

		$ajax->addWarning('Something wrong')->fail()->respond();
	}

	/**
	 * Method for load create/edit tour html form
	 *
	 * @since   3.0.0
	 */
	public function ajaxGetTourHtml()
	{
		$input = Vr360Factory::getInput();

		$tour = new Vr360Tour;
		$tour->load(
			array
			(
				'id'         => (int) $input->getInt('id'),
				'created_by' => Vr360Factory::getUser()->id
			)
		);

		// Try to migrate tour
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


	public function ajaxGetHotspotEditorHtml()
	{
		$input = Vr360Factory::getInput();

		$tour = new Vr360Tour;
		$tour->load(
			array
			(
				'id'         => (int) $input->getInt('id'),
				'created_by' => Vr360Factory::getUser()->id
			)
		);

		$ajaxResponse = Vr360AjaxResponse::getInstance();

		if ($tour === false)
		{
			$ajaxResponse->addData('html', 'Tour not found.')->fail()->respond();
		}
		else
		{
			$html = Vr360Layout::getInstance()->fetch('form.hotspots', array('tour' => $tour));

			$ajaxResponse->addData('html', $html)->success()->respond();
		}
	}

	/**
	 *
	 */
	public function ajaxSaveHotspot()
	{
		$ajax = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		$tour = new Vr360Tour;
		$tour->load(
			array
			(
				'id'         => (int) $input->getInt('id'),
				'created_by' => Vr360Factory::getUser()->id
			)
		);

		if ($tour)
		{
			// Get current json data
			$jsonData                    = $tour->getJsonData();

			$hotspotsList = json_decode($input->getString('hotspotList'), true);
			$defaultViewsList = json_decode($input->getString('defaultViewList'), true);

			if ($hotspotsList === null || $defaultViewsList === null)
			{
				$ajax->addWarning('Invalid data')->fail()->respond();
			}

			// Apply data
			foreach ($hotspotsList as $scene => $hotspots)
			{
				$jsonData['hotspotList'][$scene] = $hotspots;
			}

			// Apply data
			foreach ($defaultViewsList as $scene => $defaultView)
			{
				$jsonData['defaultViewList'][$scene] = $defaultView;
			}

			// Create xml for tour
			if (Vr360HelperTour::generateXml($tour->dir, $jsonData) === false)
			{
				$ajax->addWarning('Can not generate xml for vTour')->fail()->respond();
			}

			// Write back to data.json
			$jsonFile = $tour->getFile('data.json');
			Vr360HelperFile::write($jsonFile, json_encode($jsonData));
		}

		$ajax->success()->respond();
	}
}
