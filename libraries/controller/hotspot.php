<?php

defined('_VR360_EXEC') or die;

/**
 * Hotspot controller class
 *
 * @since   3.0.0
 */
class Vr360ControllerHotspot extends Vr360Controller
{
	/**
	 *
	 */
	public function ajaxSaveHotspot()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		$tour = new Vr360Tour;
		$tour->load(
			array(
				'id'         => (int) $input->getInt('id'),
				'created_by' => Vr360Factory::getUser()->id
			)
		);

		if (!$tour->id)
		{
			$ajax->addDanger('Tour not available')->fail()->respond();
		}

		$hotspotsList     = json_decode($input->getString('hotspotList'), true);
		$defaultViewsList = json_decode($input->getString('defaultViewList'), true);

		if ($hotspotsList === null || $defaultViewsList === null)
		{
			$ajax->addWarning('Invalid data')->fail()->respond();
		}

		$scenes = $tour->getScenes();

		$hotspotModel = Vr360ModelHotspot::getInstance();

		$hotspotModel->saveDefaultView($scenes, $defaultViewsList);
		$hotspotModel->saveHotspot($scenes, $hotspotsList);

		Vr360ModelTour::getInstance()->modifyXML($tour);

		$ajax->success()->respond();
	}
}
