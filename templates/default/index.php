<?php
defined('_VR360_EXEC') or die;
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" type="image/x-icon" href="./assets/images/globalvision.webp">
	<title><?php echo Vr360Configuration::getConfig('siteName'); ?></title>
	<meta name="description" content="<?php echo Vr360Configuration::getConfig('siteDescription'); ?>"/>

	<meta property="og:title" content="<?php echo Vr360Configuration::getConfig('siteName'); ?>"/>
	<meta property="og:description" content="<?php echo Vr360Configuration::getConfig('siteDescription'); ?>"/>
	<meta property="og:type" content="website"/>

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
	<link rel="stylesheet" type="text/css" href="./assets/globalvision.min.css">
	<?php if (Vr360HelperAuthorize::isAuthorized()): ?>
		<script type="text/javascript" src="./assets/js/admin.min.js"></script>
		<script type="text/javascript" src="./assets/js/admin.validate.min.js"></script>
		<script type="text/javascript" src="./assets/js/admin.tours.min.js"></script>
		<script type="text/javascript" src="./assets/js/admin.tour.min.js"></script>
		<script type="text/javascript" src="./assets/js/log.min.js"></script>
	<?php endif; ?>
</head>
<body>
<div class="container-fluid">
	<div class="row" style="margin-top: 15px">
		<div id="overlay-waiting" class="waiting"></div>
		<div class="col-md-12">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<div class="header">
							<a href="http://globalvision.ch" target="_blank" class="logo">
								<img id="logo" src="./assets/images/gv_logo.png"/>
							</a>
						</div>
					</div>
					<div class="col-md-6">
						<div class="text-center center-block">
							<a href="//www.facebook.com/globalvision360/" target="_blank">
								<i id="social-fb" class="fa fa-facebook-square fa-3x social"></i>
							</a>
							<a href="//twitter.com/GlobalVision360" target="_blank">
								<i id="social-tw" class="fa fa-twitter-square fa-3x social"></i>
							</a>
							<a href="//plus.google.com/+GlobalVisionSwitzerland" target="_blank">
								<i id="social-gp" class="fa fa-google-plus-square fa-3x social"></i></a>
							<a href="mailto:info@globalvision.ch">
								<i id="social-em" class="fa fa-envelope-square fa-3x social"></i>
							</a>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="col-md-12">
			<div class="container-fluid">
				<div class="messages">
					<?php $messages = Vr360Session::getInstance()->getMessages(); ?>
					<?php if (!empty($messages)): ?>
						<?php foreach ($messages as $key=> $type): ?>
							<?php foreach ($type as $message): ?>
								<div class="label label-<?php echo $key; ?>"><?php echo $message; ?></div>
							<?php endforeach; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
			<hr />
		</div>
		<div class="col-md-12">
			{content}
		</div>
	</div>
</div>
</body>
</html>
