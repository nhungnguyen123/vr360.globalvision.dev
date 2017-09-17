<?php

require 'vendor/autoload.php';

// Using Medoo namespace
use Medoo\Medoo;

// Initialize
$source = new Medoo(
	array(
		'database_type' => 'mysql',
		'database_name' => 'globalvision_vr360_old',
		'server'        => 'localhost',
		'username'      => 'root',
		'password'      => 'root'
	)
);

$dest = new Medoo(
	array(
		'database_type' => 'mysql',
		'database_name' => 'globalvision_vr360',
		'server'        => 'localhost',
		'username'      => 'root',
		'password'      => 'root'
	)
);

function migrateTours($source, $dest)
{
	$tours = $source->select(
		'tbl_vtour',
		'*'
	);

	foreach ($tours as $tour)
	{
		$dest->insert(
			'tours',
			array(
				'id'          => $tour['id'],
				'name'        => $tour['tour_des'],
				'description' => '',
				'alias'       => '',
				'created'     => date("Y-m-d H:i:s", strtotime($tour['date'])),
				'created_by'  => $tour['user_id'],
				'dir'         => $tour['u_id'],
				'status'      => $tour['status'],
				'params'      => '',
			)
		);
	}
}

function migrateAlias($source, $dest)
{
	$aliased = $source->select(
		'tbl_friendly_url',
		'*'
	);

	foreach ($aliased as $alias)
	{
		$dest->update(
			'tours',
			array('alias' => $alias['alias']),
			array('id' => $alias['vtour_id'])
		);
	}
}

function migrateUsers($source, $dest)
{
	$users = $source->select(
		'user',
		'*'
	);

	foreach ($users as $user)
	{
		$dest->insert(
			'users',
			array(
				'id'       => $user['userId'],
				'username' => $user['loginName'],
				'name'     => $user['userFullName'],
				'password' => $user['userPass'],
				'email'    => $user['userEmail'],
			)
		);
	}
}

function cleanTours($dest)
{
	$dest->delete("tours", array(
		'created_by' => 1
	));
}
//migrateAlias($source, $dest);
//migrateUsers($source, $dest);

cleanTours($dest);

