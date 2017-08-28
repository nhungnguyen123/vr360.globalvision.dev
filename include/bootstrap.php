<?php

require_once __DIR__ . '/loader.php';
require_once __DIR__ . '/defines.php';
require_once __DIR__ . '/configuration.php';
require_once VR360_PATH_ROOT . '/vendor/autoload.php';

Vr360Loader::autoload();

$session = Vr360Session::getInstance();

// If user already logged than update last_visit time
if (Vr360Authorise::isLogged())
{
	$user = Vr360Authorise::getInstance()->getUser();
	$user->last_visit = date('Y-m-d H:i:s');
	$user->save();
}