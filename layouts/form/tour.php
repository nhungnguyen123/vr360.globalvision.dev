<?php

defined('_VR360_EXEC') or die;

/** @var Vr360Tour $tour */

// Skins
$skins = Vr360HelperKrpano::getListOfSkins();

// Scenes
$scenes = $tour->getScenes();

$isRotate = null !== $tour->params && property_exists($tour->params, 'rotation') ? (boolean) $tour->params->rotation : false;
$isSocial = null !== $tour->params && property_exists($tour->params, 'socials') ? (boolean) $tour->params->socials : false;
?>
<div class="col-md-12">
	<div class="row">
		<div class="container-fluid">
			<!-- Hidden scene form -->
			<?php require_once __DIR__ . '/tour_scene.php'; ?>
			<!-- Create new tour form -->
			<form method="post" id="form-tour" class="form-horizontal" enctype="multipart/form-data">
				<div class="col-md-12">
					<div class="row">
						<?php require_once __DIR__ . '/tour_information.php'; ?>
						<div class="col-md-4 form-horizontal">
							<!-- Name -->
							<div class="form-group">
								<label for="name" class="col-sm-2 control-label">Name *</label>
								<div class="col-sm-10">
									<input
											type="text"
											class="form-control input-sm"
											id="name"
											name="name"
											placeholder="Name of this tour"
											value="<?php echo $tour->get('name'); ?>"
											title="Name of tour. Will use as site title"
											data-validation="required"
									/>
								</div>
							</div>
							<!-- Alias -->
							<div class="form-group">
								<label for="alias" class="col-sm-2 control-label">Alias *</label>
								<div class="col-sm-10">
									<input
											type="text"
											class="form-control input-sm"
											id="alias"
											name="alias"
											placeholder="URL friendly of this tour"
											value="<?php echo $tour->get('alias'); ?>"
											title="Friendly URL of tour"
											data-validation="required"
									/>
								</div>
							</div>
							<!-- Description -->
							<div class="form-group">
								<label for="description" class="col-sm-2 control-label">Description</label>
								<div class="col-sm-10">
									<input
											type="text"
											class="form-control input-sm"
											id="description"
											name="description"
											placeholder=""
											title="Tour description. Will use as site description for SEO"
											value="<?php echo $tour->get('description'); ?>"
									/>
								</div>
							</div>
							<!-- Keyword -->
							<div class="form-group">
								<label for="description" class="col-sm-2 control-label">Keyword</label>
								<div class="col-sm-10">
									<input
											type="text"
											class="form-control input-sm"
											id="keyword"
											name="keyword"
											placeholder=""
											title="Tour keyword. Will use as keyword for SEO"
											value="<?php echo $tour->get('keyword'); ?>"
									/>
								</div>
							</div>
							<hr/>
							<!-- Options -->
							<div class="options">
								<div class="form-group">
									<span class="col-sm-2 control-label label label-primary">
                                        <i class="fa fa-cogs" aria-hidden="true"></i> Options</span>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Skins</label>
									<div class="col-sm-10">
										<select class="form-control input-sm" title="skin" name="params[skin]">
											<?php foreach ($skins as $skin): ?>
												<option value="<?php echo $skin; ?>"><?php echo $skin; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<div class="checkbox">
										<label>
											<input
													type="checkbox"
													id="tour_rotation"
													name="params[rotation]"
												<?php echo ($isRotate) ? 'checked="checked"' : '' ?>
													value="1" size="80"/> Check for auto rotation.
										</label>
									</div>
								</div>

								<div class="form-group">
									<div class="checkbox">
										<label>
											<input
													type="checkbox"
													id="tour_social"
													name="params[socials]"
													value="1"
												<?php echo ($isSocial) ? 'checked="checked"' : '' ?>
													size="80"/>Check for show media social button.
										</label>
									</div>
								</div>
							</div>

							<hr/>
							<!-- Controls -->
							<div class="form-group">
								<button type="submit" id="saveTour" class="btn btn-primary btn-sm">
									<i class="fa fa-window-restore" aria-hidden="true"></i> Save
								</button>
								<button type="button" id="addScene" class="btn btn-info btn-sm">
									<i class="fa fa-plus-square" aria-hidden="true"></i> Add scene
								</button>
							</div>

						</div>
						<div class="col-md-8">
							<?php require_once __DIR__ . '/tour_scenes.php'; ?>
						</div>
					</div>
				</div>
				<fieldset>
					<?php if ($tour->id): ?>
						<input type="hidden" name="id" value="<?php echo $tour->id; ?>"/>
					<?php endif; ?>
					<input type="hidden" name="view" value="tour"/>
					<input type="hidden" name="task" value="ajaxSaveTour"/>
				</fieldset>
				<script>jQuery.validate({modules: "file"});</script>
			</form>
		</div>
	</div>
</div>
