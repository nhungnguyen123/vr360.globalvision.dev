<?php defined('_VR360_EXEC') or die; ?>
<div class="col-md-12">
	<div class="container-fluid">
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="fas fa-th-list"></i> vTours <?php echo ($this->tours) ? '' : '<span class="label label-info">Use "Add new" to create new vTour</span>'; ?>
					</h3>
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
								<th><i class="far fa-calendar-alt"></i> Created</th>
								<th><i class="fas fa-eye"></i> Scenes</th>
								<th><i class="fas fa-street-view"></i> Hotspots</th>
								<th><i class="fa fa-cogs" aria-hidden="true"></i> Controls</th>
								<th>ID</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($this->tours as $tour): ?>
								<?php /** @var Vr360Tour $tour */ ?>
								<tr
										id='vtour-<?php echo $tour->id; ?>'
										data-tour='<?php echo $tour->toJson(); ?>'
										class="is-valid-<?php echo (int) $tour->isValid(); ?>"
								>
									<td>
										<input
												id="checkBox"
												type="checkbox"
												name="id[]"
												value="<?php echo $tour->id ?>"
												title="<?php echo $tour->name ?>"
										/>
									</td>
									<td class="vtour-name hasTooltip" data-toggle="tooltip" data-placement="top"
									    title="<?php echo $tour->getDescription(); ?>">
										<?php echo $tour->getName(); ?>
										<div>
											<small>
												<a
														href="<?php echo VR360_URL_ROOT . '/_/' . $tour->id . '/vtour/tour.xml'; ?>"
														target="_blank"
												>
													<?php echo VR360_URL_ROOT . '_/' . $tour->id . '/vtour/tour.xml'; ?>
												</a>
											</small>
										</div>
									</td>
									<td class="vtour-url"><?php echo $tour->alias; ?></td>
									<td class="vtour-date"><?php echo $tour->created; ?></td>
									<td class="scenes"><strong><?php echo count($tour->getScenes()) ?></strong></td>
									<td class="hotspots">
										<strong><?php echo $tour->getHotspots(); ?></strong>
									</td>
									<td class="controls">
										<?php if ($tour->canEmbed()): ?>
											<!-- Embed -->
											<button
													type="button"
													class="btn btn-default embedCode"
													data-tour-id="<?php echo $tour->id ?>"
											>
												<i class="fa fa-code" aria-hidden="true"></i> Embed
											</button>
										<?php endif; ?>

										<?php if ($tour->canEdit()): ?>
											<!-- Edit -->
											<button type="button" class="btn btn-primary editTour"
											        data-tour-id="<?php echo $tour->id ?>">
												<i class="fas fa-edit"></i> Edit
											</button>
										<?php endif; ?>

										<?php if ($tour->canEditHotspot()): ?>
											<!-- Hotspot -->
											<button type="button" class="btn btn-primary editTourHotspot"
											        data-tour-id="<?php echo $tour->id ?>">
												<i class="fas fa-street-view"></i> Hotspot
											</button>
										<?php endif; ?>

										<?php if ($tour->canPreview()): ?>
											<!-- Preview -->
											<a type="button" class="btn btn-info previewTour" target="_blank"
											   href="/<?php echo $tour->alias ?>">
												<i class="fas fa-desktop"></i> Preview
											</a>
										<?php endif; ?>

										<button type="button" class="btn btn-danger removeTour"
										        data-tour-id="<?php echo $tour->id ?>">
											<i class="fas fa-minus"></i> Remove
										</button>
									</td>
									<td class="vtour-id"><?php echo $tour->id; ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						<?php if ($this->pagination['total'] > 0): ?>
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
						<?php endif; ?>
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
