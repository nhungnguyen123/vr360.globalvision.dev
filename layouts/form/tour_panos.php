<?php if (isset($tourSettings['scene']) && !empty($tourSettings['scene'])): ?>
	<?php foreach ($tourSettings['scene'] as $pano): ?>
		<?php
		$ext_file = substr($pano['@attributes']['thumburl'], -4);
		$file     = explode("_", $pano['@attributes']['name']);
		?>
		<div class="sortable ui-sortable well well-sm pano">
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
						<input type="text"
						       value="<?php echo $file[1] . $ext_file; ?>"
						       disabled="disabled"/>
						<input type="hidden" name="panoFile[]"
						       value="<?php echo $file[1] . $ext_file; ?>"/>
					</div>

					<div class="form-group">
						<label>Title</label>
						<input name="panoTitle[]" type="text" class="form-control"
						       placeholder="Pano title"
						       required
						       value="<?php echo $pano['@attributes']['title']; ?>"/>
					</div>

					<div class="form-group">
						<label>Description</label>
						<input
							name="panoDescription[]"
							type="text"
							class="form-control"
							size="80"
							placeholder="Pano sub title"
							required
							value="<?php echo $pano['@attributes']['subtitle']; ?>"
						/>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

<?php endif; ?>