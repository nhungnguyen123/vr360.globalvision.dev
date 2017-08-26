<?php
    header("Content-Type:application/xml");
 ?>
<krpano version="1.18" title="Virtual Tour" onstart="startup();">

<include url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/vtourskin.xml" />
<include url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/tour-vtskin.xml" />
<include url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/social-skin.xml" />

<!-- set skin settings: bingmaps? gyro? thumbnail controlling? tooltips? -->
<skin_settings bingmaps="false"
               bingmaps_key=""
               bingmaps_zoombuttons="false"
               gyro="true"
               thumbs_width="120" thumbs_height="80" thumbs_padding="10" thumbs_crop="0|40|240|160"
               thumbs_opened="false"
               thumbs_text="false"
               thumbs_dragging="true"
               thumbs_onhoverscrolling="false"
               thumbs_scrollbuttons="false"
               thumbs_scrollindicator="false"
               tooltips_thumbs="false"
               tooltips_hotspots="false"
               tooltips_mapspots="false"
               controlbar_offset="20"
               />

<!-- set optional skin logo url -->
<!-- <layer name="skin_logo" url="" scale="0.25" opened_onclick="openurl(\'...\',_blank);" /> -->

    <action name="startup">
        showlog(false);
        if(startscene === null, copy(startscene,scene[0].name));
        loadscene(get(startscene), null, MERGE);
    </action>
    <scene name="scene_<?php echo $_GET['pano']; ?>" title="aaaa" thumburl="http://vr360.globalvision.ch/_/<?php echo $_GET['t']; ?>/vtour/panos/<?php echo $_GET['pano']; ?>.tiles/thumb.jpg" lat="" lng="" heading="">
        <view hlookat="-12.836" vlookat="55.190" fovtype="MFOV" fov="70.786" maxpixelzoom="1.2" fovmin="70" fovmax="140" limitview="auto" />
        <preview url="http://vr360.globalvision.ch/_/<?php echo $_GET['t']; ?>/vtour/panos/<?php echo $_GET['pano']; ?>.tiles/preview.jpg" />
        <image>
            <cube url="http://vr360.globalvision.ch/_/<?php echo $_GET['t']; ?>/vtour/panos/<?php echo $_GET['pano']; ?>.tiles/pano_%s.jpg" />
            <mobile>
                <cube url="http://vr360.globalvision.ch/_/<?php echo $_GET['t']; ?>/vtour/panos/<?php echo $_GET['pano']; ?>.tiles/mobile_%s.jpg" />
            </mobile>
        </image>
    </scene>
</krpano>
