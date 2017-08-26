<div style="margin-top: 15px">
	<div class="col-md-12">
		<div class="col-md-6">
			<div class="form-group">
				<label for="tour_des">Name of vTour</label>
				<input type="text" class="form-control" id="tour_des" placeholder="Name of this tour">
			</div>
			<div class="form-group">
				<label for="tour_url">URL friendly</label>
				<input type="text" class="form-control" id="tour_url" placeholder="URL friendly of this tour">
			</div>

			<span class="label label-primary"><i class="fa fa-cogs" aria-hidden="true"></i> Options</span>
			<div class="checkbox">
				<label><input type="checkbox" id="tour_rotation" size="80"/>Check for auto rotation.</label>
			</div>
			<div class="checkbox">
				<label><input type="checkbox" id="tour_social" size="80"/>Check for show media social button.</label>
			</div>

			<div class="col-md-12">
				<div class="dd123" id="GUIControl">
					<button type="button" id="addButton" class="btn btn-primary" onclick="vrAdmin.addNew();"><i class="fa fa-plus-square"
					                                                                aria-hidden="true"></i> Add more
						panoramas
					</button>
					<button type="button" id="submitButton" class="btn btn-primary" onclick="vrAdmin.createTour();"><i class="fa fa-window-restore"
					                                                                   aria-hidden="true"></i> Create
						new vTour
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
			<div class="well well-sm pano">

				<label>Panorama</label>
				<button type="button" class="btn btn-danger rmChGUI rHyudM"
				        onclick="vrAdmin.removePano(this); return false;">Remove this pano
				</button>
				<hr/>
				<div class="container-fluid">
					<div id="panoWrap{{imgId}}">

						<div class="form-group">
							<label for="exampleInputFile">File input</label>
							<input type="file" id="img{{imgId}}_file"/>
							<p class="help-block">Select pano file</p>
						</div>

						<div class="form-group">
							<label for="img{{imgId}}_des">Title</label>
							<input type="text" class="form-control" id="img{{imgId}}_des"
							       placeholder="Pano title"/>
						</div>

						<div class="form-group">
							<label for="img{{imgId}}_des_sub">Description</label>
							<input type="text" class="form-control" id="img{{imgId}}_des_sub" size="80"
							       placeholder="Pano sub title"/>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
</div>
