<?php

defined('_VR360_EXEC') or die;

/** @var Vr360Tour $tour */
?>
<!-- Hidden scene form -->
<?php require_once __DIR__ . '/tour_scene.php'; ?>
<!-- Create new tour form -->
<form method="post" id="tour-form" class="form-horizontal tour-form" enctype="multipart/form-data">
	<?php require_once __DIR__ . '/tour_information.php'; ?>
	<div class="col-md-6 form-horizontal">
		<!-- Name -->
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label">Name *</label>
			<div class="col-sm-10">
				<input
						type="text"
						class="form-control input-sm"
						id="name"
						name="name"
						placeholder="Name of this tour"
						value="<?php echo $tour->get('name'); ?>"
						title="Name of tour. Will use as site title"
						data-validation="required"/>
			</div>
		</div>
		<!-- Alias -->
		<div class="form-group">
			<label for="alias" class="col-sm-2 control-label">Alias *</label>
			<div class="col-sm-10">
				<input
						type="text"
						class="form-control input-sm"
						id="alias"
						name="alias"
						placeholder="URL friendly of this tour"
						value="<?php echo $tour->get('alias'); ?>"
						title="Friendly URL of tour"
						data-validation="required"
				/>
			</div>
		</div>
		<!-- Description -->
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label">Description</label>
			<div class="col-sm-10">
				<input
						type="text"
						class="form-control input-sm"
						id="description"
						name="description"
						placeholder=""
						title="Tour description. Will use as site description for SEO"
						value="<?php echo $tour->get('description'); ?>"
				/>
			</div>
		</div>
		<!-- Keyword -->
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label">Keyword</label>
			<div class="col-sm-10">
				<input
						type="text"
						class="form-control input-sm"
						id="keyword"
						name="keyword"
						placeholder=""
						title="Tour keyword. Will use as keyword for SEO"
						value="<?php echo $tour->get('keyword'); ?>"
				/>
			</div>
		</div>
		<hr/>
		<!-- Options -->
		<?php require_once __DIR__ . '/tour_options.php'; ?>
		<hr/>
		<!-- Controls -->
		<div class="form-group">
			<button type="submit" id="tour-save" class="btn btn-primary btn-sm">
				<i class="fa fa-window-restore" aria-hidden="true"></i> Save
			</button>
		</div>
	</div>
	<div class="col-md-6">
		<?php require_once __DIR__ . '/tour_scenes.php'; ?>
	</div>
	<fieldset>
		<?php if ($tour->id): ?>
			<input type="hidden" name="id" value="<?php echo $tour->id; ?>"/>
		<?php endif; ?>
		<input type="hidden" name="view" value="tour"/>
		<input type="hidden" name="task" value="ajaxSaveTour"/>
	</fieldset>
	<script>jQuery.validate({modules: "file"});</script>
</form>


