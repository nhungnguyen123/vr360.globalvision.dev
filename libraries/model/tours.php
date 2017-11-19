<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelTours
 *
 * @since  1.0.0
 */
class Vr360ModelTours extends Vr360Model
{
	/**
	 * @return static
	 */
	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static;

		return $instance;
	}

	/**
	 * @param null $userId
	 *
	 * @return array|bool
	 */
	public function getList()
	{
		$user  = Vr360Factory::getUser();
		$input = Vr360Factory::getInput();

		$offset    = $input->getInt('page', 0) * 20;
		$limit     = 20;
		$condition = array();

		// Show all tours for administrator
		if ($user->isAdmin !== 1)
		{
			$condition = array('v2_tours.created_by' => (int) $user->id);
		}

		$condition = array_merge(
			$condition,
			array(
				'v2_tours.status[!]' => VR360_TOUR_STATUS_UNPUBLISHED,
				'ORDER'              => array('v2_tours.id' => 'DESC'),
				'LIMIT'              => array($offset, $limit)
			)
		);

		// Filter by keyword
		$keyword = $input->getString('keyword');

		if ($keyword)
		{
			$condition['v2_tours.name[~]'] = $keyword;
		}

		$rows = Vr360Database::getInstance()->select(
			'v2_tours',
			array(
				'v2_tours.id',
				'v2_tours.name',
				'v2_tours.description',
				'v2_tours.alias',
				'v2_tours.created',
				'v2_tours.created_by',
				'v2_tours.status',
				'v2_tours.params'
			),
			$condition
		);

		if (empty($rows))
		{
			return false;
		}

		$data = array();

		// Assign to tour object
		foreach ($rows as $row)
		{
			$tour          = new Vr360Tour;
			$row['params'] = json_decode($row['params']);
			$tour->bind($row);
			$data[] = $tour;
		}

		return $data;
	}

	public function getPagination($userId = null)
	{
		if ($userId === null)
		{
			$userId = Vr360Factory::getUser()->id;
		}

		$limit = 20;

		$db   = Vr360Database::getInstance();
		$rows = $db->select(
			'tours',
			[
				'tours.id',
				'tours.name',
				'tours.description',
				'tours.alias',
				'tours.created',
				'tours.created_by',
				'tours.dir',
				'tours.status'
			],
			[
				'tours.created_by' => (int) $userId,
				'tours.status[!]'  => VR360_TOUR_STATUS_UNPUBLISHED,
				'ORDER'            => [
					'tours.id' => 'DESC'
				],
			]
		);

		$input = Vr360Factory::getInput();
		if (!empty($rows))
		{
			return array(
				'current' => $input->getInt('page', 0),
				'total'   => round(count($rows) / $limit) - 1
			);
		}
	}
}