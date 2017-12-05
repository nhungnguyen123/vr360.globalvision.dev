<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Database
 *
 * @since   2.0.0
 */
class Vr360Database extends \Medoo\Medoo
{
	/**
	 * Vr360Database constructor.
	 *
	 * @param   array  $options  Database options
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
	 * @param   string  $table      Table name
	 * @param   array   $condition  Condition
	 *
	 * @return  array|boolean|mixed
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
	 * @param   string  $table  Table name
	 * @param   array   $data   Data
	 * @param   array   $where  Condition
	 *
	 * @return boolean|PDOStatement
	 */
	public function update($table, $data, $where = null)
	{
		$return = parent::update($table, $data, $where);

		return ($return !== false && isset($data['id'])) ? $data['id'] : $return;
	}
}
