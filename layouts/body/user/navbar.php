<?php defined('_VR360') or die; ?>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<a class="navbar-brand" href="#">
				<i class="fa fa-user-circle-o"
				   aria-hidden="true"></i> <?php echo Vr360Authorise::getInstance()->getUser()->name; ?>
			</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<form action="index.php" class="navbar-form navbar-right">
				<input type="hidden" name="task" value="logout"/>
				<button type="submit" class="btn btn-danger">Logout</button>
			</form>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
