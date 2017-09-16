<?php

defined('_VR360_EXEC') or die;

class Vr360Session extends Vr360Object
{

	/**
	 * @var  int
	 */
	protected $status;

	/**
	 * @var  string
	 */
	protected $id;

	/**
	 * @var array
	 */
	protected $config = array();

	protected $namespace = null;

	public function __construct($properties = null)
	{
		parent::__construct($properties);

		$this->namespace = Vr360Configuration::getConfig('sessionNamespace', 'VR360');

		$this->config['gc_maxlifetime'] = Vr360Configuration::getConfig('cookieTime');
		$this->start();
	}

	/**
	 *
	 */
	public function start()
	{
		if (empty(session_id()))
		{
			session_start($this->config);
		}

		$this->status = session_status();
		$this->id     = session_id();

	}

	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static();

		return $instance;
	}

	public function set($property, $value)
	{
		if ($this->isValid())
		{
			$_SESSION[$this->namespace][$property] = $value;
		}
	}

	/**
	 * @return bool
	 */
	protected function isValid()
	{
		return $this->status == PHP_SESSION_ACTIVE;
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