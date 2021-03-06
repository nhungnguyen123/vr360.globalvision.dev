<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Object
 *
 * @since 2.0.0
 */
class Vr360Object
{
	protected $_errors = array();

	/**
	 * Vr360Object constructor.
	 *
	 * @param   array $properties Properties
	 */
	public function __construct($properties = null)
	{
		if ($properties !== null)
		{
			$this->setProperties($properties);
		}
	}

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

	/**
	 * @param   array $properties Properties
	 *
	 * @return  boolean
	 */
	public function setProperties($properties)
	{
		if (is_array($properties) || is_object($properties))
		{
			foreach ((array) $properties as $k => $v)
			{
				// Use the set function which might be overridden.
				$this->set($k, $v);
			}

			return true;
		}

		return false;
	}

	/**
	 * @param   string $property Property
	 * @param   mixed  $value    Value
	 *
	 * @return null
	 */
	public function set($property, $value)
	{
		$previous          = property_exists($this, $property) ? $this->{$property} : null;
		$this->{$property} = $value;

		return $previous;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return get_class($this);
	}

	/**
	 * @param   string $property Property
	 * @param   mixed  $default  Default value
	 *
	 * @return  mixed
	 */
	public function def($property, $default = null)
	{
		$value = $this->get($property, $default);

		return $this->set($property, $value);
	}

	public function get($property, $default = null)
	{
		if (isset($this->$property))
		{
			return $this->$property;
		}

		return $default;
	}

	/**
	 * @return string
	 */
	public function toJson()
	{
		return json_encode($this->getProperties());
	}

	/**
	 * @param  bool $public
	 *
	 * @return array
	 */
	public function getProperties($public = true)
	{
		$vars = get_object_vars($this);

		if ($public)
		{
			foreach ($vars as $key => $value)
			{
				if ('_' == substr($key, 0, 1))
				{
					unset($vars[$key]);
				}
			}
		}

		return $vars;
	}

	public function setError($error)
	{
		$this->_errors[] = $error;
	}

	public function getError()
	{
		return array_pop($this->_errors);
	}

	public function getErrors()
	{
		return $this->_errors;
	}

	public function hasError()
	{
		return !empty($this->_errors);
	}

	public function resetErrors()
	{
		$this->_errors = array();
	}
}
