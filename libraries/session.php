<?php

class Vr360Session
{
	protected $status;
	protected $id;
	protected $namespace = '\GLOBALVISION\VR360';

	protected $config = array(
		'gc_maxlifetime' => 60 * 60 * 24
	);

	public function __construct()
	{
		$this->start();
	}

	public static function getInstance()
	{
		static $instance;

		if (!isset($instance))
		{
			$instance = new static();
		}

		return $instance;
	}

	public function start()
	{
		if (empty(session_id()))
		{
			session_start($this->config);
		}

		$this->status = session_status();
		$this->id = session_id();

	}

	protected function isValid()
	{
		return $this->status == PHP_SESSION_ACTIVE;
	}

	public function set($property, $value)
	{
		if ($this->isValid())
		{
			$_SESSION[$this->namespace][$property] = $value;
		}
	}

	public function get($property, $default = null)
	{
		if ($this->isValid())
		{
			if (isset($_SESSION[$this->namespace][$property]))
			{
				$default = $_SESSION[$this->namespace][$property];
			}
		}

		return $default;
	}

	public function reset()
	{
		session_unset();
		session_destroy();
	}
}