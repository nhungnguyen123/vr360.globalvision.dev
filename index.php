<?php

require_once __DIR__ .'/include/bootstrap.php';

$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : '';

// var_dump($_POST); die();

if (!empty($task))
{
	if (method_exists('Vr360Task', $task) && is_callable('Vr360Task', $task))
	{
		call_user_func(array('Vr360Task', $task));
	}
}

Vr360Layout::load('default');
