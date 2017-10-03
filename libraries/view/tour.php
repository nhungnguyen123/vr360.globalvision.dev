<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ViewTour
 */
class Vr360ViewTour extends Vr360View
{
	/**
	 * @var string
	 */
	protected $name = 'tour';

	/**
	 * @param string $layout
	 *
	 * @return bool|string
	 */
	public function display($layout = 'default')
	{
		if ($html = $this->getCache())
		{
			return $html;
		}

		$model = Vr360ModelTour::getInstance();

		$this->tour = $model->getItem();

		// Try to migrate tour before render
		$this->tour->migrate();

		$html = parent::display($layout);
		$this->writeCache($html);

		return $html;
	}
}
