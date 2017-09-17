<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Object
 *
 * @since 2.0.0
 */
class Vr360Object
{

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

	/**
	 * @param   array  $properties  Properties
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
	 * @param   string  $property   Property
	 * @param   mixed   $value      Value
	 *
	 * @return null
	 */
	public function set($property, $value)
	{
		$previous        = isset($this->$property) ? $this->$property : null;
		$this->$property = $value;

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
	 * @param   string  $property   Property
	 * @param   mixed   $default    Default value
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
}
