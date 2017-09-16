<?php

defined('_VR360_EXEC') or die;

class Vr360View extends Vr360Layout
{
	protected $name = '';
	protected $layoutBase = '';

	public function __construct($baseDir = null)
	{
		parent::__construct(__DIR__ . '/view/' . $this->name . '/tmpl/');
	}

	public function display($layout = 'default')
	{
		return $this->fetch($layout, array('data' => $this));
	}

	public function fetchSub($layout, $displayData = array())
	{
		return $this->fetch('_' . $layout, array('data' => $displayData));
	}
}