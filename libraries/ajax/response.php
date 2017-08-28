<?php

class Vr360AjaxResponse extends Vr360Object
{

	public function setMessage($message)
	{
		$this->message = $message;

		return $this;
	}

	public function success()
	{
		$this->status = true;

		return $this;
	}

	public function fail ()
	{
		$this->status = false;

		return $this;
	}

	public function respond ()
	{
		header('Content-Type: application/json');
		echo $this->toJson();

		exit();
	}
}