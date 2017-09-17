<?php

defined('_VR360_EXEC') or die;

class Vr360Table extends Vr360Object
{
	/**
	 * @var   int
	 */
	public $id = null;
	/**
	 * @var   object|array
	 */
	public $params = null;

	protected $_table = null;

	public function bind($properties)
	{
		$classProperties = get_class_vars($this);

		foreach ($properties as $key => $property)
		{
			if (array_key_exists($key, $classProperties))
			{
				$this->$key = $property;
			}
		}
	}

	public function load($condition)
	{
		if (is_int($condition))
		{
			$condition = array('id' => $condition);
		};

		$db  = Vr360Database::getInstance();
		$row = $db->load($this->_table, $condition);

		if ($row !== false)
		{
			$this->setProperties($row);
			$this->params = json_decode($this->params);
		}

		return $this;
	}

	/**
	 * @return bool|PDOStatement
	 */
	public function save()
	{
		if ($this->check())
		{
			if ($this->id === null)
			{
				$properties = $this->getProperties();
				unset($properties['id']);

				$this->id = Vr360Database::getInstance()->create($this->_table, $properties);

				return $this->id;
			}
			else
			{
				return Vr360Database::getInstance()->update($this->_table, $this->getProperties());
			}
		}

		return false;
	}

	/**
	 * Basic checking
	 *
	 * @return boolean
	 */
	protected function check()
	{
		if ($this->_table === null)
		{
			return false;
		}

		// Convert params to json before saving
		if (is_object($this->params) || is_array($this->params))
		{
			$this->params = json_encode($this->params);
		}

		return true;
	}
}
