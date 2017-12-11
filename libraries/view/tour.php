<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ViewTour
 *
 * @since  2.0.0
 */
class Vr360ViewTour extends Vr360View
{
	/**
	 * @var string
	 */
	protected $name = 'tour';

	/**
	 * @var  Vr360Tour
	 */
	public $tour;

	/**
	 * @param   string  $layout  Default layout
	 *
	 * @return mixed|null|string|string[]
	 */
	public function display($layout = 'default')
	{
		$this->tour = Vr360ModelTour::getInstance()->getItemByAlias();

		if (!$this->tour)
		{
			echo 'Tour not found'; exit();
		}

		if ($this->tour)
		{
			Vr360Factory::getInput()->set('id', $this->tour->get('id'));
		}

		$html = parent::display($layout);
		$html = $this->optimizeHtml($html);

		return $html;
	}
}
