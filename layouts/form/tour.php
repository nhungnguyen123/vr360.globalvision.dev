<?php
defined('_VR360_EXEC') or die;

$tour   = isset($tour) ? $tour : new Vr360Tour;
$params = $tour->params;

$rotation = '';
if (isset($params->rotation) && $params->rotation == '1')
{
	$rotation = 'checked';
}

$socials = '';
if (isset($params->socials) && $params->socials == '1')
{
	$socials = 'checked';
}
$setting_tour = '';
if ($tour->getXml()) {
    $setting_tour = $tour->getXml()->getNodes();
}
?>

<div class="col-md-12">
    <div class="row">
        <div class="container-fluid">
            <!-- -->
            <div class="hidden">
                <div class="well well-sm pano">
                    <label>Panorama</label>
                    <div class="pull-right">
                        <button type="button" class="btn btn-danger removePano">
                            Remove this pano
                        </button>
                    </div>

                    <hr/>
                    <div class="container-fluid">
                        <div id="panoWrap">
                            <div class="form-group">
                                <label>File input</label>
                                <input type="file" name="panoFile[]" required/>
                                <p class="help-block hb-select-pano-file">Select pano file</p>
                            </div>

                            <div class="form-group">
                                <label>Title</label>
                                <input name="panoTitle[]" type="text" class="form-control"
                                       placeholder="Pano title" required/>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <input
                                        name="panoDescription[]"
                                        type="text"
                                        class="form-control"
                                        size="80"
                                        placeholder="Pano sub title"
                                        required
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Create new tour form -->
            <form method="post" id="createTour" class="form-horizontal" enctype="multipart/form-data">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Name -->
                            <div class="form-group">
                                <label for="tour_des">Name of vTour</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        id="name"
                                        name="name"
                                        placeholder="Name of this tour"
                                        value="<?php echo $tour->get('name'); ?>"
                                        title="Please fill your tour name"
                                        required
                                />
                                <p class="help-block"></p>
                            </div>
                            <!-- Alias -->
                            <div class="form-group">
                                <label for="tour_url">URL friendly</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        id="alias"
                                        name="alias"
                                        placeholder="URL friendly of this tour"
                                        value="<?php echo $tour->get('alias'); ?>"
                                        required
                                />
                                <p class="help-block"></p>
                            </div>
                            <!-- Options -->
                            <div class="form-group">
                                <span class="label label-primary"><i class="fa fa-cogs" aria-hidden="true"></i> Options</span>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="tour_rotation" name="params[rotation]" value="1"
                                               size="80" <?php echo $rotation; ?>/>Check
                                        for auto
                                        rotation.
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="tour_social" name="params[socials]" value="1"
                                               size="80" <?php echo $socials; ?>/>Check for show media social button.
                                    </label>
                                </div>
                                <?php if ($setting_tour !== ''): ?>
                                    <?php if ($tour->id): ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="tour_setting" name="skin_settings" size="80" onclick="showHideSkinSetting(this)" />Skin settings
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($setting_tour['skin_settings']) && !empty($setting_tour['skin_settings'])): ?>
                                        <?php foreach ($setting_tour['skin_settings'] as $skin_settings): ?>
                                            <div id="skin_settings" style="display: none">
                                                <div class="controls">
                                                    <label for="tour_des">Maps</label>
                                                    <input type="text" class="form-control" id="maps" name="maps" placeholder="Maps"
                                                        value="<?php echo $skin_settings['maps']; ?>"
                                                        title="Please fill your maps"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Maps type</label>
                                                    <input type="text" class="form-control" id="maps_type" name="maps_type" placeholder="Maps type"
                                                           value="<?php echo $skin_settings['maps_type']; ?>"
                                                           title="Please fill your maps type"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Maps bing api key</label>
                                                    <input type="text" class="form-control" id="maps_bing_api_key" name="maps_bing_api_key" placeholder="Maps bing api key"
                                                           value="<?php echo $skin_settings['maps_bing_api_key']; ?>"
                                                           title="Please fill your maps_bing_api_key"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Maps google api key</label>
                                                    <input type="text" class="form-control" id="maps_google_api_key" name="maps_google_api_key" placeholder="Maps google api key"
                                                           value="<?php echo $skin_settings['maps_google_api_key']; ?>"
                                                           title="Please fill your maps_google_api_key"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Maps zoombuttons</label>
                                                    <input type="text" class="form-control" id="maps_zoombuttons" name="maps_zoombuttons" placeholder="Maps zoombuttons"
                                                           value="<?php echo $skin_settings['maps_zoombuttons']; ?>"
                                                           title="Please fill your maps_zoombuttons"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Gyro</label>
                                                    <input type="text" class="form-control" id="gyro" name="gyro" placeholder="Gyro"
                                                           value="<?php echo $skin_settings['gyro']; ?>"
                                                           title="Please fill your gyro"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Webvr</label>
                                                    <input type="text" class="form-control" id="webvr" name="webvr" placeholder="Webvr"
                                                           value="<?php echo $skin_settings['webvr']; ?>"
                                                           title="Please fill your webvr"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Webvr gyro keeplookingdirection</label>
                                                    <input type="text" class="form-control" id="webvr_gyro_keeplookingdirection" name="webvr_gyro_keeplookingdirection" placeholder="Webvr gyro keeplookingdirection"
                                                           value="<?php echo $skin_settings['webvr_gyro_keeplookingdirection']; ?>"
                                                           title="Please fill your webvr_gyro_keeplookingdirection"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Webvr prev next hotspots</label>
                                                    <input type="text" class="form-control" id="webvr_prev_next_hotspots" name="webvr_prev_next_hotspots" placeholder="Webvr prev next hotspots"
                                                           value="<?php echo $skin_settings['webvr_prev_next_hotspots']; ?>"
                                                           title="Please fill your webvr_prev_next_hotspots"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Littleplanetintro</label>
                                                    <input type="text" class="form-control" id="littleplanetintro" name="littleplanetintro" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['littleplanetintro']; ?>"
                                                           title="Please fill your littleplanetintro"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Title</label>
                                                    <input type="text" class="form-control" id="title" name="title" placeholder="Name of this title"
                                                           value="<?php echo $skin_settings['title']; ?>"
                                                           title="Please fill your title"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs</label>
                                                    <input type="text" class="form-control" id="thumbs" name="thumbs" placeholder="Name of this thumbs"
                                                           value="<?php echo $skin_settings['thumbs']; ?>"
                                                           title="Please fill your thumbs"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs width</label>
                                                    <input type="text" class="form-control" id="thumbs_width" name="thumbs_width" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['thumbs_width']; ?>"
                                                           title="Please fill your thumbs_width"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs height</label>
                                                    <input type="text" class="form-control" id="thumbs_height" name="thumbs_height" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['thumbs_height']; ?>"
                                                           title="Please fill your thumbs_height"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs padding</label>
                                                    <input type="text" class="form-control" id="thumbs_padding" name="thumbs_padding" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['thumbs_padding']; ?>"
                                                           title="Please fill your thumbs_padding"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs crop</label>
                                                    <input type="text" class="form-control" id="thumbs_crop" name="thumbs_crop" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['thumbs_crop']; ?>"
                                                           title="Please fill your thumbs_crop"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs opened</label>
                                                    <input type="text" class="form-control" id="thumbs_opened" name="thumbs_opened" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['thumbs_opened']; ?>"
                                                           title="Please fill your thumbs_opened"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs text</label>
                                                    <input type="text" class="form-control" id="thumbs_text" name="thumbs_text" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['thumbs_text']; ?>"
                                                           title="Please fill your thumbs_text"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs dragging</label>
                                                    <input type="text" class="form-control" id="thumbs_dragging" name="thumbs_dragging" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['thumbs_dragging']; ?>"
                                                           title="Please fill your thumbs_dragging"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs onhoverscrolling</label>
                                                    <input type="text" class="form-control" id="thumbs_onhoverscrolling" name="thumbs_onhoverscrolling" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['thumbs_onhoverscrolling']; ?>"
                                                           title="Please fill your thumbs_onhoverscrolling"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs scrollbuttons</label>
                                                    <input type="text" class="form-control" id="thumbs_scrollbuttons" name="thumbs_scrollbuttons" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['thumbs_scrollbuttons']; ?>"
                                                           title="Please fill your thumbs_scrollbuttons"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs scrollindicator</label>
                                                    <input type="text" class="form-control" id="thumbs_scrollindicator" name="thumbs_scrollindicator" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['thumbs_scrollindicator']; ?>"
                                                           title="Please fill your thumbs_scrollindicator"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Thumbs loop</label>
                                                    <input type="text" class="form-control" id="thumbs_loop" name="thumbs_loop" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['thumbs_loop']; ?>"
                                                           title="Please fill your thumbs_loop"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Tooltips buttons</label>
                                                    <input type="text" class="form-control" id="tooltips_buttons" name="tooltips_buttons" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['tooltips_buttons']; ?>"
                                                           title="Please fill your tooltips_buttons"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Tooltips thumbs</label>
                                                    <input type="text" class="form-control" id="tooltips_thumbs" name="tooltips_thumbs" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['tooltips_thumbs']; ?>"
                                                           title="Please fill your tooltips_thumbs"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Tooltips hotspots</label>
                                                    <input type="text" class="form-control" id="tooltips_hotspots" name="tooltips_hotspots" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['tooltips_hotspots']; ?>"
                                                           title="Please fill your tooltips_hotspots"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Tooltips mapspots</label>
                                                    <input type="text" class="form-control" id="tooltips_mapspots" name="tooltips_mapspots" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['tooltips_mapspots']; ?>"
                                                           title="Please fill your tooltips_mapspots"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Deeplinking</label>
                                                    <input type="text" class="form-control" id="deeplinking" name="deeplinking" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['deeplinking']; ?>"
                                                           title="Please fill your deeplinking"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Loadscene flags</label>
                                                    <input type="text" class="form-control" id="loadscene_flags" name="loadscene_flags" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['loadscene_flags']; ?>"
                                                           title="Please fill your loadscene_flags"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Loadscene blend</label>
                                                    <input type="text" class="form-control" id="loadscene_blend" name="loadscene_blend" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['loadscene_blend']; ?>"
                                                           title="Please fill your loadscene_blend"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Loadscene blend prev</label>
                                                    <input type="text" class="form-control" id="loadscene_blend_prev" name="loadscene_blend_prev" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['loadscene_blend_prev']; ?>"
                                                           title="Please fill your loadscene_blend_prev"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Loadscene blend next</label>
                                                    <input type="text" class="form-control" id="loadscene_blend_next" name="loadscene_blend_next" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['loadscene_blend_next']; ?>"
                                                           title="Please fill your loadscene_blend_next"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Loadingtext</label>
                                                    <input type="text" class="form-control" id="loadingtext" name="loadingtext" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['loadingtext']; ?>"
                                                           title="Please fill your loadingtext"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Layout width</label>
                                                    <input type="text" class="form-control" id="layout_width" name="layout_width" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['layout_width']; ?>"
                                                           title="Please fill your layout_width"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Layout maxwidth</label>
                                                    <input type="text" class="form-control" id="layout_maxwidth" name="layout_maxwidth" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['layout_maxwidth']; ?>"
                                                           title="Please fill your layout_maxwidth"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Controlbar width</label>
                                                    <input type="text" class="form-control" id="controlbar_width" name="controlbar_width" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['controlbar_width']; ?>"
                                                           title="Please fill your controlbar_width"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Controlbar height</label>
                                                    <input type="text" class="form-control" id="controlbar_height" name="controlbar_height" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['controlbar_height']; ?>"
                                                           title="Please fill your controlbar_height"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Controlbar offset</label>
                                                    <input type="text" class="form-control" id="controlbar_offset" name="controlbar_offset" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['controlbar_offset']; ?>"
                                                           title="Please fill your controlbar_offset"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Controlbar offset closed</label>
                                                    <input type="text" class="form-control" id="controlbar_offset_closed" name="controlbar_offset_closed" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['controlbar_offset_closed']; ?>"
                                                           title="Please fill your controlbar_offset_closed"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Controlbar overlap no-fractionalscaling</label>
                                                    <input type="text" class="form-control" id="controlbar_overlap.no-fractionalscaling" name="controlbar_overlap.no-fractionalscaling" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['controlbar_overlap.no-fractionalscaling']; ?>"
                                                           title="Please fill your controlbar_overlap.no-fractionalscaling"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Controlbar overlap fractionalscaling</label>
                                                    <input type="text" class="form-control" id="controlbar_overlap.fractionalscaling" name="controlbar_overlap.fractionalscaling" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['controlbar_overlap.fractionalscaling']; ?>"
                                                           title="Please fill your controlbar_overlap.fractionalscaling"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Design skin images</label>
                                                    <input type="text" class="form-control" id="design_skin_images" name="design_skin_images" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['design_skin_images']; ?>"
                                                           title="Please fill your design_skin_images"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Design bgcolor</label>
                                                    <input type="text" class="form-control" id="design_bgcolor" name="design_bgcolor" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['design_bgcolor']; ?>"
                                                           title="Please fill your design_bgcolor"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Design bgalpha</label>
                                                    <input type="text" class="form-control" id="design_bgalpha" name="design_bgalpha" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['design_bgalpha']; ?>"
                                                           title="Please fill your design_bgalpha"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Design bgborder</label>
                                                    <input type="text" class="form-control" id="design_bgborder" name="design_bgborder" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['design_bgborder']; ?>"
                                                           title="Please fill your design_bgborder"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Design bgroundedge</label>
                                                    <input type="text" class="form-control" id="design_bgroundedge" name="design_bgroundedge" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['design_bgroundedge']; ?>"
                                                           title="Please fill your design_bgroundedge"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Design bgshadow</label>
                                                    <input type="text" class="form-control" id="design_bgshadow" name="design_bgshadow" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['design_bgshadow']; ?>"
                                                           title="Please fill your design_bgshadow"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Design thumbborder bgborder</label>
                                                    <input type="text" class="form-control" id="design_thumbborder_bgborder" name="design_thumbborder_bgborder" placeholder="Name of this tour"
                                                           value="<?php echo $skin_settings['design_thumbborder_bgborder']; ?>"
                                                           title="Please fill your design_thumbborder_bgborder"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Design thumbborder padding</label>
                                                    <input type="text" class="form-control" id="design_thumbborder_padding" name="design_thumbborder_padding" placeholder="Design thumbborder padding"
                                                           value="<?php echo $skin_settings['design_thumbborder_padding']; ?>"
                                                           title="Please fill your design_thumbborder_padding"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Design thumbborder bgroundedge</label>
                                                    <input type="text" class="form-control" id="design_thumbborder_bgroundedge" name="design_thumbborder_bgroundedge" placeholder="Design thumbborder bgroundedge"
                                                           value="<?php echo $skin_settings['design_thumbborder_bgroundedge']; ?>"
                                                           title="Please fill your design_thumbborder_bgroundedge"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Design text css</label>
                                                    <input type="text" class="form-control" id="design_text_css" name="design_text_css" placeholder="Design text css"
                                                           value="<?php echo $skin_settings['design_text_css']; ?>"
                                                           title="Please fill your design_text_css"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                                <div class="controls">
                                                    <label for="tour_des">Design text shadow</label>
                                                    <input type="text" class="form-control" id="design_text_shadow" name="design_text_shadow" placeholder="Design text shadow"
                                                           value="<?php echo $skin_settings['design_text_shadow']; ?>"
                                                           title="Please fill your design_text_shadow"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <hr/>
								<?php if ($tour->isValid()) : ?>
									<?php if (isset($tour->params->panos) && !empty($tour->params->panos)): ?>
										<?php $defaultPano = isset($tour->params->defaultPano) ? $tour->params->defaultPano : ''; ?>
                                        <div class="controls">
                                            <select class="form-control" name="params[defaultPano]">
												<?php foreach ($tour->params->panos as $index => $pano): ?>
													<?php if ($defaultPano == $pano->file): ?>
                                                        <option value="<?php echo $pano->file; ?>"
                                                                selected><?php echo $pano->title; ?></option>
													<?php else: ?>
                                                        <option value="<?php echo $pano->file; ?>"><?php echo $pano->title; ?></option>
													<?php endif; ?>
												<?php endforeach; ?>
                                            </select>
                                        </div>
									<?php endif; ?>
								<?php endif; ?>
                            </div>
                            <!-- Controls -->
                            <div class="form-group">
                                <button type="submit" id="createTour" class="btn btn-primary">
									<?php if ($tour->id): ?>
                                        <i class="fa fa-window-restore" aria-hidden="true"></i> Update
									<?php else: ?>
                                        <i class="fa fa-window-restore" aria-hidden="true"></i> Create new vTour
									<?php endif; ?>
                                </button>
                                <button type="button" id="addPano" class="btn btn-info addPano">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i> Add panoramas
                                </button>
                            </div>
                            <div class="form-group">
                                <div class="well well-sm">
                                    <span class="label label-default">PHP upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?></span>
                                    <span class="label label-default">PHP post_max_size: <?php echo ini_get('post_max_size'); ?></span>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-8">
                            <div id="tour-panos" class="panos">
								<?php if ($setting_tour !== ''): ?>
									<?php if (isset($setting_tour['scene'][0]) && !empty($setting_tour['scene'])): ?>
										<?php foreach ($setting_tour['scene'] as $pano): ?>
                                            <?php
                                                $ext_file = substr($pano['@attributes']['thumburl'], -4);
                                                $file = explode("_", $pano['@attributes']['name']);
                                            ?>
                                            <div class="well well-sm pano">
                                                <label>Panorama</label>
                                                <div class="pull-right">
                                                    <button type="button" class="btn btn-danger removePano">
                                                        Remove this pano
                                                    </button>
                                                </div>

                                                <hr/>
                                                <div class="container-fluid">
                                                    <div id="panoWrap">
                                                        <div class="form-group">
                                                            <label>File input</label>
                                                            <input type="text"
                                                                   value="<?php echo $file[1] . $ext_file; ?>"
                                                                   disabled="disabled"/>
                                                            <input type="hidden" name="panoFile[]"
                                                                   value="<?php echo $file[1] . $ext_file; ?>"/>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Title</label>
                                                            <input name="panoTitle[]" type="text" class="form-control"
                                                                   placeholder="Pano title"
                                                                   required
                                                                   value="<?php echo $pano['@attributes']['title']; ?>"/>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <input
                                                                    name="panoDescription[]"
                                                                    type="text"
                                                                    class="form-control"
                                                                    size="80"
                                                                    placeholder="Pano sub title"
                                                                    required
                                                                    value="<?php echo $pano['@attributes']['subtitle']; ?>"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
										<?php endforeach; ?>
                                    <?php else: ?>
                                        <?php
                                            // Get ext file
                                            $ext_file = substr($setting_tour['scene']['@attributes']['thumburl'], -4);
                                            $file = explode("_", $setting_tour['scene']['@attributes']['name']);
                                        ?>
                                        <div class="well well-sm pano">
                                            <label>Panorama</label>
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-danger removePano">
                                                    Remove this pano
                                                </button>
                                            </div>

                                            <hr/>
                                            <div class="container-fluid">
                                                <div id="panoWrap">
                                                    <div class="form-group">
                                                        <label>File input</label>
                                                        <input type="text"
                                                               value="<?php echo $file[1] . $ext_file; ?>"
                                                               disabled="disabled"/>
                                                        <input type="hidden" name="panoFile[]"
                                                               value="<?php echo $file[1] . $ext_file; ?>"/>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Title</label>
                                                        <input name="panoTitle[]" type="text" class="form-control"
                                                               placeholder="Pano title"
                                                               required
                                                               value="<?php echo $setting_tour['scene']['@attributes']['title']; ?>"/>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <input
                                                            name="panoDescription[]"
                                                            type="text"
                                                            class="form-control"
                                                            size="80"
                                                            placeholder="Pano sub title"
                                                            required
                                                            value="<?php echo $setting_tour['scene']['@attributes']['subtitle']; ?>"
                                                            />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									<?php endif; ?>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <fieldset>
                    <input type="hidden" name="view" value="tour"/>
					<?php if ($tour->id): ?>
                        <input type="hidden" name="task" value="ajaxEditTour"/>
                        <input type="hidden" name="id" value="<?php echo $tour->id; ?>"/>
					<?php else: ?>
                        <input type="hidden" name="task" value="ajaxCreateTour"/>
					<?php endif; ?>
                    <input type="hidden" name="step" value="uploadFile"/>
                </fieldset>
            </form>
        </div>
    </div>
</div>
