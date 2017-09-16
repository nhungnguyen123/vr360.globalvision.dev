<?php
defined('_VR360_EXEC') or die;
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" type="image/x-icon" href="./assets/images/globalvision.webp">
	<title><?php echo Vr360Configuration::getConfig('siteName'); ?></title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- jQuery -->
	<script type="text/javascript" src="./assets/jquery-2.2.4.min.js"></script>

	<!-- jQuery UI -->
	<script type="text/javascript" src="./assets/jquery-ui/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="./assets/jquery-ui/jquery-ui.min.css">
	<link rel="stylesheet" href="./assets/jquery-ui//jquery-ui.theme.min.css">

	<!-- Bootstrap -->
	<script type="text/javascript" src="./assets/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.css">

	<link rel="stylesheet" href="./assets/font-awesome/css/font-awesome.css">

	<!-- Globalvision -->
	<link rel="stylesheet" type="text/css" href="./assets/globalvision.css">
</head>
<body>
<div class="container-fluid">
	<div class="row" style="margin-top: 15px">
		<div class="col-md-12">
			<div class="header">
				<a href="http://globalvision.ch" target="_blank" class="logo">
					<img id="logo" src="./assets/images/gv_logo.png"/>
				</a>
			</div>
		</div>
		<div class="col-md-12">
			{content}
		</div>
		<div class="col-md-12">
			<hr />
			<div id="footer" class="footer">
				<p class="bg-warning">
					To use this platform it is recommended to use <br>Google Chrome internet browser version 59.0.0 ( or higher)
				</p>
			</div>
		</div>
	</div>
</div>
</body>
</html>
