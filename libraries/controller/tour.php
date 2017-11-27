<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ControllerTour
 */
class Vr360ControllerTour extends Vr360Controller
{
	/**
	 * @return  void
	 */
	public function display()
	{
		$alias = Vr360Factory::getInput()->getString('alias');

		if (!empty($alias))
		{
			// Set input with tour ID for use in view
			$tourId = Vr360Database::getInstance()->select('v2_tours', array('id'), array('alias' => $alias));

			if (!empty($tourId))
			{
				Vr360Factory::getInput()->set('id', $tourId[0]['id']);
			}
		}

		parent::display();
	}

	/**
	 *
	 */
	public function ajaxSaveTour()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning('User is not authorized')->fail()->respond();
		}

		Vr360ModelTour::getInstance()->ajaxSave();
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

	public function ajaxDeleteTour()
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

		if (!$tour->id)
		{
			$ajax->addDanger('Tour is not available.')->fail()->respond();
		}

		if (!$tour->delete())
		{
			$ajax->addDanger('Delete tour: ' . (int) $input->getInt('id') . ' fail')->fail()->respond();
		}

		$ajax->addSuccess('Delete tour: ' . (int) $input->getInt('id') . ' success')->success()->respond();
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

		if ($tour)
		{
			// Get current json data
			$jsonData = $tour->getJsonData();

			$hotspotsList     = json_decode($input->getString('hotspotList'), true);
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
