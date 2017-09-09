<?php defined('_VR360') or die; ?>

<form method="post" id="createTour">
	<div style="margin-top: 15px">
		<div class="col-md-12">
			<div class="col-md-6">
				<!-- Name -->
				<div class="form-group">
					<label for="tour_des">Name of vTour</label>
					<input type="text" class="form-control" id="name" name="name" placeholder="Name of this tour"
					      value="<?php if (isset($vTourName)) echo $vTourName; ?>" required>
				</div>
				<!-- Alias -->
				<div class="form-group">
					<label for="tour_url">URL friendly</label>
					<input type="text" class="form-control" id="alias" name="alias"
					       placeholder="URL friendly of this tour" value="<?php if (isset($vTourAlias)) echo $vTourAlias; ?>" required>
				</div>

				<!-- Options -->
				<span class="label label-primary"><i class="fa fa-cogs" aria-hidden="true"></i> Options</span>
				<div class="checkbox">
					<label><input type="checkbox" id="tour_rotation" name="tourRotation" size="80"/>Check for auto
						rotation.</label>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" id="tour_social" name="tourSocials" size="80"/>Check for show media
						social button.</label>
				</div>

				<div class="col-md-12">
					<div class="dd123" id="GUIControl">
						<button type="submit" id="createTour" class="btn btn-primary">
							<i class="fa fa-window-restore" aria-hidden="true"></i> Create new vTour
						</button>
						<button type="button" id="addPano" class="btn btn-info addPano">
							<i class="fa fa-plus-square" aria-hidden="true"></i> Add more panoramas
						</button>
					</div>
				</div>
				<div class="col-md-12">
					<div class="vr-logging" id="vrLogging">
						<div class="progress hide">
							<div
									class="progress-bar progress-bar-info progress-bar-striped active"
									role="progressbar"
									aria-valuenow="20"
									aria-valuemin="0"
									aria-valuemax="100"

							<span class="sr-only"></span>
						</div>
					</div>
					<div id="subUploadWrap">
						<div class="dd123" id="uploadPrr"></div>
					</div>
				</div>

			</div>
		</div>
		<div class="col-md-6">
			<div class="dd123" id="divMain">
				<div id="rootPano">
					<div class="well well-sm pano">

						<label>Panorama</label>
						<button
								type="button" class="btn btn-danger"
								onclick="vrAdmin.removePano(this); return false;">
							Remove this pano
						</button>
						<hr/>
						<div class="container-fluid">
							<div id="panoWrap">
								<div class="form-group">
									<label>File input</label>
									<input type="file" name="file[]" required/>
									<p class="help-block">Select pano file</p>
								</div>

								<div class="form-group">
									<label>Title</label>
									<input name="panoTitle[]" type="text" class="form-control"
									       placeholder="Pano title"/>
								</div>

								<div class="form-group">
									<label>Description</label>
									<input name="panoDescription[]" type="text" class="form-control" size="80"
									       placeholder="Pano sub title"/>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<fieldset>
		<input type="hidden" name="<?php echo Vr360Session::getInstance()->get('token'); ?>" value="1"/>
	</fieldset>
</form>
