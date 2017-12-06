<?php defined('_VR360_EXEC') or die; ?>
<?php $user = Vr360Factory::getUser(); ?>
<div class="col-md-12">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<a class="navbar-brand" href="#">
					<img
							src="<?php echo Vr360HelperUser::getGravatar($user->email); ?>"
							class="img-circle"
							style="display: inline-block;"
							id="avatar"
					/>
					<span class=""><?php echo $user->name; ?></span>
				</a>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li class="">
						<form action="index.php" class="navbar-form navbar-right">
							<!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
							<button type="button" class="btn btn-primary addNew" id="addTour">
								<i class="fa fa-plus-square" aria-hidden="true"></i>
								Add new
							</button>
						</form>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<form method="post" class="navbar-form navbar-left form-inline pull-right" name="search-form">
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-btn">
										<button class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
									</span>
									<input
											type="text"
											class="form-control"
											id="task-table-filter"
											data-action="filter"
											data-filters="#task-table"
											placeholder="Enter your tour name then press enter"
											name="keyword"
											value="<?php echo Vr360Factory::getInput()->get('keyword'); ?>"
									/>
								</div>
							</div>
							<input type="button" class="btn btn-danger" id="reset-search" value="Reset"/>

							<fieldset>
								<input type="hidden" name="view" value="tours"/>
								<input type="hidden" name="task" value="display"/>
							</fieldset>
						</form>
					</li>
					<li>
						<form action="index.php" class="navbar-form navbar-right">
							<input type="hidden" name="view" value="user"/>
							<input type="hidden" name="task" value="logout"/>
							<button type="submit" id="logout" class="btn btn-danger">Logout</button>
						</form>
					</li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
</div>