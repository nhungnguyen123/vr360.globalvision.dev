<?php

require 'vendor/autoload.php';

// Using Medoo namespace
use Medoo\Medoo;

// Initialize
$db = new Medoo(
	array(
		'database_type' => 'mysql',
		'database_name' => 'globalvision_vr360',
		'server'        => 'localhost',
		'username'      => 'root',
		'password'      => 'root'
	)
);

$aliasList = $db->select(
	'tbl_friendly_url',
	array('id', 'alias', 'vtour_id')
);

// Migrate alias
foreach ($aliasList as $alias)
{
	$tour = $db->select(
		'tours',
		array('id', 'name', 'description', 'alias', 'created', '_created', 'created_by', 'dir', 'status'),
		array('id' => $alias['vtour_id'])
	);

	if (empty($tour))
	{
		// Delete this alias
		/*var_dump(
			$db->delete(
				'tbl_friendly_url',
				array('id' => $alias['id'])
			)
		);*/

		continue;
	}

	$tour = array_shift($tour);
	$db->update(
		'tbl_vtour',
		array('alias' => $alias['alias']),
		array('id' => $tour['id'])
	);
}

/*// Copy tours from another table to this one
$vTourFull = $db->select(
	'tbl_vtour_full',
	array('id', 'user_id', 'u_id', 'date', 'status', 'alias')
);

foreach ($vTourFull as $tour)
{
	$db->insert
	(
		'tours',
		array
		(
			'name'       => $tour['alias'],
			'alias'      => $tour['alias'],
			'created'    => date("Y-m-d H:i:s", strtotime($tour['date'])),
			'created_by' => $tour['user_id'],
			'dir'        => $tour['u_id'],
			'status'     => $tour['status']
		)
	);
}*/

/*$vTourMeg = $db->select(
	'tour_meg',
	array('id', 'user_id', 'tour_des', 'alias', 'u_id', 'date', 'status')
);

foreach ($vTourMeg as $tour)
{
	$db->insert
	(
		'tours',
		array
		(
			'name'       => $tour['tour_des'],
			'alias'      => $tour['alias'],
			'created'    => date("Y-m-d H:i:s", strtotime($tour['date'])),
			'created_by' => $tour['user_id'],
			'dir'        => $tour['u_id'],
			'status'     => $tour['status']
		)
	);
}*/

// Rename created field to _created and create new created field as DATETIME
$tours = $db->select(
	'tours',
	array('id', 'name', 'description', 'alias', 'created', '_created', 'created_by', 'dir', 'status')
);

foreach ($tours as $tour)
{
	// Validate target dir exists

	// Convert created
	$timestamp       = strtotime($tour['_created']);
	$tour['created'] = date("Y-m-d H:i:s", $timestamp);

	if (empty($tour['alias']))
	{
		// Replace double byte whitespaces by single byte (East Asian languages)
		$str = preg_replace('/\xE3\x80\x80/', ' ', $tour['name']);

		// Remove any '-' from the string as they will be used as concatenator.
		// Would be great to let the spaces in but only Firefox is friendly with this

		$str = str_replace('-', ' ', $str);

		// Replace forbidden characters by whitespaces
		$str = preg_replace('#[:\#\*"@+=;!><&\.%()\]\/\'\\\\|\[]#', "\x20", $str);

		// Delete all '?'
		$str = str_replace('?', '', $str);

		// Trim white spaces at beginning and end of alias and make lowercase
		$str = trim(strtolower($str));

		// Remove any duplicate whitespace and replace whitespaces by hyphens
		$str = preg_replace('#\x20+#', '-', $str);

		$tour['alias'] = $str;
	}

	$db->update(
		'tours',
		$tour,
		array('id' => $tour['id'])
	);

	// @TODO Make sure this alias is unique
}

/**
 * @TODO Final step
 *
 * DROP tbl_friendly_url
 * DROP all rest tables which not using
 * Rename tbl_vtour to vtours
 */
