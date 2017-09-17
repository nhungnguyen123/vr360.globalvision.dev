<?php defined('_VR360_EXEC') or die; ?>

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
					<!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
					<button type="button" class="btn btn-primary addNew"><i class="fa fa-plus-square"
					                                                        aria-hidden="true"></i>
						Add new</a></button>
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

<div class="col-md-12">
	<div class="container-fluid">
		<div class="row">
			<?php echo $this->fetchSub('tours'); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="vrModal" tabindex="-1" role="dialog" aria-labelledby="vrModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Embed code
				</h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="vrTour" tabindex="-1" role="dialog" aria-labelledby="vrTourLabel">
	<div class="modal-dialog" role="document" style="width: 90%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-square" aria-hidden="true"></i> New tour
				</h4>
			</div>
			<div class="modal-body">

				<div class="container-fluid">
					<?php Vr360Layout::getInstance()->load('form.tour'); ?>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="./assets/js/admin.js"></script>
<script type="text/javascript" src="./assets/js/log.min.js"></script>
