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
		// Create Driver with default options
		$driver = new Stash\Driver\FileSystem(array('path' => VR360_PATH_ROOT . '/cache'));

		// Inject the driver into a new Pool object.
		$pool = new Stash\Pool($driver);


		// New Items will get and store their data using the same Driver.
		$item = $pool->getItem('vtour/' . md5(VR360_URL_FULL));

		// Has cached
		if (!$item->isMiss())
		{
			return $item->get();
		}

		$model = Vr360ModelTour::getInstance();

		$this->tour = $model->getItem();

		// Try to migrate tour before render
		$this->tour->migrate();

		$html = parent::display($layout);
		$html = $this->optimizeHtml($html);

		// Store the expensive to generate data.
		$pool->save($item->set($html));

		return $html;
	}
}
