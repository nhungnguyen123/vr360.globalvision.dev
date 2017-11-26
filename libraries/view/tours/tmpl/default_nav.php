<?php
defined('_VR360_EXEC') or die;
?>

<div class="col-md-12">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<a class="navbar-brand" href="#">
					<i class="fa fa-user-circle-o"
					   aria-hidden="true"></i> <?php echo Vr360Factory::getUser()->name; ?>
				</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<div class="navbar-form navbar-left">

				</div>
				<form action="index.php" class="navbar-form navbar-right">
					<input type="hidden" name="view" value="user"/>
					<input type="hidden" name="task" value="logout"/>
					<button type="submit" class="btn btn-danger">Logout</button>
				</form>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
</div>