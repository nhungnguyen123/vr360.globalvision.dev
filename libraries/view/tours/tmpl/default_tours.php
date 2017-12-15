<?php defined('_VR360_EXEC') or die; ?>
<div class="col-md-12">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				<i class="fas fa-th-list"></i>
				vTours <?php echo ($this->tours) ? '' : '<span class="label label-info">Use "Add new" to create new vTour</span>'; ?>
			</h3>
		</div>
		<div class="panel-body">
			<!-- Show tours -->
			<?php if (!empty($this->tours)): ?>
				<form name="tours-form" id="tours-form">
					<table
							id="vTours"
							class="table table-bordered table-hover table-condensed table-responsive"
					>
						<thead>
						<tr>
							<th>#</th>
							<th>
								<input id="tours-check-all" type="checkbox" class="tours-check-all" />
							</th>
							<th>
								<i class="fa fa-window-maximize" aria-hidden="true"></i> Name
							</th>
							<th>
								<i class="far fa-calendar-alt"></i> Created
							</th>
							<th>
								<i class="fas fa-eye"></i> Scenes
							</th>
							<th>
								<i class="fas fa-street-view"></i> Hotspots
							</th>
							<th>
								<i class="fa fa-cogs" aria-hidden="true"></i> Controls
							</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($this->tours as $tour): ?>
							<?php
							/** @var Vr360Tour $tour */
							$tourUrl = VR360_URL_ROOT . '/_/' . $tour->id;
							?>
							<tr
									id='vtour-<?php echo $tour->id; ?>'
									data-tour='<?php echo $tour->toJson(); ?>'
									class="is-valid-<?php echo ($tour->isValid()) ? 1 : '0 danger'; ?>"
							>
								<td class="vtour-id"><?php echo $tour->id; ?></td>
								<td>
									<input
											type="checkbox"
											class="checkbox-tour"
											name="id[]"
											value="<?php echo $tour->id ?>"
											title="<?php echo $tour->name ?>"
									/>
								</td>
								<td
										class="vtour-name hasTooltip"
										data-toggle="tooltip"
										data-placement="top"
										title="<?php echo $tour->getDescription(); ?>"
								>
									<span class="tour-name"><?php echo $tour->getName(); ?></span>
									<!-- Split button -->
									<div class="btn-group pull-right tour-links">
										<button type="button" class="btn btn-info">Links</button>
										<button
												type="button"
												class="btn btn-info dropdown-toggle"
												data-toggle="dropdown"
												aria-haspopup="true"
												aria-expanded="false"
										>
											<span class="caret"></span>
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<ul class="dropdown-menu">
											<li>
												<a
														type="button"
														class="tour-link-xml"
														target="_blank"
														href="<?php echo $tourUrl . '/vtour/tour.xml'; ?>">
													<i class="fas fa-file-alt"></i> /vtour/tour.xml
												</a>
											</li>
										</ul>
									</div>
									<div>
										<small>
											<span class="label label-default" class="tour-alias"><?php echo $tour->alias; ?></span>
										</small>
									</div>
								</td>
								<td class="vtour-date"><?php echo $tour->created; ?></td>
								<td class="scenes">
									<?php if ($tour->canEdit()): ?>
										<button
												type="button"
												class="btn btn-primary tour-edit"
												data-tour-id="<?php echo $tour->id ?>">
											Edit <span class="badge"><?php echo count($tour->getScenes()); ?></span>
										</button>
									<?php endif; ?>
								</td>
								<td class="hotspots">
									<?php if ($tour->canEditHotspot()): ?>
										<!-- Hotspot -->
										<button
												type="button"
												class="btn btn-primary tour-edit-hotspots"
												data-tour-id="<?php echo $tour->id ?>"
										>
											Edit <span class="badge"><?php echo $tour->getHotspots(); ?></span>
										</button>
									<?php endif; ?>
								</td>
								<td class="controls">
									<?php if ($tour->canEmbed()): ?>
										<!-- Embed -->
										<button
												type="button"
												class="btn btn-default tour-embed"
												data-tour-id="<?php echo $tour->id ?>"
										>
											<i class="fa fa-code" aria-hidden="true"></i> Embed
										</button>
									<?php endif; ?>
									<?php if ($tour->canPreview()): ?>
										<!-- Preview -->
										<a type="button" class="btn btn-info tour-preview" target="_blank"
										   href="/<?php echo $tour->alias ?>">
											<i class="fas fa-desktop"></i> Preview
										</a>
									<?php endif; ?>
									<button
											type="button"
											class="btn btn-danger tour-delete"
											data-tour-id="<?php echo $tour->id ?>">
										<i class="fas fa-minus"></i> Delete
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</form>
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
				<div class="col-md-12">
					<div class="alert alert-warning" role="alert">There are no vTours</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
