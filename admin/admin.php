<?php
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 3600*24);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(3600*24);
@session_start ();

if (isset ( $_GET ['signOut'] )) {
	$_SESSION ['auth'] = false;
	$_SESSION ['userId'] = NULL;
	$_SESSION ['$userName'] = NULL;
	$_SESSION['isAdminLogin'] = 'false';
	session_unset ();
	session_destroy ();

	header ( 'Location: /admin/admin.php', true, '301' );
	die ();
}

include 'class.admin.php';

$admin = new adminClass();

if($admin->haveAdminLoginData())
	if($admin->login()) $admin->redirect('./admin/admin.php');
	else $admin->f_401()->echoLoginForm();

?>
<html>
<head>
	<meta charset="utf-8">
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="shortcut icon" type="image/png" href="http://images.globalvision.ch/icon.png">
	<link rel="stylesheet" type="text/css" href="admin.css">
	<script type="text/javascript" src="admin.js"></script>
</head>
<body>

<?php

if(!$admin->isLogin())
{
	$admin->echoLoginForm();
	die();
}
?>


<p id="welcome" style="margin: 10px; height: 50px;">
	<a href="http://globalvision.ch" target="_blank"><img id="gvMiniLogo"
		src="./../gv_logo.png" style="width: 200px; float: left;"></a> Welcome <b>Administrator</b>
	[<a href="./admin.php?signOut=1">SignOut</a>]
</p>


<div id="loading" style="display: none;" title="We are working, please wait...">
	<div id="fountainG">
		<div id="fountainG_1" class="fountainG"></div>
		<div id="fountainG_2" class="fountainG"></div>
		<div id="fountainG_3" class="fountainG"></div>
		<div id="fountainG_4" class="fountainG"></div>
		<div id="fountainG_5" class="fountainG"></div>
		<div id="fountainG_6" class="fountainG"></div>
		<div id="fountainG_7" class="fountainG"></div>
		<div id="fountainG_8" class="fountainG"></div>
	</div>
</div>

<div id="userPanoListDialog" title="Pano List">
	<table id="tbluserPanoListDialog"></table>
</div>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">User List</a></li>
    <li><a href="#tabs-2">Pano List</a></li>
    <li><a href="#tabs-3">New User</a></li>
  </ul>
 	 <div id="tabs-1">
		<div id="tabUserList">
			<table id="tblUserList">
				<tr class="ls">
					<td>
						{{userId}}
					</td>
					<td>
						<p onclick="admin.changeUserFullName('{{userId}}');" title="Change user Full Name" class="mouseControl">{{userFullName}}</p>
					</td>
					<td>
						<p onclick="admin.changeUserEmail('{{userId}}');" title="Change user Email" class="mouseControl">{{userEmail}}</p>
					</td>
					<td>
						<p onclick="admin.getAllPanioByUser('{{userId}}');" title="List of Pano by user" class="mouseControl">{{loginName}}</p>
					</td>
					<td>
						<button onclick="admin.changeUserPass('{{userId}}');">Change Password</button>
						<!--  <button onclick="admin.changeUserStatus('{{userId}}');">Deactive</button> -->
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="tabs-2">
		<div id="tabPanoList">
			<table id="tblPanoList">
				<tr class="ls {{stt_text_color}}">
					<td>
						{{id}}
					</td>
					<td>
						{{user_id_trans_to_name}}
					</td>
					<td>
						<img class="x16img" {{sst_image}}>
						{{stt_text}}
					</td>
					<td>
						<a href="http://vr360.globalvision.ch/preview.php?t={{u_id}}" target="_blank" class="">{{tour_des}}</a>
					</td>
					<td>
						{{date}}
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="tabs-3">
		
			<input class="nu" size="40" type="text" placeholder="Username" id="newUserName">
			<input class="nu" size="40" type="password" placeholder="Password" id="newUserPass">
			<br>
			<input class="nu" size="40" type="text" placeholder="Email"    id="userUserEmail">
			<input class="nu" size="40" type="text" placeholder="User Full Name" id="userUserFullName">
			<br>
			<!-- <input type="submit" value="New User"> -->
			<button onclick = "admin.addNewUser()">Add New User</button>
		
	</div>
</div>



<script type="text/javascript">
var admin = new adminObj();

admin.getAllTmp();

$(document).ready(function(){
	admin.getAllData();
	$('#tabs').tabs();
});

</script>
</body>
</html>
