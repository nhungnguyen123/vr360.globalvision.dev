<?php
defined('_VR360_EXEC') or die;

/**
 * This layout used for display all scenes
 *
 * @var  array $scenes List of scenes
 */
?>
<div id="scenes" class="scenes">
	<?php if (isset($scenes) && !empty($scenes)): ?>
		<?php foreach ($scenes as $scene): ?>
		<div class="panel <?php echo $scene->default ? 'panel-primary' : 'panel-info' ?> scene">
			<div class="panel-heading">
				<div class="container-fluid">
					<div class="col-md-6">
						<div class="panel-title">Scene <span class="badge"><small><?php echo count($scene->getHotspots()); ?> hotspots</small></span></div>
					</div>
					<div class="col-md-6">
						<button type="button" class="btn btn-danger btn-sm pull-right removeScene">
							<i class="fa fa-remove"></i> Remove
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
									<input type="text" value="<?php echo $scene->file ?>" class="form-control disabled"
										   disabled="disabled" title="Scene File"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Name *</label>
								<div class="col-sm-9">
									<input
											name="sceneName[<?php echo $scene->id ?>]"
											type="text"
											class="form-control input-sm"
											placeholder="Scene name"
											value="<?php echo $scene->name ?>"
									/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Description</label>
								<div class="col-sm-9">
									<input
											name="sceneDescription[<?php echo $scene->id ?>]"
											type="text"
											class="form-control input-sm"
											size="80"
											placeholder="Scene description"
											value="<?php echo $scene->description ?>"
									/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<div class="checkbox">
										<label>
											<input
													name="sceneDefault"
													type="radio"
													value="<?php echo $scene->id ?>"
												<?php echo $scene->default ? 'checked' : '' ?>
											/>
											Set as default scene
										</label>
									</div>
								</div>
							</div>
							<input type="hidden" name="sceneId[]" value="<?php echo $scene->id ?>"/>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			jQuery(document).ready(function ()
			{
				jQuery(function ()
				{
					jQuery(".scenes").sortable();
					jQuery(".scenes").disableSelection();
				});
			})
		</script>
	<?php endforeach; ?>
	<?php else: ?>
		<div class="alert alert-warning" role="alert">There is no scene please add at least one</div>
	<?php endif; ?>
</div>