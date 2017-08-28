<?php defined('_VR360') or die; ?>

<div>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active">
			<a href="#tours" aria-controls="home" role="tab" data-toggle="tab">
				<i class="fa fa-list" aria-hidden="true"></i> Tours</a>
		</li>
		<li role="presentation">
			<a href="#create" aria-controls="profile" role="tab" data-toggle="tab">
				<i class="fa fa-plus-square" aria-hidden="true"></i> Create new</a>
		</li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="tours">
			<?php Vr360Layout::load('body.user.tabs.tours'); ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="create">
			<?php Vr360Layout::load('body.user.tabs.create'); ?>
		</div>
	</div>
</div>