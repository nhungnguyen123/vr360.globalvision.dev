<table id='vtourListTbl' class="table table-condensed table-bordered table-responsive table-hover"
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
	<!-- Template -->
	<tr id='vtourData{{dir}}' data-dir="{{dir}}" class="{{tour_status_class}}">
		<td class="vtour_id">{{id}}</td>
		<td class="vtour_name">{{name}}</td>
		<td class="vtour_url">
			{{alias}}<a href="#" onclick="changeUrl({{id}})" class="pull-right"><i class="fa fa-pencil-square-o"
			                                                                       aria-hidden="true"></i></a>
		</td>
		<td class="vtour_date">{{created}}</td>
		<td class="status">{{status}}</td>
		<td class="getEmbed">
			<button type="button" class="btn btn-default" onclick="vrAdmin.vrTours.getEmbed('{{dir}}')">
				<i class="fa fa-code" aria-hidden="true"></i> Get EmbedCode
			</button>
			<button class="vtourEditButton" onclick="{{manager.editor}}.edit('{{u_id}}')">Edit
			</button>
			<button onclick="{{manager.vtourList}}.preview('{{u_id}}')">Preview</button>
			<button type="button" class="btn btn-danger" onclick="vrAdmin.vrTours.removeTour('{{u_id}}')">
				<i class="fa fa-eraser" aria-hidden="true"></i> Remove
			</button>
		</td>
	</tr>
	</tbody>
</table>

<nav aria-label="...">
	<ul class="pagination">
		<li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
		<li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">5</a></li>
		<li><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
	</ul>
</nav>