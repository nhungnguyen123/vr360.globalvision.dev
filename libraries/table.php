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

	public function load($condition)
	{

		$db  = Vr360Database::getInstance();
		$row = $db->load($this->_table, $condition);

		if ($row !== false)
		{
			$this->setProperties($row);
			$this->params = new Vr360Object(json_decode($this->params));
		}
		else
		{
			$this->params = new Vr360Object();
		}

		return $this;
	}

	/**
	 * @return   bool|PDOStatement
	 */
	public function save()
	{
		if (!$this->check())
		{
			return false;
		}

		$db = Vr360Database::getInstance();

		// Insert
		if ($this->id === null)
		{
			$properties = $this->getProperties();
			unset($properties['id']);

			$db->insert($this->_table, $properties);

			if (!$db->id())
			{
				$errors = $db->error();
				$this->setError(end($errors));

				return false;
			}

			$this->id = $db->id();
		}
		else
		{
			$properties = $this->getProperties();

			if (!$db->update($this->_table, $properties, array('id' => $properties['id'])))
			{
				$this->setError(end($db->error()));

				return false;
			}
		}

		return true;
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
