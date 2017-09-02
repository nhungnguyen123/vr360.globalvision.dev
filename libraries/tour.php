<?php

class Vr360Tour extends Vr360DatabaseTable
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

	public function render()
	{
		Vr360Layout::load('body.user.tours.tour', array('tour' => $this));
	}

	protected function check()
	{
		if ($this->created === null)
		{
			$this->created = Vr360HelperDatetime::getMySqlFormat();
		}

		return parent::check();
	}
}