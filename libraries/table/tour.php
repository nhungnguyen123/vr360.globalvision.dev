<?php

defined('_VR360') or die;

/**
 * Class Vr360TableTour
 */
class Vr360TableTour extends Vr360TableBase
{
	public $id = null;
	public $name = null;
	public $description = null;
	public $alias = null;
	public $created = null;
	public $created_by = null;
	public $dir = null;
	public $status = null;

	protected $_table = 'tours';

	/**
	 * Render a tour
	 */
	public function render()
	{
		Vr360Layout::load('body.user.tours.tour', array('tour' => $this));
	}

	protected function check()
	{
		// Make sure unique alias
		$tableTour = new Vr360TableTour();
		$tableTour->load(array('alias' => $this->alias));

		// This alias already exists
		if ($tableTour->id === null)
		{
			return false;
		}

		if (
			empty($this->name)
			|| empty($this->alias)
			|| empty($this->dir)
		)
		{
			return false;
		}

		if ($this->created === null)
		{
			$this->created = Vr360HelperDatetime::getMySqlFormat();
		}

		if ($this->created_by === null)
		{
			$this->created_by = Vr360Authorise::getInstance()->getUser()->id;
		}

		return parent::check();
	}

	/**
	 * @return bool|string
	 */
	public function getJsonContent()
	{
		$jsonFile = VR360_PATH_DATA . '/' . $this->dir . '/data.json';

		if (!file_exists($jsonFile) || !is_file($jsonFile))
		{
			return false;
		}

		return file_get_contents($jsonFile);
	}
}