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

$db               = new dbObj($_db);
$vtour_id         = $_POST['id'];
$tour_des         = $_POST['friendlyUrl'];
$tour_result      = $db->check_url(array('alias' => $tour_des, "vtour_id" => $vtour_id));
$result['status'] = 0;
if (isset($tour_result) && $tour_result != "")
{
	$result['status'] = 2;
}
else
{
	$db->update_url(array('alias' => $tour_des, "vtour_id" => $vtour_id));
	$result['status'] = 1;
}
echo json_encode($result);
?>
