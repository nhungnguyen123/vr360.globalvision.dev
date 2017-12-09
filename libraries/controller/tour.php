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

	/**
	 * Method for load create/edit tour html form
	 *
	 * @since   2.1.0
	 *
	 * @return  void
	 */
	public function ajaxGetTourHtml()
	{
		$tour = Vr360ModelTour::getInstance()->getItem();

		if ($tour)
		{
			$html = Vr360Layout::getInstance()->fetch('form.tour', array('tour' => $tour));
		}
		else
		{
			$html = Vr360Layout::getInstance()->fetch('form.tour', array('tour' => new Vr360Tour));
		}

		Vr360AjaxResponse::getInstance()->addData('html', $html)->success()->respond();
	}

	/**
	 * @return  void
	 */
	public function ajaxGetTourEmbedHtml()
	{
		$tour = Vr360ModelTour::getInstance()->getItem();

		if ($tour)
		{
			$html = Vr360Layout::getInstance()->fetch('tour.embed', array('tour' => $tour));
			Vr360AjaxResponse::getInstance()->addData('html', $html)->success()->respond();
		}
	}

	/**
	 * @return  void
	 */
	public function ajaxDeleteTour()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		$tour = Vr360ModelTour::getInstance()->getItem();

		if (!$tour)
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

	/**
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function ajaxValidateAlias()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		$result = Vr360ModelTour::getInstance()->validateAlias();

		if ($result)
		{
			$ajax->addDanger('Duplicated alias. Please use another tour name or manual change alias')->fail()->respond();
		}

		$ajax->success()->respond();
	}
}
