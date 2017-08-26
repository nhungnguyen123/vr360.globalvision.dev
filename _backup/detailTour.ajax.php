<?php
require_once('db.class.php');
require_once('auth.class.php');
require_once('data.ver.class.php');
require_once('configure.php');

$auth = new authObj();
if (!$auth->isAuth())
{
	echo '{"error": "notAuth"}';
	die();
}

$db = new dbObj($_db);
$re = $db->get_tour_data($auth->getUserId());
foreach (json_decode($re) as $key => $value)
{
	if (isset($_GET['id']) && $_GET['id'] == $value->id)
	{
		$alias = $db->get_alias($_GET['id']);
	}
}

echo json_encode($alias);
?>
