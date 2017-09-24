<?php defined('_VR360_EXEC') or die; ?>

<form action="index.php" method="post">
	<div class="input-group">
		<span class="input-group-addon" id="basic-addon1"><i class="fa fa-user-circle" aria-hidden="true"></i></span>
		<input
				type="text"
				name="username"
				class="form-control"
				placeholder="Username"
				aria-describedby="basic-addon1"
		>
	</div>
	<br/>
	<div class="input-group">
		<span class="input-group-addon" id="basic-addon2"><i class="fa fa-key" aria-hidden="true"></i></span>
		<input
				type="password"
				name="password"
				class="form-control"
				placeholder="Password"
				aria-describedby="basic-addon2"
		>
	</div>

	<br/>
	<input class='btn btn-primary' type="submit" name="submit" value="Submit">

	<fieldset>
		<input type="hidden" name="view" value="user"/>
		<input type="hidden" name="task" value="login"/>
	</fieldset>
</form>
