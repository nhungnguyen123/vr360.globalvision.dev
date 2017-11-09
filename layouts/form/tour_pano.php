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
					<input type="file" name="panoFile[]"/>
					<p class="help-block hb-select-pano-file">Select pano file</p>
				</div>

				<div class="form-group">
					<label>Title</label>
					<input name="panoTitle[]" type="text" class="form-control"
						   placeholder="Pano title"/>
				</div>

				<div class="form-group">
					<label>Description</label>
					<input
							name="panoDescription[]"
							type="text"
							class="form-control"
							size="80"
							placeholder="Pano sub title"
					/>
				</div>
			</div>
		</div>
	</div>
</div>