<?php

defined('_VR360_EXEC') or die;

// Skins
$skins = Vr360HelperFolder::files(VR360_PATH_ASSETS . '/krpano/skins');

?>

<div class="col-md-12">
	<div class="row">
		<div class="container-fluid">
			<!-- -->
			<?php require_once __DIR__ . '/tour_scene.php'; ?>
			<!-- Create new tour form -->
			<form method="post" id="form-tour" class="form-horizontal" enctype="multipart/form-data">
				<div class="col-md-12">
					<div class="row">
						<div class="form-group">
							<div class="well well-sm">
								<span class="label label-default">PHP upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?></span>
								<span class="label label-default">PHP post_max_size: <?php echo ini_get('post_max_size'); ?></span>
							</div>
						</div>
						<div class="col-md-4 form-horizontal">
							<!-- Name -->
							<div class="form-group">
								<label for="name" class="col-sm-2 control-label">Name</label>
								<div class="col-sm-10">
									<input
											type="text"
											class="form-control input-sm"
											id="name"
											name="name"
											placeholder="Name of this tour"
											value="<?php echo $tour->get('name'); ?>"
											title="Please fill your tour name"
											data-validation="required"
									/>
								</div>
								<p class="help-block"></p>
							</div>
							<!-- Alias -->
							<div class="form-group">
								<label for="alias" class="col-sm-2 control-label">Alias</label>
								<div class="col-sm-10">
									<input
											type="text"
											class="form-control input-sm"
											id="alias"
											name="alias"
											placeholder="URL friendly of this tour"
											value="<?php echo $tour->get('alias'); ?>"
											data-validation="required"
									/>
									<p class="help-block"></p>
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
											value="<?php echo $tour->get('description'); ?>"
									/>
									<p class="help-block"></p>
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
											value="<?php echo $tour->get('keyword'); ?>"
									/>
									<p class="help-block"></p>
								</div>
							</div>
							<hr/>
							<!-- Options -->
							<div class="options">
								<div class="form-group">
									<span class="col-sm-2 control-label label label-primary"><i class="fa fa-cogs"
									                                                            aria-hidden="true"></i> Options</span>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label">Skins</label>
									<div class="col-sm-10">
										<select class="form-control input-sm">
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
													value="1" size="80"/> Check
											for auto
											rotation.
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
													size="80"/>Check for show media social
											button.
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
							<?php if ($tour->id): ?>
							<div class="form-group">
								<label class="col-sm-3 control-label">Default scene</label>
								<div class="col-sm-9">
									<select class="form-control input-sm">

									</select>
								</div>
							</div>
							<?php endif; ?>
							<div id="scenes" class="scenes">
								<?php require_once __DIR__ . '/tour_panos.php'; ?>
							</div>
						</div>
					</div>
				</div>

				<fieldset>
					<input type="hidden" name="id" value="<?php echo $tour->id; ?>"/>
					<input type="hidden" name="view" value="tour"/>
					<input type="hidden" name="task" value="ajaxSaveTour"/>
				</fieldset>
				<script>jQuery.validate()</script>
			</form>
		</div>
	</div>
</div>
