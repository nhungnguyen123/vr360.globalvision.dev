<?php

class Vr360DatabaseTable extends Vr360Object
{

	public $id = null;
	public $params = null;

	protected $_table = null;

	public function save()
	{
		$this->check();

		if ($this->id === null)
		{
			return Vr360Database::getInstance()->create($this->_table, $this->getProperties());
		}
		else
		{
			return Vr360Database::getInstance()->update($this->_table, $this->getProperties());
		}

	}

	protected function check()
	{
		if (is_object($this->params) || is_array($this->params))
		{
			$this->params = json_encode($this->params);
		}
	}
}