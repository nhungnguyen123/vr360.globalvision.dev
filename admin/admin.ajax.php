<?php
require_once './../configure.php';
require_once './admin.db.php';

include 'class.admin.php';

$admin = new adminClass();

if(!$admin->isLogin()) { $admin->f_401(); die('{"error" : "401"}'); }

$db = new adminDBObj($_db);

function sqlSpecRm($value)
{
	if(!preg_match('/\'\"\*\;/', $value)) return $value;
	else die('{"error" : "500"}');
}

switch ($_GET['mode']) {
	case 'AllUser':
	   echo $db->getAllUser();
	break;
	
	case 'AllPano':
		echo $db->getAllPano();
	break;
	
	case 'AllPanoByUser':
		echo $db->getPanoByUser(sqlSpecRm($_GET['data']));
	break;
	
	case 'ChangeUserPass':
		$data = json_decode(base64_decode($_GET['data']));
		echo $db->changePass($data->userId, sqlSpecRm($data->newPass));
	break;
	
	case 'ChangeUserEmail':
		$data = json_decode(base64_decode($_GET['data']));
		echo $db->ChangeUserEmail($data->userId, sqlSpecRm($data->newEmail));
	break;
	
	case 'ChangeUserFullName':
		$data = json_decode(base64_decode($_GET['data']));
		echo $db->ChangeUserFullName($data->userId, sqlSpecRm($data->newUserFullname));
	break;
	
	case 'addNewUser':
		$data = json_decode(base64_decode($_GET['data']));
		echo $db->addNewUser(sqlSpecRm($data->Name), sqlSpecRm($data->Pass), sqlSpecRm($data->Email), sqlSpecRm($data->FullName));
	break;
	
	default:
		echo "{}";
	break;
}