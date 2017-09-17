<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelTour
 */
class Vr360ModelTour extends Vr360Model
{
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

	public function getItem()
	{
		$alias = Vr360Factory::getInput()->getRaw('alias');

		$table = new Vr360TableTour;
		$table->load(array(
			'alias' => $alias
		));

		return $table;
	}
}