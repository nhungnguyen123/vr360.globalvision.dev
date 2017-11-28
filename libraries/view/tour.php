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

	public $tour;

	/**
	 * @param string $layout
	 *
	 * @return bool|string
	 */
	public function display($layout = 'default')
	{
		$model = Vr360ModelTour::getInstance();

		$this->tour = $model->getItemFromAlias();

		$html = parent::display($layout);
		$html = $this->optimizeHtml($html);

		return $html;
	}
}
