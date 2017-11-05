<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$migrate = new Vr360Migrate;
$migrate->migrate();
