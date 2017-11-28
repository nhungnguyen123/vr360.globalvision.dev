<?php

defined('_VR360_EXEC') or die;
$uploadMaxFilesize     = floatval(ini_get('upload_max_filesize'));
$postMaxsize           = floatval(ini_get('post_max_size'));
$allowedNumberOfScenes = round($postMaxsize / $uploadMaxFilesize);
?>
<div class="well well-sm">
	<span class="label label-default">PHP upload_max_filesize: <?php echo $uploadMaxFilesize; ?></span>
	<span class="label label-default">PHP post_max_size: <?php echo $postMaxsize; ?></span>
	<span class="label label-info">Number of scenes allowed: <?php echo $allowedNumberOfScenes; ?></span>
</div>