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

    // Read skin_setting default
    $setting = new Vr360TourXml();
    $default_setting = $setting->load('./krpano/viewer/skin/vtourskin.xml');

    // Merged
    $data_setting_tour = array_merge($setting_tour['skin_settings']['@attributes'], $default_setting['skin_settings']['@attributes']);
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
                                    <?php if (!empty($data_setting_tour)): ?>
                                        <div id="skin_settings" style="display: none">
                                            <?php foreach ($data_setting_tour as $key => $skin_settings): ?>
                                                <div class="controls">
                                                    <label for="tour_des"><?php echo $key ?></label>
                                                    <input type="text" class="form-control" id="<?php echo $key ?>" name="<?php echo $key ?>" placeholder="<?php echo $key ?>"
                                                        value="<?php echo $skin_settings; ?>"
                                                        title="Please fill your <?php echo $key ?>"
                                                        />
                                                    <p class="help-block"></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
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
                                            <div class="sortable ui-sortable well well-sm pano">
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
