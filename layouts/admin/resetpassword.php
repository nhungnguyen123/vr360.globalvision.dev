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
			<div class="container">
				<div id="passwordreset" style="margin-top:50px"
				     class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
					<div class="panel panel-info">
						<div class="panel-heading">
							<div class="panel-title">Create New Password</div>
						</div>
						<div class="panel-body">
							<form id="signupform" class="form-horizontal" role="form" method="post">
								<div class="form-group">
									<label for="email" class=" control-label col-sm-3">Registered email</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="email"
										       placeholder="Please input your email used to register with us">
									</div>
								</div>
								<div class="form-group">
									<label for="email" class=" control-label col-sm-3">New password</label>
									<div class="col-sm-9">
										<input type="password" class="form-control" name="password"
										       placeholder="create your new password">
									</div>
								</div>
								<div class="form-group">
									<label for="email" class=" control-label col-sm-3">Confirm password</label>
									<div class="col-sm-9">
										<input type="password" class="form-control" name="password_confirmation"
										       placeholder="confirm your new password">
									</div>
								</div>
								<div class="form-group">
									<!-- Button -->
									<div class="  col-sm-offset-3 col-sm-9">
										<button id="btn-signup" type="submit" class="btn btn-success">Submit</button>
										<fieldset>
											<input type="hidden" name="<?php echo Vr360Session::getInstance()->get('token'); ?>" value="1"/>
											<input type="hidden" name="task" value="resetPassword"/>
										</fieldset>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
</body>
</html>

