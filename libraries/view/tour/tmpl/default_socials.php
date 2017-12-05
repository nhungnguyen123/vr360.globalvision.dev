<?php

defined('_VR360_EXEC') or die;
?>

<?php if ($thumbnail !== false): ?>
	<meta itemprop="image" content="<?php echo $thumbnail['url']; ?>">
<?php endif; ?>

<!-- Twitter -->
<meta name="twitter:card" content="summary"/>
<meta name="twitter:site" content="@GlobalVision360"/>
<meta name="twitter:creator" content="@GlobalVision360"/>
<meta name="twitter:description" content="<?php echo Vr360Configuration::getConfig('siteDescription'); ?>">
<!-- Twitter summary card with large image must be at least 280x150px -->

<?php if ($thumbnail !== false): ?>
	<meta name="twitter:image:src" content="<?php echo $thumbnail['url']; ?>">
<?php endif; ?>

<!-- Opengraph -->
<meta property="og:url" content="<?php echo VR360_URL_FULL; ?>"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="VR360 Globalvision - <?php echo $this->tour->getName(); ?>"/>
<meta property="og:description" content="<?php echo $this->tour->getDescription(); ?>"/>
<?php if ($thumbnail !== false): ?>
	<meta property="og:image" content="<?php echo $thumbnail['url']; ?>"/>
	<meta property="og:image:alt" content="<?php echo $thumbnail['alt']; ?>"/>
	<!-- Extra og:image demension -->
	<?php if (isset($thumbnail['width']) && isset($thumbnail['width'])): ?>
		<meta property="og:image:width" content="<?php echo $thumbnail['width']; ?>"/>
		<meta property="og:image:height" content="<?php echo $thumbnail['height']; ?>"/>
		<meta property="og:image:type" content="<?php echo $thumbnail['mime']; ?>"/>
	<?php endif; ?>
<?php endif; ?>
