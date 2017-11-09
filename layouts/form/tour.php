<?php

defined('_VR360_EXEC') or die;

/**
 * @var  Vr360Tour $tour
 */
$params = $tour->params;

$rotation = ($params->get('rotation') == 1) ? 'checked' : '';
$socials  = ($params->get('socials') == 1) ? 'checked' : '';

// Read skin_setting default
$vTourskin    = new Vr360TourXml();
$tourSettings = $vTourskin->load('./krpano/viewer/skin/vtourskin.xml');

if ($tour->id)
{
	$tourNodes = $tour->getXml()->getNodes();

	// Merged
	$tourSettings = array_merge($tourNodes['skin_settings']['@attributes'], $tourSettings['skin_settings']['@attributes']);
}

?>

<div class="col-md-12">
	<div class="row">
		<div class="container-fluid">
			<!-- -->
			<?php require_once __DIR__ . '/tour_pano.php'; ?>
			<!-- Create new tour form -->
			<form method="post" id="form-tour" class="form-horizontal" enctype="multipart/form-data">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-4">
							<!-- Name -->
							<div class="form-group">
								<label for="name">Name of vTour</label>
								<input
										type="text"
										class="form-control"
										id="name"
										name="name"
										placeholder="Name of this tour"
										value="<?php echo $tour->get('name'); ?>"
										title="Please fill your tour name"
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
										disabled="disabled"
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

								<?php require_once __DIR__ . '/tour_skinsettings.php'; ?>

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
								<?php require_once __DIR__ . '/tour_panos.php'; ?>
							</div>
						</div>
					</div>
				</div>

				<fieldset>
					<input type="hidden" name="view" value="tour"/>
					<input type="hidden" name="task" value="ajaxEditTour"/>
					<input type="hidden" name="id" value="<?php echo $tour->id; ?>"/>
					<input type="hidden" name="task" value="ajaxCreateTour"/>
					<input type="hidden" name="step" value="uploadFile"/>
				</fieldset>
			</form>
		</div>
	</div>
</div>
