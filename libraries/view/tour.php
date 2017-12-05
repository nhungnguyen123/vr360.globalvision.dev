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

	public $tour;

	/**
	 * @param   string  $layout  Default layout
	 *
	 * @return mixed|null|string|string[]
	 */
	public function display($layout = 'default')
	{
		$alias = Vr360Factory::getInput()->getString('alias');

		// New Items will get and store their data using the same Driver.
		$item = Vr360HelperCache::getItem('vtour/' . $alias);

		// Has cached
		if (!$item->isMiss())
		{
			echo 'xxx';
			return $item->get();
		}

		$model = Vr360ModelTour::getInstance();

		$this->tour = $model->getItemFromAlias();

		$html = parent::display($layout);
		$html = $this->optimizeHtml($html);

		// Store the expensive to generate data.
		Vr360HelperCache::setItem($item->set($html));

		return $html;
	}
}
