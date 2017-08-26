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
$re = $db->get_row_data($auth->getUserId(), $_GET['UID']);

echo $re;
?>
