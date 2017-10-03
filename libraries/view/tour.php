<?php

defined('_VR360_EXEC') or die;

class Vr360ViewTour extends Vr360View
{
	protected $name = 'tour';

	public function display($layout = 'default')
	{
		$model = Vr360ModelTour::getInstance();

		$this->tour = $model->getItem();

		// Try to migrate tour before render
		$this->tour->migrate();

		return parent::display($layout);
	}
}
