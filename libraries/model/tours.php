<?php

defined('_VR360') or die;

/**
 * Class Vr360ModelTours
 */
class Vr360ModelTours extends Vr360Database
{
	/**
	 * @return static
	 */
	public static function getInstance()
	{
		static $instance;

		if (!isset($instance))
		{
			$instance = new static();
		}

		return $instance;
	}

	public function getList($userId = null)
	{
		if ($userId === null)
		{
			$userId = Vr360Authorise::getInstance()->getUser()->id;
		}

		$offset = isset($_REQUEST['page']) ? $_REQUEST['page'] * 20 : 0;
		$limit  = 20;

		$rows = $this->medoo->select(
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

				'LIMIT' => [
					$offset,
					$limit
				]

			]
		);

		if (!empty($rows))
		{
			$data = array();
			foreach ($rows as $row)
			{
				$data[] = new Vr360TableTour($row);
			}

			return $data;
		}

		return $rows;
	}

	public function getPagination($userId = null)
	{
		if ($userId === null)
		{
			$userId = Vr360Authorise::getInstance()->getUser()->id;
		}

		$limit = 20;

		$rows = $this->medoo->select(
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
				'ORDER'                => [
					'tours.id' => 'DESC'
				],
			]
		);

		if (!empty($rows))
		{
			return array(
				'current' => isset($_REQUEST['page']) ? $_REQUEST['page'] : 1,
				'total'   => round(count($rows) / $limit)
			);
		}
	}
}