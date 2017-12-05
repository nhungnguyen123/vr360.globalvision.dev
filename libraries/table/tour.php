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
	 * @var string
	 */
	public $name = null;

	/**
	 * @var string
	 */
	public $alias = null;

	/**
	 * @var string
	 */
	public $description = null;

	/**
	 * @var string
	 */
	public $keyword = null;

	/**
	 * @var string
	 */
	public $ordering  = null;

	/**
	 * @var string
	 */
	public $created  = null;

	/**
	 * @var integer
	 */
	public $created_by  = null;

	/**
	 * @var string
	 */
	public $status  = null;

	/**
	 * @var string
	 */
	protected $_table = 'tours';

	/**
	 * @return boolean
	 */
	protected function check()
	{
		$db    = Vr360Database::getInstance();

		$condition = array('alias' => $this->alias);

		if ($this->id)
		{
			$condition['id[!]'] = $this->get('id');
		}

		$tours = $db->select(
			$this->_table,
			'*',
			$condition
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
