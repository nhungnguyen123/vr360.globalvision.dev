<?php defined('_VR360') or die; ?>

<tr id='vtour-<?php echo $tour->id; ?>' data-tour='<?php echo $tour->toJson(); ?>' class="">
	<td class="vtour-id"><?php echo $tour->id; ?></td>
	<td class="vtour-name"><?php echo $tour->name; ?></td>
	<td class="vtour-url"><?php echo $tour->alias; ?></td>
	<td class="vtour-date"><?php echo $tour->created; ?></td>
	<td class="status"><?php echo ($tour->status == 1) ? '<i class="fa fa-check" aria-hidden="true"></i>' : '' ; ?></td>
	<td class="controls">
		<!-- Embed -->
		<button type="button" class="btn btn-default embedCode" data-id="<?php echo $tour->id; ?>">
			<i class="fa fa-code" aria-hidden="true"></i> Get EmbedCode
		</button>
		<!-- Edit -->
		<button type="button" class="btn btn-primary editTour" data-id="<?php echo $tour->id; ?>">
			<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
		</button>
		<!-- Preview -->
		<button type="button" class="btn btn-info" onclick="vrAdmin.vrTours.preview(<?php echo $tour->id; ?>)">
			<i class="fa fa-external-link" aria-hidden="true"></i> Preview</button>
		<button type="button" class="btn btn-danger" onclick="vrAdmin.vrTours.removeTour(<?php echo $tour->id; ?>)">
			<i class="fa fa-eraser" aria-hidden="true"></i> Remove
		</button>
	</td>
</tr>