<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360AjaxResponse
 *
 * @since 2.0.0
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
	 * @var boolean
	 */
	protected $status = false;

	/**
	 * @return static
	 */
	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static;

		return $instance;
	}

	/**
	 * @param   string $message Message
	 *
	 * @return Vr360AjaxResponse
	 */
	public function addDefault($message)
	{
		return $this->addMessage($message, 'default');
	}

	/**
	 * @param   string $message Message
	 * @param   string $type    Type
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

	/**
	 * @param   string $message Message
	 *
	 * @return Vr360AjaxResponse
	 */
	public function addPrimary($message)
	{
		return $this->addMessage($message, 'primary');
	}

	/**
	 * @param   string $message Message
	 *
	 * @return Vr360AjaxResponse
	 */
	public function addSuccess($message)
	{
		return $this->addMessage($message, 'success');
	}

	/**
	 * @param   string $message Message
	 *
	 * @return Vr360AjaxResponse
	 */
	public function addInfo($message)
	{
		return $this->addMessage($message, 'info');
	}

	/**
	 * @param   string $message Message
	 *
	 * @return Vr360AjaxResponse
	 */
	public function addWarning($message)
	{
		return $this->addMessage($message, 'warning');
	}

	/**
	 * @param   string $message Message
	 *
	 * @return Vr360AjaxResponse
	 */
	public function addDanger($message)
	{
		return $this->addMessage($message, 'danger');
	}

	/**
	 * @param   string $key   Key
	 * @param   mixed  $value Value
	 *
	 * @return $this
	 */
	public function addData($key, $value)
	{
		$this->data[$key] = $value;

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
		$this->status = false;

		return $this;
	}

	/**
	 * @return  void
	 */
	public function respond()
	{
		header('Content-Type: application/json');
		echo $this->toJson();

		exit();
	}
}
