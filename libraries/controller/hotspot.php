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

		echo '<pre>';
		echo print_r($hotspotsList);
		echo '</pre>';
		echo '<pre>';
		echo print_r($defaultViewsList);
		echo '</pre>';
		exit;

		/*if ($hotspotsList === null || $defaultViewsList === null)
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

		$ajax->success()->respond();*/
	}
}
