<?php
// Using Medoo namespace
use Medoo\Medoo;

/**
 * Class Vr360Database
 *
 * Model class. Used to work with database
 */
class Vr360Database
{
	protected $medoo;

	/**
	 * Vr360Database constructor.
	 */
	public function __construct()
	{
		$config   = Vr360Configuration::getInstance();
		$this->db = new PDO('mysql:host=' . $config->dbServer
			. ';dbname=' . $config->dbName . ';charset=utf8', $config->dbUser, $config->dbPassword);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		$this->medoo = new Medoo([
			'database_type' => 'mysql',
			'database_name' => $config->dbName,
			'server'        => $config->dbServer,
			'username'      => $config->dbUser,
			'password'      => $config->dbPassword,
			'charset'       => 'utf8'
		]);
	}

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

	/**
	 * @param $userName
	 *
	 * @return  array
	 */
	public function getUserData($userName)
	{
		$result = $this->medoo->select(
			'users',
			'*',
			[
				'OR' =>
					[
						'username' => $userName,
						'email'    => $userName
					]
			]);

		if ($result === false)
		{
			return false;
		}

		return array_shift($result);
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
		return $this->medoo->get(
			$table, '*', $condition
		);
	}

	/**
	 * Create new record
	 *
	 * @param $table
	 * @param $data
	 *
	 * @return bool|int|mixed|string
	 */
	public function create($table, $data)
	{
		if ($this->medoo->insert($table, $data) !== false)
		{
			return $this->medoo->id();
		}

		return false;
	}

	/**
	 * Update a record
	 *
	 * @param $table
	 * @param $data
	 *
	 * @return bool|PDOStatement
	 */
	public function update($table, $data)
	{

		return $this->medoo->update(
			$table,
			$data,
			[
				'id' => $data['id']
			]
		);
	}
}
