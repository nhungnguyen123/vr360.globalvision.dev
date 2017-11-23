<?php

defined('_VR360_EXEC') or die;

class Vr360Database extends \Medoo\Medoo
{
	/**
	 * Vr360Database constructor.
	 */
	public function __construct($options = null)
	{
		$dbServer   = Vr360Configuration::getConfig('dbServer', 'localhost');
		$dbName     = Vr360Configuration::getConfig('dbName');
		$dbUsername = Vr360Configuration::getConfig('dbUser');
		$dbPassword = Vr360Configuration::getConfig('dbPassword');

		parent::__construct(
			array
			(
				'database_type' => 'mysql',
				'server'        => $dbServer,
				'username'      => $dbUsername,
				'password'      => $dbPassword,
				'database_name' => $dbName,
				'charset'       => 'utf8'
			)
		);
	}

	/**
	 * @return static
	 */
	public static function getInstance()
	{
		static $instance;

		if (!isset($instance))
		{
			$instance = new static;
		}

		return $instance;
	}

	/**
	 * Load a record
	 *
	 * @param $table
	 * @param $condition
	 *
	 * @return array|bool|mixed
	 */
	public function load($table, $condition)
	{
		return $this->get(
			$table, '*', $condition
		);
	}

	/**
	 * Update a record
	 *
	 * @param $table
	 * @param $data
	 * @param $where
	 *
	 * @return bool|PDOStatement
	 */
	public function update($table, $data, $where = null)
	{
		$return = parent::update($table, $data, $where);

		return ($return !== false && isset($data['id'])) ? $data['id'] : $return;
	}
}
