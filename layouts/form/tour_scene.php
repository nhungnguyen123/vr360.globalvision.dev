<div class="hidden">
	<div class="well well-sm scene">
		<div class="container-fluid">
			<div class="col-md-12">
				<div class="pull-right">
					<button type="button" id="removeScene" class="btn btn-danger btn-sm">Remove</button>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="col-md-12">
				<div id="sceneWrap form-horizontal">
					<div class="form-group">
						<label class="col-sm-3 control-label">Scene file</label>
						<div class="col-sm-9">
							<input
								type="file"
								name="sceneFile[]"
								data-validation="required mime size"
								data-validation="size" data-validation-max-size="30MB" data-validation-allowing="jpg, png"
							/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Name</label>
						<div class="col-sm-9">
							<input name="sceneName[]" type="text" class="form-control input-sm" placeholder="Scene name" data-validation="required"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Description</label>
						<div class="col-sm-9">
							<input
								name="sceneDescription[]"
								type="text"
								class="form-control input-sm"
								size="80"
								placeholder="Scene description"
							/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>