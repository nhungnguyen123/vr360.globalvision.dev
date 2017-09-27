<?php

defined('_VR360_EXEC') or die;

class Vr360ControllerTour extends Vr360Controller
{
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

			$jsonData             = $tour->getJsonData();

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
			if (Vr360HelperTour::generateTour($uId, $jsonData, $command) === null)
			{
				$ajax->addWarning('Can not generate vTour: ' . $command)->fail()->respond();
			}

			// Create xml for tour
			if (Vr360HelperTour::generateXml($uId, $jsonData) === false)
			{
				$ajax->addWarning('Can not generate xml for vTour')->fail()->respond();
			}

			// Have done
			$tour->status = VR360_TOUR_STATUS_PUBLISHED_READY;
			$tour->save();

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

	/**
	 * Create new tour
	 */
	public function ajaxCreateTour()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning('User is not authorized')->fail()->respond();
		}

		$tourName  = $input->getString('name');
		$tourAlias = $input->getString('alias');

		if (empty($tourName) || empty($tourAlias))
		{
			$ajax->addWarning('Missed fields')->fail()->respond();
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

		if ($tour !== false)
		{
			$html = Vr360Layout::getInstance()->fetch('form.hotspots', array('tour' => $tour));
		}

		else
		{
			$html = Vr360Layout::getInstance()->fetch('form.hotspotsiframe');
		}


		Vr360AjaxResponse::getInstance()->addData('html', $html)->success()->respond();
	}

	public function getEditTourHtmlHotspotEditorIFrame()
	{
		// I cant make this!
		$html = Vr360Layout::getInstance()->fetch('form.hotspotsiframe', '');
		Vr360AjaxResponse::getInstance()->addData('html', $html)->success()->respond();
	}

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

		// Rebuild json
		$hotSpotList     = json_decode($input->getString('hotspotList'), true);
		$defaultViewList = json_decode($input->getString('defaultViewList'), true);
		$uId             = $tour->dir;
		$jsonData        = json_decode(file_get_contents(VR360_PATH_DATA . "/$uId/data.json"), true);

		$jsonData['hotspotList']     = $hotSpotList;
		$jsonData['defaultViewList'] = $defaultViewList;
		$jsonData['rotation']        = $tour->params->rotation;
		$jsonData['socials']         = $tour->params->socials;
		$jsonData['defaultPano']     = $tour->params->defaultPano;

		// Create xml for tour
		if (Vr360HelperTour::generateXml($uId, $jsonData) === false)
		{
			$ajax->addWarning('Can not generate xml for vTour')->fail()->respond();
		}

		// Have done
		$tour->status = VR360_TOUR_STATUS_PUBLISHED_READY;
		$tour->save();

		// Send mail
		$mailer = new Vr360Email;
		$mailer->isHTML(true);
		$mailer->Subject = 'Your tour was created and generated success';
		$mailer->Body    = '';
		$mailer->send();

		$ajax->addSuccess('Tour generated success')->success()->respond();
	}
}
