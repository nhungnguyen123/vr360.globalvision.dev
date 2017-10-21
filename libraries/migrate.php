<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Migrate
 *
 * @since  3.0.0
 */
class Vr360Migrate
{
	protected function getTours()
	{
		$db = Vr360Database::getInstance();

		return $db->select(
			'tours',
			[
				'tours.id',
				'tours.name',
				'tours.description',
				'tours.alias',
				'tours.created',
				'tours.created_by',
				'tours.dir',
				'tours.status',
				'tours.params'
			]
		);
	}

	public function migrate()
	{
		$tours = $this->getTours();
		$db    = Vr360Database::getInstance();

		foreach ($tours as $tour)
		{
			$this->deleteUnpublishedTour($tour);
			$this->deletePendingTour($tour);

			if ($tour['created_by'] == 1)
			{

				$db->delete("tours", [
					"AND" => [
						"id" => (int) $tour['id']
					]
				]);
			}
		}
	}

	public function deleteUnpublishedTour($tour)
	{
		if ($tour['status'] != 1)
		{
			$dataDir = VR360_PATH_DATA . '/' . $tour['dir'];

			//
			Vr360HelperFolder::create(VR360_PATH_ROOT . '/backup/tours');
			Vr360HelperFolder::move($dataDir, VR360_PATH_ROOT . '/backup/tours/' . $tour['dir']);
		}
	}

	public function deletePendingTour($tour)
	{
		if ($tour['status'] != 1)
		{
			$db = Vr360Database::getInstance();
			$db->delete("tours", [
				"AND" => [
					"id" => (int) $tour['id']
				]
			]);
		}
	}
}