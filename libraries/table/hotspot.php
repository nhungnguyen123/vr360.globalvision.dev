<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableScene
 */
class Vr360TableHotspot extends Vr360Table
{
	public $sceneId = null;
	public $code = null;
	public $ath = null;
	public $atv = null;
	public $style = null;
	public $type = null;
	public $params = null;

	/**
	 * @var string
	 */
	protected $_table = 'hotspots';
}
