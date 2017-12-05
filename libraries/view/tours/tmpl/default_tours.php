<?php defined('_VR360_EXEC') or die; ?>
<div class="col-md-12">
	<div class="container-fluid">
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						vTours <?php echo ($this->tours) ? '' : '<span class="label label-info">Use "Add new" to create new vTour</span>'; ?></h3>
				</div>
				<div class="panel-body">
					<!-- Show tours -->
					<?php if (!empty($this->tours)): ?>
						<table id="vTours" class="table table-condensed table-bordered table-responsive table-hover">
							<thead>
							<tr>
								<th>#</th>
								<th><i class="fa fa-window-maximize" aria-hidden="true"></i> Name</th>
								<th><i class="fa fa-link" aria-hidden="true"></i> Alias</th>
								<th><i class="fa fa-calendar" aria-hidden="true"></i> Created</th>
								<th><i class="fa fa-image" aria-hidden="true"></i> Scenes</th>
								<th><i class="fa fa-image" aria-hidden="true"></i> Hotspots</th>
								<th><i class="fa fa-cogs" aria-hidden="true"></i> Controls</th>
								<th>ID</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($this->tours as $tour): ?>
								<?php /** @var Vr360Tour $tour */ ?>
								<tr id='vtour-<?php echo $tour->id; ?>' data-tour='<?php echo $tour->toJson(); ?>'
								    class="is-valid-<?php echo (int) $tour->isValid(); ?>">
									<td>
										<input
												id="checkBox"
												type="checkbox"
												name="id[]"
												value="<?php echo $tour->id ?>"
												title="<?php echo $tour->name ?>"
										/>
									</td>
									<?php $tooltip = !$tour->isValid() ? 'Missing data files' : ''; ?>
									<td class="vtour-name" data-toggle="tooltip" data-placement="left"
									    title="<?php echo $tooltip; ?>">
										<?php echo $tour->getName(); ?>
										<div>
											<small><?php echo '_/' . $tour->id . '/vtour/tour.xml'; ?></small>
										</div>
									</td>
									<td class="vtour-url"><?php echo $tour->alias; ?></td>
									<td class="vtour-date"><?php echo $tour->created; ?></td>
									<td class="scenes"><strong><?php echo count($tour->getScenes()) ?></strong></td>
									<td class="hotspots">
										<strong>
											<?php
											echo $tour->getHotspots();
											?>
										</strong>
									</td>
									<td class="controls">
										<?php if ($tour->canEmbed()): ?>
											<!-- Embed -->
											<button type="button" class="btn btn-default btn-sm embedCode"
											        data-tour-id="<?php echo $tour->id ?>">
												<i class="fa fa-code" aria-hidden="true"></i> Embed
											</button>
										<?php endif; ?>

										<?php if ($tour->canEdit()): ?>
											<!-- Edit -->
											<button type="button" class="btn btn-primary btn-sm editTour"
											        data-tour-id="<?php echo $tour->id ?>">
												<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
											</button>
										<?php endif; ?>

										<?php if ($tour->canEditHotspot()): ?>
											<!-- Hotspot -->
											<button type="button" class="btn btn-primary btn-sm editTourHotspot"
											        data-tour-id="<?php echo $tour->id ?>">
												<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Hotspot
											</button>
										<?php endif; ?>

										<?php if ($tour->canPreview()): ?>
											<!-- Preview -->
											<a type="button" class="btn btn-info btn-sm previewTour" target="_blank"
											   href="/<?php echo $tour->alias ?>">
												<i class="fa fa-external-link" aria-hidden="true"></i> Preview
											</a>
										<?php endif; ?>

										<button type="button" class="btn btn-danger btn-sm removeTour"
										        data-tour-id="<?php echo $tour->id ?>">
											<i class="fa fa-eraser" aria-hidden="true"></i> Remove
										</button>
									</td>
									<td class="vtour-id"><?php echo $tour->id; ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						<!-- Pagination -->
						<div class="col-md-12">
							<div class="row">

									<nav aria-label="...">
										<ul class="pagination">
											<?php for ($index = 0; $index <= $this->pagination['total']; $index++): ?>
												<li class="<?php echo $index == $this->pagination['current'] ? 'active' : ''; ?>">
													<a href="index.php?page=<?php echo $index; ?>"><?php echo $index; ?></a>
												</li>
											<?php endfor; ?>
										</ul>
									</nav>

							</div>
						</div>
					<?php else: ?>
						<div class="container-fluid">
							<div class="alert alert-warning" role="alert">There are no vTours</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>