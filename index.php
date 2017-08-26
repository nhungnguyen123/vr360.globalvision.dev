<?php

require_once __DIR__ .'/include/bootstrap.php';

$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : '';

if (!empty($task))
{
	if (method_exists('Vr360Task', $task) && is_callable('Vr360Task', $task))
	{
		call_user_func(array('Vr360Task', $task));
	}
}
?>
<html>
<head>
	<?php Vr360Layout::load('head'); ?>
</head>
<body>
<div class="container-fluid">
	<div class="row" style="margin-top: 15px">
		<div class="col-md-12">
			<?php if (!Vr360Authorise::isLogged()): ?>
				<?php Vr360Layout::load('body.guest.guest'); ?>
			<?php else: ?>
				<?php Vr360Layout::load('body.user.user'); ?>
			<?php endif; ?>
		</div>
		<div class="col-md-12">
			<?php Vr360Layout::load('body.footer'); ?>
		</div>
	</div>
</div>
</body>
</html>
