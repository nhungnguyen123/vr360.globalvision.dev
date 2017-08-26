<?php
session_start();
$_SESSION['auth']      = false;
$_SESSION['userId']    = null;
$_SESSION['$userName'] = null;
session_unset();
session_destroy();

header('Location: /_index.php', true, '301');
die();
?>
