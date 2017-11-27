<?php
defined('_VR360_EXEC') or die;

/**
 * Layout variables
 * ============================
 * @var   Vr360Tour $tour   Tour data
 * @var   array     $scenes Tour scenes
 */
$defaultScene = 0;

foreach ($scenes as $index => $scene)
{
	if (!$scene->default)
	{
		continue;
	}

	$defaultScene = $index;
}

$layoutHelper = Vr360Layout::getInstance();
$params       = json_decode($tour->params);
$skin         = isset($params->skin) ? $params->skin : 'default.xml';
$assetsPath = '../../../assets';
?>
<krpano version="1.19" title="Virtual Tour">
	<include url="<?php echo $assetsPath; ?>/vendor/krpano/viewer/skin/vtourskin.xml"/>
	<include url="<?php echo $assetsPath; ?>/vendor/krpano/viewer/skin/tour-vtskin.xml"/>
	<include url="<?php echo $assetsPath; ?>/vendor/krpano/viewer/skin/social-skin.xml"/>

	<?php if (null !== $params && property_exists($params, 'rotation') && $params->rotation == 1): ?>
		<autorotate enabled="true" waittime="2.0" speed="2.0" horizon="0.0"/>
	<?php endif; ?>
	<skin_settings maps="false"
				   maps_type="google"
				   maps_bing_api_key=""
				   maps_google_api_key=""
				   maps_zoombuttons="false"
				   gyro="true"
				   webvr="true"
				   webvr_gyro_keeplookingdirection="false"
				   webvr_prev_next_hotspots="true"
				   littleplanetintro="false"
				   title="true"
				   thumbs="true"
				   thumbs_width="120" thumbs_height="80" thumbs_padding="10" thumbs_crop="0|40|240|160"
				   thumbs_opened="false"
				   thumbs_text="false"
				   thumbs_dragging="true"
				   thumbs_onhoverscrolling="false"
				   thumbs_scrollbuttons="false"
				   thumbs_scrollindicator="false"
				   thumbs_loop="false"
				   tooltips_buttons="false"
				   tooltips_thumbs="false"
				   tooltips_hotspots="false"
				   tooltips_mapspots="false"
				   deeplinking="false"
				   loadscene_flags="MERGE"
				   loadscene_blend="OPENBLEND(0.5, 0.0, 0.75, 0.05, linear)"
				   loadscene_blend_prev="SLIDEBLEND(0.5, 180, 0.75, linear)"
				   loadscene_blend_next="SLIDEBLEND(0.5,   0, 0.75, linear)"
				   loadingtext="loading..."
				   layout_width="100%"
				   layout_maxwidth="814"
				   controlbar_width="-24"
				   controlbar_height="40"
				   controlbar_offset="20"
				   controlbar_offset_closed="-40"
				   controlbar_overlap.no-fractionalscaling="10"
				   controlbar_overlap.fractionalscaling="0"
				   design_skin_images="vtourskin.png"
				   design_bgcolor="0x2D3E50"
				   design_bgalpha="0.8"
				   design_bgborder="0"
				   design_bgroundedge="1"
				   design_bgshadow="0 4 10 0x000000 0.3"
				   design_thumbborder_bgborder="3 0xFFFFFF 1.0"
				   design_thumbborder_padding="2"
				   design_thumbborder_bgroundedge="0"
				   design_text_css="color:#FFFFFF; font-family:Arial;"
				   design_text_shadow="1"
	/>

	<!-- Use for override everything -->
	<include url="<?php echo $assetsPath; ?>/vendor/krpano/skins/<?php echo $skin; ?>"/>

	<action name="startup" autorun="onstart">
		if(startscene === null OR !scene[get(startscene)], copy(startscene,scene[<?php echo $defaultScene ?>].name); );
		loadscene(get(startscene), null, MERGE);
		if(startactions !== null, startactions() );
	</action>
	<?php if (null !== $params && property_exists($params, 'socials') && $params->socials == 1): ?>
		<layer name="social_share" type="container" keep="true" align="left" width="50" height="200" x="20" y="0"
			   bgcolor="0xffffff" bgalpha="0.0">
			<layer name="face" align="righttop" x="0" y="0" url="../../../assets/vendor/krpano/viewer/skin/images/f.jpg"
				   zorder="90"
				   onclick="js(shareFacebook(););"/>
			<layer name="twit" align="righttop" x="0" y="50"
				   url="../../../assets/vendor/krpano/viewer/skin/images/t.jpg" zorder="90"
				   onclick="js(shareTwitter(););"/>
			<layer name="goog" align="righttop" x="0" y="100"
				   url="../../../assets/vendor/krpano/viewer/skin/images/g.jpg" zorder="90"
				   onclick="js(shareGooglePlus(););"/>
			<layer name="embed" type="image" url="../../../assets/vendor/krpano/viewer/skin/images/embed.png"
				   keep="true"
				   align="lefttop"
				   width="50" height="50" crop="0|0|50|50" onovercrop="50|0|50|50" onclick="js(toggleEmbedcode(););"/>
		</layer>
	<?php endif; ?>
	<?php if (!empty($scenes)): ?>
		<?php foreach ($scenes as $scene): ?>
			<?php echo $layoutHelper->fetch('tour.scene', array('scene' => $scene)) ?>
		<?php endforeach; ?>
	<?php endif; ?>
</krpano>