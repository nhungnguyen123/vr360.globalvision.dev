<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableScene
 *
 * @since   2.1.0
 */
class Vr360TableHotspot extends Vr360Table
{
	/**
	 * @var  integer
	 */
	public $sceneId = null;

	/**
	 * @var  string
	 */
	public $code = null;

	/**
	 * @var  string
	 */
	public $ath = null;

	/**
	 * @var  string
	 */
	public $atv = null;

	/**
	 * @var  string
	 */
	public $style = null;

	/**
	 * @var  string
	 */
	public $type = null;

	/**
	 * @var  string
	 */
	public $params = null;

	/**
	 * @var string
	 */
	protected $_table = 'hotspots';

	/**
	 * @return boolean
	 */
	protected function check()
	{
		// SceneId must be provided
		if ($this->sceneId === null)
		{
			$this->setError('Missing sceneId');

			return false;
		}

		return parent::check();
	}
}
