<?php
defined('_VR360_EXEC') or die;

/**
 * This layout used for hidden scene form
 */
?>
<div class="hidden">
	<div class="panel panel-default scene">
		<div class="panel-heading">
			<div class="container-fluid">
				<div class="col-md-6">
					<div class="panel-title">New Scene</div>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-danger btn-sm pull-right removeScene"><i
								class="fa fa-remove"></i> Remove
					</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-md-12">
					<div id="sceneWrap form-horizontal">
						<div class="form-group">
							<label class="col-sm-3 control-label">Scene file *</label>
							<div class="col-sm-9">
								<input
										type="file"
										name="newSceneFile[]"
										data-validation="mime size required"
										data-validation-allowing="jpg, png, jpeg"
										data-validation-max-size="<?php echo ini_get('upload_max_filesize');?>"
								/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Name *</label>
							<div class="col-sm-9">
								<input name="newSceneName[]" type="text" class="form-control input-sm"
								       placeholder="Scene name" data-validation="required"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-9">
								<input
										name="newSceneDescription[]"
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
</div>