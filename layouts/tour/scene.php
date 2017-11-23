<?php
defined('_VR360_EXEC') or die;

/**
 * Layout variables
 * ============================
 * @var   Vr360Scene $scene Scene data
 */
$fileName = explode('.', $scene->file)[0];
$fov      = VR360_TOUR_SCENE_DEFAULT_FOV;
$hLookAt  = VR360_TOUR_SCENE_DEFAULT_HLOOKAT;
$vLookAt  = VR360_TOUR_SCENE_DEFAULT_HLOOKAT;
?>
<scene name="scene_<?php echo $fileName ?>" title="<?php echo $scene->name ?>" subtitle="<?php echo $scene->description ?>" onstart=""
       thumburl="panos/<?php echo $fileName ?>.tiles/thumb.jpg" lat="" lng="" heading="">
    <view hlookat="<?= $hLookAt ?>" vlookat="<?= $vLookAt ?>" fovtype="MFOV" fov="<?= $fov ?>" maxpixelzoom="2.0" fovmin="70" fovmax="140"
          limitview="auto" />
    <preview url="panos/<?php echo $fileName ?>.tiles/preview.jpg" />
    <image>
        <cube url="panos/<?php echo $fileName ?>.tiles/pano_%s.jpg" />
    </image>
	<?php // @TODO: Add hotspot here ?>
</scene>