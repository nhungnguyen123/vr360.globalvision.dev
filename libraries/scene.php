<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableScene
 *
 * @since   3.0.0
 */
class Vr360Scene extends Vr360TableScene
{
	/**
	 * Method for delete Scene
	 *
	 * @return  boolean
	 *
	 * @since   3.0.0
	 */
	public function delete()
	{
		if (!$this->id)
		{
			return false;
		}

		$path = VR360_PATH_DATA . '/' . $this->tourId . '/' . $this->file;

		// Delete scene
		if (!Vr360Database::getInstance()->delete('v2_scenes', array('id' => $this->id)))
		{
			return false;
		}

		// Delete hotspots
		if (!Vr360Database::getInstance()->delete('hotspots', array('sceneId' => $this->id)))
		{
			return false;
		}

		// Finally delete physical files
		if (!Vr360HelperFile::delete($path))
		{
			return false;
		}

		return true;
	}

	/**
	 * Method for get all hotspots of scene
	 *
	 * @return  array|false  Array of hotspots. False otherwise.
	 *
	 * @since   3.0.0
	 */
	public function getHotspots()
	{
		if (!$this->id)
		{
			return false;
		}

		$items = Vr360Database::getInstance()->select(
			'hotspots',
			array(
				'id', 'sceneId', 'code', 'ath', 'atv', 'style', 'type', 'params'
			),
			array(
				'sceneId' => $this->id,
				'ORDER'  => array('id' => 'ASC')
			)
		);

		if (empty($items))
		{
			return false;
		}

		foreach ($items as $key => $item)
		{
			$scene = new Vr360Hotspot;

			$item['params'] = json_decode($item['params']);
			$scene->bind($item);
			$items[$key] = $scene;
		}

		return $items;
	}
}
