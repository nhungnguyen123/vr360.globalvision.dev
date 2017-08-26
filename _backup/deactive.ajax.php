<?php
require_once('db.class.php');
require_once('auth.class.php');
require_once('data.ver.class.php');
require_once('configure.php');
$auth = new authObj ();
if (!$auth->isAuth())
{
	echo "hello";
	die();
}
$db = new dbObj($_db);
$db->change_vtour_status($_GET['UIDx'], 2);
?>
