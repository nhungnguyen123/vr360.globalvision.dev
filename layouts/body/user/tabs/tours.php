<?php defined('_VR360') or die; ?>

<?php $tours = Vr360Database::getInstance()->getTours(); ?>

<?php if (!empty($tours)): ?>
	<table id='vTours' class="table table-condensed table-bordered table-responsive table-hover"
	       style="margin-top: 15px;">
		<thead>
		<tr>
			<th>#</th>
			<th><i class="fa fa-window-maximize" aria-hidden="true"></i> vTour name</th>
			<th><i class="fa fa-link" aria-hidden="true"></i> Friendly URL</th>
			<th><i class="fa fa-calendar" aria-hidden="true"></i> Creation day</th>
			<th><i class="fa fa-check-square-o" aria-hidden="true"></i> Status</th>
			<th><i class="fa fa-cogs" aria-hidden="true"></i> Controls</th>
		</tr>
		</thead>
		<tbody id='vtourList'>
		<?php foreach ($tours as $tour): ?>
			<?php $tour->render(); ?>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php Vr360Layout::load('body.user.tours.pagination'); ?>
<?php endif; ?>

<?php Vr360Layout::load('body.user.modals.embed'); ?>