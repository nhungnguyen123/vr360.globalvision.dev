<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableTour
 *
 * @since  2.0.0
 */
class Vr360TableTour extends Vr360Table
{
	/**
	 * @var null
	 */
	public $name = null;

	public $alias = null;
	public $description = null;
	public $keyword = null;
	public $ordering  = null;
	public $created  = null;
	public $created_by  = null;
	public $status  = null;
	/**
	 * @var string
	 */
	protected $_table = 'v2_tours';

	/**
	 * @return bool
	 */
	protected function check()
	{
		$db    = Vr360Database::getInstance();
		$tours = $db->select(
			$this->_table,
			'*',
			array
			(
				'alias' => $this->alias
			)
		);

		if ($tours !== false && count($tours) > 0)
		{
			if ($tours[0]['id'] == $this->get('id'))
			{
				$this->setError('Duplicated alias');

				return false;
			}
		}

		if (empty($this->name) || empty($this->alias))
		{
			$this->setError('Missing name or alias');

			return false;
		}

		if ($this->created === null)
		{
			$this->created = Vr360HelperDatetime::getMySqlFormat();
		}

		if ($this->created_by === null)
		{
			$this->created_by = Vr360Factory::getUser()->id;
		}

		if ($this->status === null)
		{
			$this->status = VR360_TOUR_STATUS_PUBLISHED_READY;
		}

		return parent::check();
	}
}
