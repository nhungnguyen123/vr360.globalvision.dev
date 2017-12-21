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
			<select class="form-control input-sm tour-skins" title="skin" name="params[skin]">
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
								id="tour-param-rotation"
								class="tour-params"
								name="params[rotation]"
							<?php echo ($tour->params->get('rotation', false)) ? 'checked="checked"' : '' ?>
								value="1" size="80"/> <?php echo \Joomla\Language\Text::_('TOUR_LABEL_OPTION_AUTO_ROTATION'); ?>
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="checkbox"
								id="tour-param-socials"
								class="tour-params"
								name="params[socials]"
								value="1"
							<?php echo ($tour->params->get('socials', false)) ? 'checked="checked"' : '' ?>
								size="80"/> <?php echo \Joomla\Language\Text::_('TOUR_LABEL_OPTION_AUTO_ROTATION'); ?>
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="checkbox"
								id="tour-param-use-logo"
								class="tour-params"
								name="params[uselogo]"
								value="1"
							<?php echo ($tour->params->get('uselogo', false)) ? 'checked="checked"' : '' ?>
								size="80"/> <?php echo \Joomla\Language\Text::_('TOUR_LABEL_OPTION_USE_LOGO'); ?>
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="checkbox"
								id="tour-param-logo"
								class="tour-params"
								name="params[logo]"
								value="1"
							<?php echo ($tour->params->get('logo', false)) ? 'checked="checked"' : '' ?>
								size="80"/>Check for Logo
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input
								type="checkbox"
								id="tour-param-vr"
								class="tour-params"
								name="params[vr_mode]"
								value="1"
							<?php echo ($tour->params->get('vr_mode', false)) ? 'checked="checked"' : '' ?>
								size="80"/>Vr mode
					</label>
				</div>
			</div>
			<div class="form-group">
				Select Hotspots type
				<div class="checkbox">
					<label>
						<input
								type="radio"
								id="tour-param-vr"
								class="tour-params"
								name="params[hot_type]"
								value="blinking"
								<?php echo ($tour->params->get('vr_mode', false)) ? 'checked="checked"' : '' ?>
								size="80"/>Blinking
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input
								type="radio"
								id="tour-param-vr"
								class="tour-params"
								name="params[hot_type]"
								value="still"
								<?php echo ($tour->params->get('vr_mode', false)) ? 'checked="checked"' : '' ?>
								size="80"/>Still
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input
								type="radio"
								id="tour-param-vr"
								class="tour-params"
								name="params[hot_type]"
								value="custom"
								<?php echo ($tour->params->get('vr_mode', false)) ? 'checked="checked"' : '' ?>
								size="80"/>Custom icon
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input
								type="radio"
								id="tour-param-vr"
								class="tour-params"
								name="params[hot_type]"
								value="graphic"
								<?php echo ($tour->params->get('vr_mode', false)) ? 'checked="checked"' : '' ?>
								size="80"/>Graphic
					</label>
				</div>
			</div>
		</div>
	</div>
</div>