<div class="checkbox">
	<label>
		<input type="checkbox" id="tour_setting" name="skin_settings" size="80"
		       onclick="showHideSkinSetting(this)"/>Skin settings
	</label>
</div>


<div id="skin-settings" style="">
	<?php foreach ($tourSettings['skin_settings']['@attributes'] as $key => $value): ?>
		<div class="controls">
			<label for="tour_des"><?php echo $key ?></label>
			<input type="text" class="form-control" id="<?php echo $key ?>"
			       name="<?php echo $key ?>" placeholder="<?php echo $key ?>"
			       value="<?php echo $value; ?>"
			       title="Please fill your <?php echo $key ?>"
			/>
			<p class="help-block"></p>
		</div>
	<?php endforeach; ?>
</div>