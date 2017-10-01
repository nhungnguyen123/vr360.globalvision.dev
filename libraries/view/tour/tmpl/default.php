<!DOCTYPE html>
<html>
<head>
	<?php
	$defaultThumbnail      = $this->tour->getDefaultThumbnail();
	$defaultThumbnailImage = VR360_URL_ROOT . $defaultThumbnail['file'];
	$defaultThumbnailAlt   = $defaultThumbnail['alt'];
	?>
	<title>VR360 Globalvision - <?php echo $this->tour->getName(); ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="./assets/images/globalvision.webp">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>

	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<!-- Charset -->
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta http-equiv="x-ua-compatible" content="IE=edge"/>

	<meta name="description" content="<?php echo $this->tour->getDescription(); ?>">
	<meta name="keywords" content="<?php echo Vr360Configuration::getConfig('siteKeyword'); ?>">

	<meta itemprop="image" content="<?php echo $defaultThumbnailImage; ?>">

	<!-- Globalvision -->
	<link rel="stylesheet" type="text/css" href="./assets/tour.min.css">

	<!-- Krpano -->
	<script src="<?php echo $this->tour->getKrpanoJsUrl(); ?>"></script>

	<script src="./assets/js/site.tour.min.js"></script>

	<!-- SEO Metadata -->
	<meta name="robots" content="index, follow"/>

	<!-- Twitter -->
	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="@GlobalVision360"/>
	<meta name="twitter:creator" content="@GlobalVision360"/>
	<meta name="twitter:description" content="<?php echo Vr360Configuration::getConfig('siteDescription'); ?>">
	<!-- Twitter summary card with large image must be at least 280x150px -->
	<meta name="twitter:image:src" content="<?php echo $defaultThumbnailImage; ?>">

	<!-- Opengraph -->
	<meta property="og:url" content="<?php echo VR360_URL_FULL; ?>"/>
	<meta property="og:type" content="website"/>
	<meta property="og:title" content="VR360 Globalvision - <?php echo $this->tour->getName(); ?>"/>
	<meta property="og:description" content="<?php echo $this->tour->getDescription(); ?>"/>
	<meta property="og:image" content="<?php echo $defaultThumbnailImage; ?>"/>
	<meta property="og:image:alt" content="<?php echo $defaultThumbnailAlt; ?>"/>
	<?php if (isset($defaultThumbnail['width']) && isset($defaultThumbnail['width'])): ?>
		<meta property="og:image:width" content="<?php echo $defaultThumbnail['width']; ?>"/>
		<meta property="og:image:height" content="<?php echo $defaultThumbnail['height']; ?>"/>
		<meta property="og:image:type" content="<?php echo $defaultThumbnail['mime']; ?>"/>
	<?php endif; ?>
</head>
<body>

	<?php if (!$this->tour->isValid()): ?>
		<span class="label label-danger">Invalid tour or data broken</span>
	<?php else: ?>
		<div id="pano" style="width:100%;height:100%;">
			<noscript>
				<table style="width:100%;height:100%;">
					<tr style="vertical-align:middle;">
						<td>
							<div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div>
						</td>
					</tr>
				</table>
			</noscript>
			<script>
				embedpano({
					swf: '<?php echo $this->tour->getKrpanoSwfUrl(); ?>',
					xml: "_/<?php echo $data->tour->dir; ?>/vtour/tour.xml",
					target: "pano",
					html5: "auto",
					mobilescale: 1.0,
					passQueryParameters: true
				});
			</script>
		</div>
	<?php endif; ?>
</body>
</html>
