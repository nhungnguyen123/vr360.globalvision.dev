<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableScene
 */
class Vr360TableScene extends Vr360Table
{


	public $tourId = null;
	/**
	 * @var null
	 */
	public $name = null;
	public $description = null;
	public $file  = null;
	public $ordering  = null;
	public $status  = null;
	public $params  = null;

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

		return parent::check();
	}
}
