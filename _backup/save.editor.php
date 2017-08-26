<?php
require_once('db.class.php');
require_once('auth.class.php');
require_once('data.ver.class.php');
require_once('configure.php');
require_once('save.editor.class.php');

$auth = new authObj();
if (!$auth->isAuth())
{
	echo '{"error": "notAuth"}';
	die();
}

$_editor = new editorSave();
$_files  = new fileObj($_editor, false);
$_db     = new dbObj($_db);

$_files->xmlWrite();
rename($_editor->currentDir . 'vtour/tour.xml', $_editor->currentDir . 'vtour/tour.' . $_editor->editID . '.xml');
rename($_editor->currentDir . 't.xml', $_editor->currentDir . 'vtour/tour.xml');

$_db->update_vtour(array('tour_des' => $_editor->tourDes, 'u_id' => $_editor->uId));

exec('chmod -Rf 777 ./_');
?>
