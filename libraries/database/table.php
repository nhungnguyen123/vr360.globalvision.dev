<?php

class Vr360DatabaseTable extends Vr360Object
{

	public $id = null;
	public $params = null;

	protected $_table = null;

	public function save()
	{
		if ($this->check())
		{
			if ($this->id === null)
			{
				$properties = $this->getProperties();
				unset($properties['id']);
				return Vr360Database::getInstance()->create($this->_table, $properties);
			}
			else
			{
				return Vr360Database::getInstance()->update($this->_table, $this->getProperties());
			}
		}

		return false;
	}

	protected function check()
	{
		if (is_object($this->params) || is_array($this->params))
		{
			$this->params = json_encode($this->params);
		}

		return true;
	}
}