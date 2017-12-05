<?php
defined('_VR360_EXEC') or die;
?>
<div class="form-group">
	<button type="button" id="saveHotspots" class="btn btn-info saveHotspots" data-tour-id="<?php echo $tour->id ?>">
		<i class="fa fa-floppy-o" aria-hidden="true"></i> Save
	</button>
</div>
<iframe id='editTourHotspots' src="/hotspotsiframe.php?uId=<?php echo $tour->id ?>" width="100%" height="600"></iframe>
