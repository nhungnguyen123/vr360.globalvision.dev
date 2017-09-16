<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360AjaxResponse
 */
class Vr360AjaxResponse extends Vr360Object
{
	/**
	 * @var array
	 */
	protected $data = null;

	/**
	 * @var array
	 */
	protected $messages = null;

	/**
	 * @var bool
	 */
	protected $status = false;

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

	public function addDefault($message)
	{
		return $this->addMessage($message, 'default');
	}

	/**
	 * @param        $message
	 * @param string $type
	 *
	 * @return $this
	 */
	public function addMessage($message, $type = '')
	{
		if (!empty($type))
		{
			$message = '<span class="label label-' . $type . '">' . $message . '</span>';
		}

		$this->messages[] = $message;

		return $this;
	}

	public function addPrimary($message)
	{
		return $this->addMessage($message, 'primary');
	}

	public function addSuccess($message)
	{
		return $this->addMessage($message, 'success');
	}

	public function addInfo($message)
	{
		return $this->addMessage($message, 'info');
	}

	public function addWarning($message)
	{
		return $this->addMessage($message, 'warning');
	}

	public function addDanger($message)
	{
		return $this->addMessage($message, 'danger');
	}

	/**
	 * @param $key
	 * @param $key_value
	 *
	 * @return $this
	 */
	public function addData($key, $key_value)
	{
		$this->data[$key] = $key_value;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function success()
	{
		$this->status = true;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function fail()
	{
		if (isset($this->message))
		{
			$this->message = '<span class="label label-danger">' . $this->message . '</span>';
		}

		$this->status = false;

		return $this;
	}

	/**
	 *
	 */
	public function respond()
	{
		header('Content-Type: application/json');
		echo $this->toJson();

		exit();
	}
}
