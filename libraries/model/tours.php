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
	 * @return array|boolean
	 */
	public function getList()
	{

		$input  = Vr360Factory::getInput();
		$offset = $input->getInt('page', 0) * 20;
		$limit  = 20;

		$db    = Vr360Factory::getDbo();
		$query = $this->buildQuery();

		$rows = $db->setQuery($query, $offset, $limit)->loadObjectList();

		if (!$rows || empty($rows))
		{
			return false;
		}

		$tours = array();

		// Assign to tour object
		foreach ($rows as $row)
		{
			$row->params = !empty($row->params) ? new Vr360Object(json_decode($row->params)) : new Vr360Object();
			$tour        = new Vr360Tour;
			$tour->bind($row);
			$tours[] = $tour;
		}

		return $tours;
	}
	public function SaveOption($columns,$values)
	{

		// Get a db connection.
		$db = Vr360Factory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		$tmp = '';
		foreach ($values as $i => $v) {
			$tmp .= ($i ? ',' : '') . "'$v'";
		}
		// Prepare the insert query.
		$query
		    ->insert($db->quoteName('hotspots_option'))
		    ->columns($db->quoteName($columns))
		    ->values($tmp);

		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		$db->execute();
	}

	public function SaveHotspot($columns, $values)
	{
		// Get a db connection.
		$db = Vr360Factory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		$tmp = '';
		foreach ($values as $i => $v) {
			$tmp .= ($i ? ',' : '') . "'$v'";
		}
		// Prepare the insert query.
		$query
		    ->insert($db->quoteName('hotspots'))
		    ->columns($db->quoteName($columns))
		    ->values($tmp);

		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		$db->execute();
		return	$db->insertid();
	}

	public function GetOption($id){
		// Get a db connection.
		$db = Vr360Factory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		// $tmp = '';
		// foreach ($values as $i => $v) {
		// 	$tmp .= ($i ? ',' : '') . "'$v'";
		// }
		// Prepare the insert query.

		$query->select("*");
		$query->from($db->quoteName('hotspots_option'));
		$query->where($db->quoteName('hotspot_id') . ' = '. $db->quote($id));

		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		$db->execute();
		return	$db->loadObjectList();
	}

	public function Uptadeoption($id,$c,$r){
		$db = Vr360Factory::getDbo();
		$query = $db->getQuery(true);
			echo $c;
		$fields = array(
		    		$db->quoteName($c). "=".$db->quote($r)
				);
		$conditions = array(
		    $db->quoteName('hotspot_id') . '='.$id
		);

		$query->update($db->quoteName('hotspots_option'))->set($fields)->where($conditions);
		$db->setQuery($query);

		$result = $db->execute();
	}

	/**
	 * @return array|boolean
	 */
	public function getPagination()
	{
		$limit = 20;

		$db    = Vr360Factory::getDbo();
		$query = $this->buildQuery();

		$rows = $db->setQuery($query)->loadObjectList();

		$input = Vr360Factory::getInput();

		if (!empty($rows))
		{
			return array(
				'current' => $input->getInt('page', 0),
				'total'   => round(count($rows) / $limit) - 1
			);
		}

		return false;
	}

	/**
	 * @return \Joomla\Database\DatabaseQuery
	 */
	protected function buildQuery()
	{
		$db    = Vr360Factory::getDbo();
		$input = Vr360Factory::getInput();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('tours'))
			->where($db->quoteName('status') . ' = ' . VR360_TOUR_STATUS_PUBLISHED_READY)
			->where($db->quoteName('created_by') . ' = ' . Vr360Factory::getUser()->id)
			->order($db->quoteName('id') . ' DESC');

		// Filter by keyword
		$keyword = $input->getString('keyword');

		if ($keyword)
		{
			$query->where(
				$db->quoteName('name') . ' LIKE ' . $db->quote('%' . $keyword . '%')
				. ' OR ' . $db->quoteName('alias') . ' LIKE ' . $db->quote('%' . $keyword . '%')
				. ' OR ' . $db->quoteName('description') . ' LIKE ' . $db->quote('%' . $keyword . '%')
				. ' OR ' . $db->quoteName('keyword') . ' LIKE ' . $db->quote('%' . $keyword . '%')
			);
		}

		return $query;
	}
}
