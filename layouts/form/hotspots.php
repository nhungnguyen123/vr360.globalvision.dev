<!-- <iframe src="/index.php?task=getEditTourHtmlHotspotEditorIFrame&uId=<?php echo $tour->dir; ?>"></iframe> -->
<!-- I dont understand how to render this file in tour controller without any POST param  -->
<div class="form-group">
  <button type="button" id="saveHotspots" class="btn btn-info saveHotspots" data-scene-id="<?php echo $tour->id; ?>">
    <i class="fa fa-floppy-o" aria-hidden="true"></i> Save
  </button>
</div>
<iframe id='editTourHotspots' src="/hotspotsiframe.php?uId=<?php echo $tour->dir; ?>" width="100%" height="600"></iframe>
