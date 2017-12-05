<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Hotspot
 *
 * @since   2.1.0
 */
class Vr360Hotspot extends Vr360TableHotspot
{
	/**
	 * Method for delete Scene
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public function delete()
	{
		if (!$this->id)
		{
			return false;
		}

		return Vr360Database::getInstance()->delete('hotspots', array('id' => $this->id));
	}
}
