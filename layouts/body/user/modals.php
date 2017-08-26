
<!-- Embed Modal -->
<div class="modal fade" id="embedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Embed code</h4>
			</div>
			<div class="modal-body">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Embed Modal -->
<div class="modal fade" id="uploadWrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Create new vTour</h4>
			</div>
			<div class="modal-body">

			</div>
		</div>
	</div>
</div>


<div id='ifrDialog' title="Hotspot Editor">
	<iframe id="if-ddhsy" {{src}}="/hotspotEditor.inside-iframe.php?t={{UID}}&p={{pId}}" width="98%"
	        height="98%"></iframe>
</div>

<div id="editDialog" title="Edit vTour">
	<fieldset id='fieldset_e_{{imgId}}'>
		<legend>Panorama#{{imgId}}:</legend>
		<img width="80"
		     {{src}}="http://vr360.globalvision.ch/_/{{UID}}/vtour/panos/{{imgId}}.tiles/thumb.jpg"/>
		<input type="text" size="65" name="img_e_{{imgId}}_des"
		       id="img_e_{{imgId}}_des" value="{{img_val}}"/> <input type="text"
		                                                             size="65"
		                                                             name="img_e_{{imgId}}_des_sub"
		                                                             id="img_e_{{imgId}}_des_sub"
		                                                             value="{{img_val_sub}}"/>
		<button onclick="{{manager.editor}}.remove('{{imgId}}')">Remove this
			panorama
		</button>
		<button onclick="{{manager.editor}}.makeDefaultScene('{{imgId}}')">Make
			This Default Scene
		</button>
	</fieldset>
</div>
<div id="editorNewPana">
	<fieldset id='fieldsetNew'>
		<legend>New Panorama:</legend>
		<div id="panoWrapNewPano">
			<input type="file" id="imgNewPano_file"/>
			<input type="text"
			       id="imgNewPano_des" size="60" placeholder="New pano title"/>
			<input
					type="text" id="imgNewPano_des_sub" size="60"
					placeholder="New pano sub title"/>
		</div>
	</fieldset>
</div>
<div id="savingDialog" title="Saving data..."></div>