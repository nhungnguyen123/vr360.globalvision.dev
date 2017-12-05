<?php

defined('_VR360_EXEC') or die;

$url = VR360_URL_ROOT . '/' . $tour->alias;
?>
<iframe
		name="<?php echo $tour->name; ?>"
		width="800px"
		height="400px"
		src="<?php echo $url; ?>"
		frameborder="0"
		allowfullscreen="allowfullscreen"
		mozallowfullscreen="mozallowfullscreen"
		msallowfullscreen="msallowfullscreen"
		oallowfullscreen="oallowfullscreen"
		webkitallowfullscreen="webkitallowfullscreen"
>
</iframe>