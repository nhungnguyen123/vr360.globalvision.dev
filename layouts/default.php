<?php defined('_VR360') or die; ?>

<!DOCTYPE html>
<html>
<head>
	<?php Vr360Layout::load('head.head'); ?>
</head>
<body>
<div class="container-fluid">
	<div class="row" style="margin-top: 15px">
		<div class="col-md-12">
			<?php if (!Vr360Authorise::isLogged()): ?>
				<?php Vr360Layout::load('body.guest.default'); ?>
			<?php else: ?>
				<?php Vr360Layout::load('body.user.default'); ?>
			<?php endif; ?>
		</div>
		<div class="col-md-12">
			<?php Vr360Layout::load('body.footer'); ?>
		</div>
	</div>
</div>
</body>
</html>
