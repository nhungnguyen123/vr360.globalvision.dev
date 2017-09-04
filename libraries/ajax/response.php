<?php

defined('_VR360') or die;

/**
 * Class Vr360AjaxResponse
 */
class Vr360AjaxResponse extends Vr360Object
{

	public function setMessage($message)
	{
		$this->message = $message;

		return $this;
	}

	public function setData($key, $key_value)
	{
		$this->data[$key] = $key_value;

		return $this;
	}

	public function success()
	{
		$this->message = '<span class="label label-success">' . $this->message. '</span>';
		$this->status = true;

		return $this;
	}

	public function fail()
	{
		$this->message = '<span class="label label-danger">' . $this->message. '</span>';
		$this->status = false;

		return $this;
	}

	public function respond()
	{
		header('Content-Type: application/json');
		echo $this->toJson();

		exit();
	}
}
