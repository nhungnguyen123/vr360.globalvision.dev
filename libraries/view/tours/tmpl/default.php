<?php
defined('_VR360_EXEC') or die;
?>

<?php require_once __DIR__ . '/default_nav.php'; ?>

<?php require_once __DIR__ . '/default_tours.php'; ?>

<div class="modal fade" id="vrModal" tabindex="-1" role="dialog" aria-labelledby="vrModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Embed code
				</h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="vrTour" tabindex="-1" role="dialog" aria-labelledby="vrTourLabel">
	<div class="modal-dialog" role="document" style="width: 90%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-square" aria-hidden="true"></i> New tour
				</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>