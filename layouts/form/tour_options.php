<?php

defined('_VR360_EXEC') or die;

// Skins
$skins = Vr360HelperKrpano::getListOfSkins();

?>
<div class="tour-options">
	<div class="form-group">
		<span class="col-sm-2 control-label label label-primary">
			<i class="fa fa-cogs" aria-hidden="true"></i> Options
		</span>
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
	<div class="well well-sm">
		<div class="container-fluid">
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="checkbox"
								id="tour_rotation"
								name="params[rotation]"
							<?php echo ($tour->params->get('rotation', false)) ? 'checked="checked"' : '' ?>
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
							<?php echo ($tour->params->get('socials', false)) ? 'checked="checked"' : '' ?>
								size="80"/>Check for show media social button.
					</label>
				</div>
			</div>
		</div>
	</div>
</div>
