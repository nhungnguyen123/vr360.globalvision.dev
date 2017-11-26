<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelHotspot
 *
 * @since  3.0.0
 */
class Vr360ModelHotspot extends Vr360Model
{
	/**
	 * @return static
	 */
	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static;

		return $instance;
	}

	/**
	 * Method for store default view of scenes
	 *
	 * @param   array $scenes      List of available scenes
	 * @param   array $defaultView List of default view data
	 *
	 * @return  void
	 *
	 * @since   3.0.0
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
			$scene->params = isset($defaultView[$sceneName]) && !empty($defaultView[$sceneName]) ? $defaultView[$sceneName] : array();

			if ($scene->save())
			{
				$ajax->addMessage('Scene ' . $scene->name . ' store default view success');
			}
			else
			{
				$ajax->addWarning('Scene ' . $scene->name . ' store default view fail');
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
	 * @since   3.0.0
	 */
	public function saveHotspot($scenes = array(), $hotspots = array())
	{
		if (empty($scenes) || empty($hotspots))
		{
			return;
		}

		$ajax = Vr360AjaxResponse::getInstance();

		foreach ($scenes as $scene)
		{
			// Delete old hotspot
			Vr360Database::getInstance()->delete('hotspots', array('sceneId' => $scene->id));

			$key = 'scene_' . explode('.', $scene->file)[0];

			if (!isset($hotspots[$key]) || empty($hotspots[$key]))
			{
				continue;
			}

			foreach ($hotspots[$key] as $code => $hotspot)
			{
				/** @var Vr360Scene $scene */
				$hotspotObj = new Vr360Hotspot;

				$hotspotObj->id      = null;
				$hotspotObj->code    = $code;
				$hotspotObj->sceneId = $scene->id;
				$hotspotObj->ath     = $hotspot['ath'];
				$hotspotObj->atv     = $hotspot['atv'];
				$hotspotObj->type    = $hotspot['hotspot_type'];

				if ($hotspotObj->type == 'normal')
				{
					$hotspotObj->style  = 'tooltip';
					$hotspotObj->params = array('linkedscene' => $hotspot['linkedscene']);
				}
				elseif ($hotspotObj->type == 'text')
				{
					$hotspotObj->style  = 'textpopup';
					$hotspotObj->params = array('hotspot_text' => $hotspot['hotspot_text']);
				}

				$hotspotObj->params = json_encode($hotspotObj->params);

				if ($hotspotObj->save())
				{
					$ajax->addSuccess('Hotspot ' . $hotspotObj->code . ' save successful');
				}
				else
				{
					$ajax->addWarning('Hotspot ' . $hotspotObj->code . ' save fail');
				}
			}
		}
	}
}
