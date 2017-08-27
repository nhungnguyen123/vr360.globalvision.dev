<div class="col-md-12">
	<div class="col-md-6">
		<?php Vr360Layout::load('body.logo');?>
	</div>
	<div class="col-md-6">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<a class="navbar-brand" href="#">
						<i class="fa fa-user-circle-o" aria-hidden="true"></i> <?php echo Vr360Authorise::getInstance()->getUser()->name; ?>
					</a>
				</div>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
	</div>
</div>

<div class="col-md-12">
	<?php Vr360Layout::load('body.user.tabs');?>
</div>

<div class="modals">
	<?php //Vr360Layout::load('body.user.modals');?>
</div>

<script type="text/javascript" src='./assets/globalvision.js'></script>
<script type="text/javascript" src="./assets/js/admin.js"></script>
<script type="text/javascript" src="./assets/js/admin/tours.js"></script>
<script type="text/javascript" src="./assets/js/log.js"></script>
<script type="text/javascript" src="./assets/js/progress.js"></script>