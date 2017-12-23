<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelHotspot
 *
 * @since  2.1.0
 */
class Vr360ModelHotspot extends Vr360Model
{
	/**
	 * Method for store default view of scenes
	 *
	 * @param   array $scenes      List of available scenes
	 * @param   array $defaultView List of default view data
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function saveDefaultView($scenes = array(), $defaultView = array())
	{
		if (empty($scenes) || empty($defaultView))
		{
			return;
		}

		$ajax = Vr360AjaxResponse::getInstance();

		foreach ($scenes as $scene)
		{
			/** @var Vr360Scene $scene */

			$sceneName     = 'scene_' . explode('.', $scene->file)[0];
			$scene->setParam('defaultview', isset($defaultView[$sceneName]) && !empty($defaultView[$sceneName]) ? $defaultView[$sceneName] : array());

			if ($scene->store())
			{
				$ajax->addMessage('Scene ' . $scene->name . ' store default view successed');
			}
			else
			{
				$ajax->addWarning('Scene ' . $scene->name . ' store default view failed');
			}
		}
	}

	/**
	 * Method for store default view of scenes
	 *
	 * @param   array $scenes   List of available scenes
	 * @param   array $hotspots List of default view data
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function saveHotspot($scenes = array(), $hotspots = array())
	{
		$ajax = Vr360AjaxResponse::getInstance();

		if (empty($scenes) || empty($hotspots))
		{
			$ajax->addInfo('There are no hotspot');
		}

		foreach ($scenes as $scene)
		{
			$key = 'scene_' . explode('.', $scene->file)[0];

			if (!isset($hotspots[$key]) || empty($hotspots[$key]))
			{
				continue;
			}

			/**
			 * Delete old hotspot
			 * But only for request scenes
			 */
			if (!Vr360ModelHotspots::getInstance()->deleteBySceneId($scene->id))
			{
				$ajax->addWarning('Can not delete hotspots')->fail()->respond();
			}

			$hotspotPrefix = 'skin_hotspotstyle|';

			foreach ($hotspots[$key] as $code => $hotspot)
			{
				/** @var Vr360Scene $scene */
				$hotspotObj = new Vr360Hotspot;

				$hotspotObj->id      = null;
				$hotspotObj->code    = $code;
				$hotspotObj->sceneId = $scene->id;
				$hotspotObj->ath     = $hotspot['ath'];
				$hotspotObj->atv     = $hotspot['atv'];
				$hotspotObj->type    = trim($hotspot['hotspot_type']);
				if ($hotspotObj->type == '')
				{
					continue;
				}
						// print_r($hotspot);die();
				switch ($hotspotObj->type)
				{
					case 'normal':
						$hotspotObj->style  = $hotspotPrefix . 'tooltip';
						$hotspotObj->params = array('linkedscene' => $hotspot['linkedscene']);
						break;
					case 'text':
						$hotspotObj->style  = 'textpopup';
						$hotspotObj->params = array(
							'hotspot_text' => $hotspot['hotspot_text'],
							'hotspot_desc' => $hotspot['hotspot_desc']
						);

						if (empty($hotspot['hotspot_text']))
						{
							$ajax->addWarning('Can not save hotspot . ' . $hotspotObj->code . ' because empty content');

							continue;
						}
						break;
					case 'video':
						$hotspotObj->style  =  'video';
						$hotspotObj->params = array('video_url' => $hotspot['hotspot_text']);

						if (empty($hotspot['hotspot_text']))
						{
							$ajax->addWarning('Can not save hotspot . ' . $hotspotObj->code . ' because empty content');

							continue;
						}
						break;
					case 'image':
						$hotspotObj->style  =  'image';
						$hotspotObj->params = array('image_url' => $hotspot['hotspot_text']);

						if (empty($hotspot['hotspot_text']))
						{
							$ajax->addWarning('Can not save hotspot . ' . $hotspotObj->code . ' because empty content');

							continue;
						}
						break;
					default:
						$hotspotObj->style  = $hotspotPrefix . 'skin_hotspotstyle|textpopup';
						$hotspotObj->params = array();
				}

				if ($hotspotObj->store())
				{
					$ajax->addSuccess('Hotspot ' . $hotspotObj->code . ' save successful');
				}
				else
				{
					$ajax->addWarning('Hotspot ' . $hotspotObj->code . ' save fail. ' . $hotspotObj->getError());
				}
			}
		}
	}
}
