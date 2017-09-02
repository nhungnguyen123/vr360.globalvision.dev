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

	// public function insert_vtour($data)
	// {
	//     $sql = "INSERT INTO `tbl_vtour` (`id`, `user_id`, `tour_des`, `u_id`, `date`, `status`) VALUES (NULL, ".$data['userId'].", '".$data['tour_des']."', '".$data['UID']."', '".gmdate("M d Y H:i:s")."', '0')";
	//     $re =  $this->db->query($sql);
	//     echo $re->errorInfo();
	// }

	public function update_vtour($data)
	{
		//$sql = "UPDATE `tbl_vtour` SET `tour_des` = '".$data['tour_des']."' WHERE `tbl_vtour`.`u_id` = '".$data['u_id']."';";
		$sql  = "UPDATE `tbl_vtour` SET `tour_des` = :tour_des WHERE `tbl_vtour`.`u_id` = :u_id;";
		$stmt = $this->db->prepare($sql);
		$re   = $stmt->execute([":tour_des" => $data['tour_des'], ":u_id" => $data['u_id']]);
		//echo $re->errorInfo();
	}

	/**
	 * @param     $UID
	 * @param int $status
	 *
	 * @return bool|PDOStatement
	 */
	public function change_vtour_status($UID, $status = 1)
	{
		return $this->medoo->update(
			'tbl_vtour',
			[

				'status' => (int) $status
			],
			[
				'u_id'    => $UID,
				'user_id' => (int) Vr360Authorise::getInstance()->getUserId()
			]

		);
	}

	/**
	 * @param null $userId
	 *
	 * @return array|bool
	 */
	public function getTours($userId = null)
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
				$data[] = new Vr360Tour($row);
			}

			return $data;
		}

		return $rows;
	}

	public function getToursPagination($userId = null)
	{
		if ($userId === null)
		{
			$userId = Vr360Authorise::getInstance()->getUser()->id;
		}

		$limit = 20;

		$rows = $this->medoo->select(
			'tbl_vtour',
			[
				'[><]tbl_friendly_url' => ['id' => 'vtour_id']
			],
			[
				'tbl_vtour.id',
				'tbl_vtour.name',
				'tbl_vtour.dir',
				'tbl_vtour.created',
				'tbl_vtour.created_by',
				'tbl_vtour.status',
				'tbl_friendly_url.alias'
			],
			[
				'tbl_vtour.created_by' => (int) $userId,
				'tbl_vtour.status[!]'  => VR360_TOUR_STATUS_UNPUBLISHED,
				'ORDER'                => [
					'tbl_vtour.id' => 'DESC'
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

	public function get_row_data($userId, $uid)
	{
		// $sql = "SELECT * FROM `tbl_vtour` WHERE `user_id` = '$userId' AND `u_id` = '$uid'";
		$sql          = "SELECT * FROM `tbl_vtour` WHERE `user_id` = :userId AND `u_id` = :uid;";
		$stmt         = $this->db->prepare($sql);
		$resultHander = $stmt->execute([":userId" => $userId, ":uid" => $uid]);

		$data = '{';
		while ($row = $resultData = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			//var_dump($resultData);
			$data .= '"' . $row['u_id'] . '":' . json_encode($row) . ',';
		}
		//rm last ','
		$data = substr($data, 0, strlen($data) - 1);

		$data .= '}';

		return $data;
	}

	public function get_alias($tourID)
	{
		// $sql = "SELECT  `id` ,`alias`, `vtour_id` FROM `tbl_friendly_url` WHERE `vtour_id` = '$tourID' ";
		$sql          = "SELECT  `id` ,`alias`, `vtour_id` FROM `tbl_friendly_url` WHERE `vtour_id` = :tourID;";
		$stmt         = $this->db->prepare($sql);
		$resultHander = $stmt->execute([":tourID" => $tourID]);
		$row          = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row;
	}
	//  public function getAllData()
	// {
	//     $sql = "SELECT * FROM `tbl_vtour`";
	//     $resultHander =  $this->db->query($sql);
	//     $row = $resultHander->fetchAll(PDO::FETCH_ASSOC);
	//     return $row;
	// }
	public function update_url($data)
	{
		// $sql = "UPDATE `tbl_friendly_url` SET  `alias` = '".$data['alias']."'  WHERE vtour_id = ". $data['vtour_id'];
		$sql  = "UPDATE `tbl_friendly_url` SET  `alias` = :alias  WHERE vtour_id = :vtour_id";
		$stmt = $this->db->prepare($sql);
		$re   = $stmt->execute([":alias" => $data['alias'], ":vtour_id" => $data['vtour_id']]);
	}

	public function check_url($data)
	{
		// $sql = "SELECT  fu.`id` ,`alias`, `status` FROM `tbl_vtour` as vt INNER JOIN `tbl_friendly_url` as fu ON vt.`id` = fu.`vtour_id` WHERE `alias` like '".$data['alias']."' AND status != 2 AND fu.vtour_id != ".$data['vtour_id'] ;
		$sql  = "SELECT  fu.`id` ,`alias`, `status` FROM `tbl_vtour` as vt INNER JOIN `tbl_friendly_url` as fu ON vt.`id` = fu.`vtour_id` WHERE `alias` like :alias AND status != 2 AND fu.`vtour_id` != :vtour_id";
		$stmt = $this->db->prepare($sql);
		$re   = $stmt->execute([":alias" => $data['alias'], ":vtour_id" => $data['vtour_id']]);
		$row  = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row;
	}

	public function get_u_id($alias)
	{
		// $sql = "SELECT vt.`id`, `user_id`, `u_id`, `alias`, `vtour_id` FROM `tbl_vtour` as vt INNER JOIN `tbl_friendly_url` as fu ON vt.`id` = fu.`vtour_id` WHERE fu.`alias` = '$alias'  ORDER BY vt.`id` DESC";
		$sql  = "SELECT vt.`id`, `user_id`, `u_id`, `alias`, `vtour_id` FROM `tbl_vtour` as vt INNER JOIN `tbl_friendly_url` as fu ON vt.`id` = fu.`vtour_id` WHERE fu.`alias` = :alias  ORDER BY vt.`id` DESC";
		$stmt = $this->db->prepare($sql);
		$re   = $stmt->execute([":alias" => $alias]);
		$row  = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row;
	}

	public function create($table, $data)
	{
		return $this->medoo->insert(
			$table,
			$data
		);
	}

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

	public function updateTour($data)
	{

		return $this->medoo->update(
			'tbl_vtour',
			$data,
			[
				'id' => $data['id']
			]
		);
	}
}

?>
