<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableScene
 */
class Vr360Scene extends Vr360TableScene
{
	public function delete()
	{
		if (!$this->id)
		{
			return false;
		}

		$path = VR360_PATH_DATA . '/' . $this->tourId . '/' . $this->file;

		if (!Vr360Database::getInstance()->delete('v2_scenes', array('id' => $this->id)))
		{
			return false;
		}

		if (!Vr360HelperFile::delete($path))
		{
			return false;
		}

		return true;
	}
}
