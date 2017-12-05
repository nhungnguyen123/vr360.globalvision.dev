<?php

defined('_VR360_EXEC') or die;

/**
 * Hotspot controller class
 *
 * @since   2.1.0
 */
class Vr360ControllerHotspot extends Vr360Controller
{
	/**
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function ajaxSaveHotspot()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		// Get tour by ID
		$tour = new Vr360Tour;
		$tour->load(
			array(
				'id'         => (int) $input->getInt('id'),
				'created_by' => Vr360Factory::getUser()->id
			)
		);

		// Tour not found
		if (!$tour->id)
		{
			$ajax->addDanger('Tour is not available')->fail()->respond();
		}

		// Hotspots
		$hotspotsList     = json_decode($input->getString('hotspotList'), true);

		// Default view
		$defaultViewsList = json_decode($input->getString('defaultViewList'), true);

		if ($hotspotsList === null || $defaultViewsList === null)
		{
			$ajax->addWarning('Invalid data')->fail()->respond();
		}

		// Get all scenes of current tour
		$scenes = $tour->getScenes();

		$hotspotModel = Vr360ModelHotspot::getInstance();
		$hotspotModel->saveDefaultView($scenes, $defaultViewsList);
		$hotspotModel->saveHotspot($scenes, $hotspotsList);

		Vr360ModelTour::getInstance()->modifyXML($tour);

		$ajax->addInfo('Data saved success')->success()->respond();
	}
}
