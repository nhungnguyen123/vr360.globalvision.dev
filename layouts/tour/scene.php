<?php
defined('_VR360_EXEC') or die;

/**
 * Layout variables
 * ============================
 * @var   Vr360Scene $scene Scene data
 */
$fileName = explode('.', $scene->file)[0];

if (null !== $scene->params)
{
	$fov     = $scene->params->fov;
	$hLookAt = $scene->params->hlookat;
	$vLookAt = $scene->params->vlookat;
}
else
{
	$fov     = VR360_TOUR_SCENE_DEFAULT_FOV;
	$hLookAt = VR360_TOUR_SCENE_DEFAULT_HLOOKAT;
	$vLookAt = VR360_TOUR_SCENE_DEFAULT_HLOOKAT;
}

$hotspots = $scene->getHotspot();
?>
<scene name="scene_<?php echo $fileName ?>" title="<?php echo $scene->name ?>"
	   subtitle="<?php echo $scene->description ?>" onstart=""
	   thumburl="panos/<?php echo $fileName ?>.tiles/thumb.jpg" lat="" lng="" heading="">
	<view hlookat="<?= $hLookAt ?>" vlookat="<?= $vLookAt ?>" fovtype="MFOV" fov="<?= $fov ?>" maxpixelzoom="2.0"
		  fovmin="70" fovmax="140"
		  limitview="auto"/>
	<preview url="panos/<?php echo $fileName ?>.tiles/preview.jpg"/>
	<image>
		<cube url="panos/<?php echo $fileName ?>.tiles/pano_%s.jpg"/>
	</image>
	<?php if (!empty($hotspots)): ?>
		<?php foreach ($hotspots as $index => $hotspot): ?>
			<?php
			$data = array();

			if (null !== $hotspot->params)
			{
				foreach ($hotspot->params as $key => $value)
				{
					$data[] = $key . '="' . $value . '"';
				}
			}
			?>
			<hotspot name="spot_<?php echo $hotspot->code; ?>"
					 dataId="<?php echo $hotspot->code ?>"
					 style="<?php echo $hotspot->style; ?>"
					 hotspot_type="<?php echo $hotspot->type; ?>"
					 ath="<?php echo $hotspot->ath ?>"
					 atv="<?php echo $hotspot->atv ?>" <?php echo implode(' ', $data) ?> />
		<?php endforeach; ?>
	<?php endif; ?>
</scene>