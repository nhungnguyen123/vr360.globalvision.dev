<?php

class Vr360Session
{
	protected $status;
	protected $id;
	protected $namespace = 'GLOBALVISION';

	protected $config = array(
		'lifetime' => 60 * 60 * 24
	);

	public function __construct()
	{
		// Reset sessions
		if (session_id())
		{
			$this->reset();
		}

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

	protected function start()
	{
		if (empty(session_id()) || session_status() == PHP_SESSION_NONE)
		{
			session_start();
			session_set_cookie_params($this->config['lifetime']);

			$_SESSION[$this->namespace] = array();
		}

		$this->status = session_status();
		$this->id     = session_id();
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