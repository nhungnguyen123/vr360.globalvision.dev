<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableScene
 */
class Vr360TableScene extends Vr360Table
{
	public $tourId = null;
	public $name = null;
	public $description = null;
	public $file = null;
	public $ordering = null;
	public $status = null;
	public $params = null;
	public $default = null;

	/**
	 * @var string
	 */
	protected $_table = 'v2_scenes';

	/**
	 * @return bool
	 */
	protected function check()
	{
		if ($this->status === null)
		{
			$this->status = 1;
		}

		if ($this->default === null)
		{
			$this->default = 0;
		}

		if ($this->ordering === null)
		{
			$this->ordering = Vr360Database::getInstance()->max('v2_scenes', 'ordering', array('tourId' => $this->tourId)) + 1;
		}

		return parent::check();
	}
}
