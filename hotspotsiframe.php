<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$hotSpotImgUrl     = base64_encode("/assets/images/hotspot.png");
$hotSpotInfoImgUrl = base64_encode("/assets/images/information.png");
$tourId            = Vr360Factory::getInput()->getInt('uId', 0);
$tourUrl           = '//' . $_SERVER['HTTP_HOST'] . '/_/' . $tourId . '/vtour';

$tour = new Vr360Tour;
$tour->load(
	array(
		'id'         => $tourId,
		'created_by' => Vr360Factory::getUser()->id
	)
);

$tours = new Vr360ModelTours;
$scenes = !$tour->id ? array() : $tour->getScenes();

if(isset($_POST['insertoptions']) && !empty($_POST['insertoptions']) ){

	$user_id = $_POST["user_id"];
	$tour_id = $_POST["tour_id"];

	$text_t = $_POST["title_t"];
	$title_d = $_POST["title_d"];

	$tooltip_t = $_POST["tooltip_t"];
	$tooltip_d = $_POST["tooltip_d"];

	$modal_t =  $_POST["modal_t"];
	$modal_d =  $_POST["modal_d"];
	//image
	$image_url = $_POST["image_url"];
	//video
	$video_url = $_POST["video_url"];
	// scene
	$scene = $_POST['scene'];


	$posX = $_POST['posX'];
	$posY = $_POST['posY'];



	$option = $tours->SaveHotspot(
			array(
				'sceneId',
				'code',
				'ath',
				'atv',
				'style',
				'type',
				'params'
			),
			array(
				(int)$user_id,
				null,
				$posX,
				$posY,
				null,
				null,
				'params'
			)
		);

	$tours->SaveOption(
		array(
			'user_id',
			'hotspot_id',
			'text_t',
			'text_d',
			'tooltip_t',
			'tooltip_d',
			'modal_t',
			'modal_d',
			'image_url',
			'video_url',
			'link_scene',
		),
		array(
			(int)$user_id,
			$option,
			$text_t,
			$title_d,
			$tooltip_t,
			$tooltip_d,
			$modal_t,
			$modal_d,
			$image_url,
			$video_url,
			$scene
		)
	);

	echo $option;
	die();

}

if(isset($_POST["getoptions"] ) ){
	if(isset($_POST['hotspod_id']) && !empty($_POST['hotspod_id'])){
		$hotspod_id = $_POST['hotspod_id'];
		$res = $tours->GetOption($hotspod_id);
		echo json_encode($res[0]);
	}
	die();
}

if(isset($_POST["editoptions"] )){
	$text_t_edit = $_POST['text_t_edit'];
	$text_d_edit = $_POST['text_d_edit'];
	$tooltip_t_edit = $_POST['tooltip_t_edit'];
	$tooltip_d_edit = $_POST['tooltip_d_edit'];
	$modal_t_edit = $_POST['modal_t_edit'];
	$modal_d_edit = $_POST['modal_d_edit'];
	$image_input_edit = $_POST['image_input_edit'];
	$video_input_edit = $_POST['video_input_edit'];
	$scene_d_edit = $_POST['scene_d_edit'];
	$hotspod_id =$_POST["hotspod_id"];
	$res = $tours->GetOption($hotspod_id);
	$tours->Uptadeoption($hotspod_id,'text_t',$text_t_edit);
	$tours->Uptadeoption($hotspod_id,'text_d',$text_d_edit);
	$tours->Uptadeoption($hotspod_id,'tooltip_t',$tooltip_t_edit);
	$tours->Uptadeoption($hotspod_id,'tooltip_d',$tooltip_d_edit);
	$tours->Uptadeoption($hotspod_id,'modal_t',$modal_t_edit);
	$tours->Uptadeoption($hotspod_id,'modal_d',$modal_d_edit);
	$tours->Uptadeoption($hotspod_id,'image_url',$image_input_edit);
	$tours->Uptadeoption($hotspod_id,'video_url',$video_input_edit);
	$tours->Uptadeoption($hotspod_id,'link_scene',$scene_d_edit);
	die();
}



?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport"
	      content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta http-equiv="x-ua-compatible" content="IE=edge"/>
	<!-- Globalvision -->
	<link rel="stylesheet" type="text/css" href="./assets/css/tour.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/hotspots.min.css">
	<script type="text/javascript" src="./assets/vendor/jquery-2.2.4.min.js"></script>
	<script src='<?php echo $tourUrl . '/tour.js'; ?>'></script>
	<!-- Bootstrap -->
	<script type="text/javascript" src="./assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./assets/vendor/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="./assets/vendor/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" media="screen" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
	<style >
	/* Outer */

#edit-remove-move{
	    left: 990px !important;
    top: 180px !important;
    position: fixed !important;
}
.popup {
    width:100%;
    height:100%;
    display:none;
    position:fixed;
    top:0px;
    left:0px;
}

/* Inner */
.popup-inner {
    max-width:276px;
    width:90%;
    padding:10px;
    position:absolute;
    -webkit-transform:translate(-50%, -50%);
    transform:translate(-50%, -50%);
    box-shadow:0px 2px 6px rgba(0,0,0,1);
    border-radius:3px;
    background:#fff;
}

/* Close Button */
.popup-close {
    width:30px;
    height:30px;
    padding-top:4px;
    display:inline-block;
    position:absolute;
    top:0px;
    right:276px;
    transition:ease 0.25s all;
    -webkit-transform:translate(50%, -50%);
    transform:translate(50%, -50%);
    border-radius:1000px;
    background:rgba(0,0,0,0.8);
    font-family:Arial, Sans-Serif;
    font-size:20px;
    text-align:center;
    line-height:100%;
    color:#fff;
}

.popup-close:hover {
    -webkit-transform:translate(50%, -50%) rotate(180deg);
    transform:translate(50%, -50%) rotate(180deg);
    background:rgba(0,0,0,1);
    text-decoration:none;
}
.button-custom {
    width:126px;
    margin-top: 5px;
    margin-bottom: 5px;
}
.button-custom-save-cancel{
    margin-top: 5px;
    margin-bottom: 5px;
}
.button-custom-th{
	width:82px;
    margin-top: 5px;
    margin-bottom: 5px;
}

</style>
</head>
<body>
<div id="button-container">

	<div class="popup" data-popup="popup-1">
	    <div class="popup-inner" id="popup">
			<form >
				<input type="hidden" id="user_id" value="<?php echo $tour->created_by?>">
				<input type="hidden" id="tour_id" value="<?php echo $tour->id?>">
					<button type="button" id="add_hotpost" class="btn btn-primary btn-sm button-custom" onclick="addHotspot();">
						Add hotspot here
					</button>
					<button type="button" id="set_defaultView" class="btn btn-primary btn-sm" onclick="setDefaultView();">
						Set default view
					</button>
			<div id="open-add-hot" class="form-inline" style="display: none;">
				<div class="form-group">
					<div class="button-group" role="group">
						<button type="button" id="add_text" class="btn btn-primary btn-sm button-custom" onclick="addText();">
							Add Text
						</button>
						<button type="button" id="add_Tooltip" class="btn btn-primary btn-sm button-custom" onclick="addTooltip();">
							Add Tooltip
						</button>
					</div>
					<div class="button-group" role="group">
						<button type="button" id="add_Modal" class="btn btn-primary btn-sm button-custom" onclick="addModal();">
							Add  Modal
						</button>
						<button type="button" id="add_image" class="btn btn-primary btn-sm button-custom" onclick="addImage();">
							Add  Image
						</button>
					</div>
					<div class="button-group" role="group">
						<button type="button" id="add_video" class="btn btn-primary btn-sm button-custom" onclick="addVideo();">
							Add  Video
						</button>
						<button type="button" id="add_link" class="btn btn-primary btn-sm button-custom" onclick="addLink();">
							Add  Link a scene
						</button>
					</div>
					<!-- TeXt-->
					<div id="text_div" class="form-group" style="display: none;">
						<div class="form-group">
							<textarea
								class="form-control"
								placeholder="Input Description"
								id="text_text"
								maxlength="255"
								style="
								resize: none;
								width:257px;
								overflow:hidden;
								margin-top:2px;
								margin-bottom:2px;
								height: 155px;
								"
							></textarea>
						</div>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot()">Save
						</button>
						<button
							type="button"
							class="btn btn-default"
							onclick="onclickCancel()">Back
						</button>
					</div>
					<!-- ToooltIp-->
					<div id="Tooltip_div" class="form-group" style="display: none;">
						<div class="form-group">
							<input
								id='tooltip_t'
								type="text"
								size="30"
								placeholder="Input Tooltip Title"
								class="form-control"
							/>
						</div>
						<div class="form-group">
							<textarea
								class="form-control"
								placeholder="Input Description"
								id="tooltip_d"
								maxlength="255"
								style="
								resize: none;
								width:257px;
								overflow:hidden;
								margin-top:2px;
								margin-bottom:2px;
								height: 155px;
								"
							></textarea>
						</div>
						<br>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot()">Save
						</button>
						<button
							type="button"
							class="btn btn-default"
							onclick="onclickCancel()">Back
						</button>
					</div>
					<!-- Modal-->
					<div id="modal_div" class="form-group" style="display: none;">
						<div class="form-group">
							<input
								id='modal_t'
								type="text"
								size="30"
								placeholder="Input Modal Title"
								class="form-control"
							/>
						</div>
						<div class="form-group">
							<textarea
								class="form-control"
								placeholder="Input Description"
								id="modal_d"
								maxlength="255"
								style="
								resize: none;
								width:257px;
								overflow:hidden;
								margin-top:2px;
								margin-bottom:2px;
								height: 155px;
								"
							></textarea>
						</div>
						<br>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot()">Save
						</button>
						<button
							type="button"
							class="btn btn-default"
							onclick="onclickCancel()">Back
						</button>
					</div>
					<!-- Image-->
					<div id="image_div" class="form-group" style="display: none;">
						<div class="form-group">
							<input
								id='image_url'
								type="text"
								size="30"
								placeholder="Image Url"
								class="form-control"
								style="margin-bottom: 2px "
							/>
						</div>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot()">Save
						</button>
						<button
							type="button"
							class="btn btn-default"
							onclick="onclickCancel()">Back
						</button>
					</div>
					<!-- Video-->
					<div id="video_div" class="form-group" style="display: none;">
						<div class="form-group">
							<input
								id='video_url'
								type="text"
								size="30"
								placeholder="Video Url"
								class="form-control"
								style="margin-bottom: 5px; margin-top: 5px;"
							/>
						</div>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot()">Save
						</button>
						<button
							type="button"
							class="btn btn-default"
							onclick="onclickCancel()">Back
						</button>
					</div>
					<!-- addLinkScene-->
					<div id="link_div" class="form-group" style="display: none;">
						<select
							class="selectpicker"
							data-width="261px"
						>
						<?php foreach ($tours->getList() as $tour): ?>
							<option title="<?php echo $tour->name?>"" value="<?php echo $tour->alias?>"><?php echo $tour->name?></option>
						<?php endforeach ?>
						</select>
						<button
								type="button"
								id="savehotspots"
								class="btn btn-default "
								onclick="SaveHot()">Save
						</button>
						<button
							type="button"
							class="btn btn-default button-custom-save-cancel"
							onclick="onclickCancel()">Back
						</button>
					</div>
				</div>
			</div>
	        <a class="popup-close" data-popup-close="popup-1" href="#">x</a>
	    </div>
	</form>
	</div>

	<div class="popup-inner" id="edit-remove-move" style="display:none;">
		<div class="button-group" role="group">
			<button type="button" id="edit_text" class="btn btn-primary btn-sm button-custom" onclick="editText();" style="display: none;">
			Edit Text
			</button>
			<button type="button" id="edit_Tooltip" class="btn btn-primary btn-sm button-custom" onclick="editTooltip();" style="display: none;">
			Edit Tooltip
			</button>
		</div>
		<div class="button-group" role="group">
			<button type="button" id="edit_modal" class="btn btn-primary btn-sm button-custom" onclick="editModal();" style="display: none;">
			edit Modal
			</button>
			<button type="button" id="edit_image" class="btn btn-primary btn-sm button-custom" onclick="editImage();" style="display: none;">
			Edit Image
			</button>
		</div>
		<div class="button-group" role="group">
			<button type="button" id="edit_video" class="btn btn-primary btn-sm button-custom" onclick="editVideo();" style="display: none;">
			Edit Video
			</button>
			<button type="button" id="edit_link" class="btn btn-primary btn-sm button-custom" onclick="editScene();" style="display: none;">
			Edit Link a Scene
			</button>
		</div>
		<!-- <button type="button" id="saveEdit" class="btn btn-default" onclick="saveEdit()" style="display: none;">
		Save Edit
		</button> -->
		<button type="button" id="edit_hotpost" class="btn btn-primary btn-sm button-custom-th" onclick="editHotspot();">
		Edit
		</button>
		<button type="button" id="move_hotspot" class="btn btn-primary btn-sm button-custom-th" onclick="moveHotspot()" ;">
		Move
		</button>
		<button type="button" id="delete_hotpost" class="btn btn-primary btn-sm button-custom-th" onclick="deleteHotspot();">
		Delete
		</button>
		<a class="popup-close" data-popup-close="popup-1" href="#">x</a>

		<div id="text_div_edit" class="form-group" style="display: none;">
			<div class="form-group">
				<input
				id='text_t_edit'
				type="text"
				size="30"
				placeholder="Edit  Title"
				class="form-control"
				/>
			</div>
			<div class="form-group">
				<textarea
				class="form-control"
				placeholder="Edit Description"
				id="text_d_edit"
				maxlength="255"
				style="
				resize: none;
				width:257px;
				overflow:hidden;
				margin-top:2px;
				margin-bottom:2px;
				height: 155px;
				"
				></textarea>
			</div>
			<button
			type="button"
			class="btn btn-default"
			onclick="saveEdit()">Save
			</button>
		</div>

		<div id="tooltip_div_edit" class="form-group" style="display: none;">
			<div class="form-group">
				<input
				id='tooltip_t_edit'
				type="text"
				size="30"
				placeholder="Edit Title"
				class="form-control"
				/>
			</div>
			<div class="form-group">
				<textarea
				class="form-control"
				placeholder="Edit Tooltip"
				id="tooltip_d_edit"
				maxlength="255"
				style="
				resize: none;
				width:257px;
				overflow:hidden;
				margin-top:2px;
				margin-bottom:2px;
				height: 155px;
				"
				></textarea>
			</div>
			<button type="button" class="btn btn-default" onclick="saveEdit()">Save
			</button>
		</div>
		<div id="modal_div_edit" class="form-group" style="display: none;">
			<div class="form-group">
				<input
				id='modal_t_edit'
				type="text"
				size="30"
				placeholder="Edit  Title"
				class="form-control"
				/>
			</div>
			<div class="form-group">
				<textarea
				class="form-control"
				placeholder="Edit Description"
				id="modal_d_edit"
				maxlength="255"
				style="
				resize: none;
				width:257px;
				overflow:hidden;
				margin-top:2px;
				margin-bottom:2px;
				height: 155px;
				"
				></textarea>
			</div>
			<button type="button" class="btn btn-default" onclick="saveEdit()">Save
			</button>
		</div>
		<div id="image_div_edit" class="form-group" style="display:none">
			<div class="form-group">
				<input id="image_input_edit" type="text" size="30" placeholder="Image Url" class="form-control" style="margin-bottom: 2px ">
			<button type="button" class="btn btn-default" onclick="saveEdit()">
				Save
			</button>
			</div>
		</div>
		<div id="video_div_edit" class="form-group" style="display:none">
			<div class="form-group">
				<input id="video_input_edit" type="text" size="30" placeholder="Image Url" class="form-control" style="margin-bottom: 2px ">
				<button type="button" class="btn btn-default" onclick="saveEdit()">
					Save
				</button>
			</div>
		</div>
		<div id="scene_div_edit" class="form-group" style="display:none">
			<div class="form-group">
				<select
					id="scene_d_edit"
					class="selectpicker"
					data-width="261px"
					>
					<?php foreach ($tours->getList() as $tour): ?>
					<option title="<?php echo $tour->name?>"" value="<?php echo $tour->alias?>"><?php echo $tour->name?></option>
					<?php endforeach ?>
				</select>
			</div>
			<button type="button" class="btn btn-default" onclick="saveEdit()">
				Save
			</button>
		</div>

	</div>
	<div class="container-fluid">
			<div class="button-group" role="group">
			</div>
	</div>
</div>
<div id="show_link">
	Linked scene: <select id="selectbox">
		<?php if (!empty($scenes)): ?>
			<?php foreach ($scenes as $scene): ?>
				<option value="scene_<?php echo explode('.', $scene->file)[0] ?>"><?php echo $scene->name ?></option>
			<?php endforeach; ?>
		<?php endif; ?>
	</select>
	<button id="done_link" onclick="get_link()">Done</button>
</div>


<div id="pano" >
	<script type="text/javascript">
		$(function() {
			var krpano = document.getElementById('krpanoSWFObject');
			krpano.onhover="showtext(you are hovering me);"
			var timeout, clicker = $("#pano");
			var oldX, oldY;
			//----- OPEN
			clicker.mousedown(function(e){
				oldX = e.pageX;
				oldY = e.pageY;

			timeout = setInterval(function(){
				var targeted_popup_class = jQuery(this).attr('data-popup-open');
				$('[data-popup=popup-1]').fadeIn(350);
				let x = e.pageX + 138;
				let y = e.pageY + 59;
				$(".popup-inner").css({left: x, top: y});
				e.preventDefault();
			}, 500);

			clicker.mousemove(function(event){
				if (event.pageX != oldX || event.pageY != oldY) {
				clearInterval(timeout);
				}
			});

			return false;
			});

			$(document).mouseup(function(){
				clearInterval(timeout);
				return false;
			});

		    //----- CLOSE
			$('[data-popup-close]').on('click', function(e)  {

				$('#text_div').hide();
				$('#Tooltip_div').hide();
				$('#modal_div').hide();
				$('#video_div').hide();
				$('#image_div').hide();
				$('#link_div').hide();
				$('#open-add-hot').hide();


				$('#edit-remove-move').hide();
				$('#text_div_edit').hide();
			enableButton(['#edit_hotpost', '#move_hotspot', '#delete_hotpost'])
			disableButton(['#edit_text','#edit_Tooltip','#edit_modal','#edit_image','#edit_video','#edit_link'])


			enableButton(['#set_defaultView', '#add_hotpost'])
			enableButton(['#add_text','#add_Tooltip', '#add_Modal', '#add_image', '#add_video' ,'#add_link']);

				var targeted_popup_class = jQuery(this).attr('data-popup-close');
				$('[data-popup="' + targeted_popup_class + '"]').fadeOut(0);
				e.preventDefault();
			});
		});

		embedpano({
			swf: '<?php echo $tourUrl . '/tour.swf'; ?>',
			xml: '<?php echo $tourUrl . '/tour.xml?' . time(); ?>',
			target: "pano",
			html5: "prefer",
			passQueryParameters: true
		});

		var krpano = document.getElementById('krpanoSWFObject');

		var add_hotpost = document.getElementById('add_hotpost');
		var hotspot_done = document.getElementById('add_text');
		var selectbox = document.getElementById('selectbox');
		var showlink = document.getElementById('show_link');

		var i = 0;
		var hotspots = {};
		var uniqname = '';
		var scene_nums = krpano.get('scene.count');
		var hotspotList = [];
		var current_scene = '';
		var current_vTour_hotspot_counter = 0;
		var current_randome_val = Math.round(Math.random() * 1000000000).toString() + Math.round(Math.random() * 1000000000).toString();

		function disableButton(elements) {
			if (jQuery.isArray(elements)) {
				jQuery.each(elements, function (index, element) {
					jQuery(element).attr('disabled', 'disabled');
					jQuery(element).hide();
				})
			}
			else {
				jQuery(elements).attr('disabled', 'disabled');
				jQuery(elements).hide();
			}
		}

		function enableButton(elements) {
			if (jQuery.isArray(elements)) {
				jQuery.each(elements, function (index, element) {
					jQuery(element).removeAttr('disabled');
					jQuery(element).show();
				})
			}
			else {
				jQuery(element).removeAttr('disabled');
				jQuery(element).show();
			}
		}

		function getHotspotsCount() {
			return krpano.get('hotspot.count');
		}

		function addHotspot(currentHotspotData) {
			disableButton([ '#set_defaultView', '#add_hotpost']);
			$('#open-add-hot').show();
		}

		function addText(){
		// alert(1);
		// alert(uniqname);
		disableButton(['#add_Tooltip', '#add_Modal', '#add_image', '#add_video' ,'#add_link' ]);
			$("#text_div").show();

		}

		function addModal(){
		disableButton(['#add_Tooltip', '#add_text', '#add_image', '#add_video' ,'#add_link' ,'#savehotspots' ]);
			$("#modal_div").show();
		}

		function addTooltip(){
		disableButton(['#add_Modal', '#add_text', '#add_image', '#add_video' ,'#add_link' ,'#savehotspots' ]);
			$("#Tooltip_div").show();
		}

		function addImage(){
			disableButton([ '#add_text' , '#add_Modal', '#add_Tooltip', '#add_video' ,'#add_link' ,'#savehotspots' ]);
			$("#image_div").show();
		}

		function addVideo(){
			krpano.call("set(hotspot[" + uniqname + "].hotspot_type, video);");
			disableButton([ '#add_text' , '#add_Modal', '#add_Tooltip', '#add_image' ,'#add_link' ,'#savehotspots' ]);
			$("#video_div").show();
		}

		function addLink(){
			disableButton([ '#add_text' , '#add_Modal', '#add_Tooltip', '#add_image' ,'#add_video' ,'#savehotspots' ]);
			$("#link_div").show();
		}

		////edit

		function editText(){
			disableButton(['#edit_Tooltip', '#edit_modal', '#edit_image', '#edit_video' ,'#edit_link' ,'#saveEdit' ]);
			$("#text_div_edit").show();

		}

		function editTooltip(){
			disableButton(['#edit_text' , '#edit_modal',  '#edit_image', '#edit_video' ,'#edit_link' ,'#saveEdit' ]);
			$("#tooltip_div_edit").show();
		}

		function editModal(){
			disableButton(['#edit_text','#edit_Tooltip',  '#edit_image', '#edit_video' ,'#edit_link' ,'#saveEdit' ]);
			$("#modal_div_edit").show();
		}


		function editImage(){
			disableButton([ '#edit_text' , '#edit_modal', '#edit_Tooltip', '#edit_video' ,'#edit_link' ,'#saveEdit' ]);
			$("#image_div_edit").show();
		}

		function editVideo(){
			disableButton([ '#edit_text' , '#edit_modal', '#edit_Tooltip', '#edit_image' ,'#edit_link' ,'#saveEdit' ]);
			$("#video_div_edit").show();
		}

		function editScene(){
			disableButton([ '#edit_text' , '#edit_modal', '#edit_Tooltip', '#edit_image' ,'#edit_video' ,'#saveEdit' ]);
			$("#scene_div_edit").show();
		}


		function onclickCancel(){
			$("#text_div").hide();
			$("#modal_div").hide();
			$("#Tooltip_div").hide();
			$("#image_div").hide();
			$("#video_div").hide();
			$("#link_div").hide();
			enableButton(['#add_text','#add_Tooltip', '#add_Modal', '#add_image', '#add_video' ,'#add_link','#savehotspots']);
		}
		function saveEdit(){
			var text_t_edit = $('#text_t_edit').val();
			var text_d_edit = $('#text_d_edit').val();

			var tooltip_t_edit = $('#tooltip_t_edit').val();
			var tooltip_d_edit = $('#tooltip_d_edit').val();

			var modal_t_edit = $('#modal_t_edit').val();
			var modal_d_edit = $('#modal_d_edit').val();

			var image_input_edit = $('#image_input_edit').val();

			var video_input_edit = $('#video_input_edit').val();

			var scene_d_edit = $('#scene_d_edit').val();


			var hotspod_id =hotspots[uniqname];
			$.ajax({
				url: "",
				type: "POST",
				data: {
					editoptions: true,
					text_t_edit: text_t_edit,
					text_d_edit: text_d_edit,
					tooltip_t_edit: tooltip_t_edit,
					tooltip_d_edit: tooltip_d_edit,
					modal_t_edit: modal_t_edit,
					modal_d_edit: modal_d_edit,
					image_input_edit: image_input_edit,
					video_input_edit: video_input_edit,
					scene_d_edit: scene_d_edit,
					hotspod_id: hotspod_id
				},
				success: function(data) {
				}
			})
			$( "[data-popup-close]" ).trigger( "click" );
		}

		function SaveHot(){
			i += 1;
			uniqname = "spot_new_" + i;

			krpano.call("screentosphere(mouse.x,mouse.y,m_ath,m_atv);");
			var scene_num = krpano.get('scene.count');
			var current_scene = krpano.get('xml.scene');
			var user_id = $('#user_id').val();
			var tour_id = $('#tour_id').val();
			var title_t = $('#text_title').val();
			var title_d = $('#text_text').val();
			var tooltip_t = $('#tooltip_t').val();
			var tooltip_d = $('#tooltip_d').val();
			var modal_t = $('#modal_t').val();
			var modal_d = $('#modal_d').val();
			//image
			var image_url  = $('#image_url').val();
			//Video
			var video_url = $('#video_url').val();
			// SCENE
			var scene = $('.selectpicker').val();
			var posX = krpano.get('m_ath');
			var posY = krpano.get('m_atv');

			$.ajax({
				url: "",
				type: "POST",
				data: {
					insertoptions: true,
					user_id: user_id,
					tour_id: tour_id,
					title_t: title_t,
					title_d: title_d,
					tooltip_t: tooltip_t,
					tooltip_d: tooltip_d,
					modal_t: modal_t,
					modal_d: modal_d,
					image_url: image_url,
					video_url: video_url,
					scene: scene,
					posX:posX,
					posY:posY,
				},
				success: function(r) {

					hotspots[uniqname] = r; //JSON.parse(r);
					krpano.call("addhotspot(" + uniqname + ");");
					if (typeof currentHotspotData == 'undefined'){
						currentHotspotData = {};
						currentHotspotData.ath = krpano.get('view.hlookat');
						currentHotspotData.atv = krpano.get('view.vlookat');

						hotspot_done.style.display = 'inline-block';
					}
					else // THIS HOTSPOT HAVE AADITIONAL DATA FROM HOTDPOT LIST
					{
						if (currentHotspotData.hotspot_type == 'normal') {
							krpano.call("set(hotspot[" + uniqname + "].linkedscene, " + currentHotspotData.linkedscene + ");");
						}
						if (currentHotspotData.hotspot_type == 'text') {
							krpano.call("set(hotspot[" + uniqname + "].hotspot_text, " + currentHotspotData.hotspot_text + ");");
						}
					}
					krpano.call("set(hotspot[" + uniqname + "].onclick,  js(showPopup(" + uniqname + ")););");
					krpano.call("set(hotspot[" + uniqname + "].ath, " + posX + ");");
					krpano.call("set(hotspot[" + uniqname + "].sceneName, " + current_scene + ");");
					krpano.call("set(hotspot[" + uniqname + "].atv, " + posY + ");");
					krpano.call("set(hotspot[" + uniqname + "].hotspot_type, normal);");
					krpano.call("set(hotspot[" + uniqname + "].hotspot_text,"+ title_d +" ");
					krpano.call("set(hotspot[" + uniqname + "].url, assets/images/hotspot.png);");
				}
			}).then(function(){
				var user_id = $('#user_id').val('');
				var tour_id = $('#tour_id').val('');
				var title_t = $('#text_title').val('');
				var title_d = $('#text_text').val('');
				var tooltip_t = $('#tooltip_t').val('');
				var tooltip_d = $('#tooltip_d').val('');
				var modal_t = $('#modal_t').val('');
				var modal_d = $('#modal_d').val('');
				$( "[data-popup-close]" ).trigger( "click" );
			})
		}

		function editHotspot(){
			// enableButton(['#edit_text', '#edit_Tooltip', '#edit_modal', '#edit_image' ,'#edit_video' ,'#edit_link' ]);
			disableButton(['#move_hotspot', '#delete_hotpost','#edit_hotpost']);
			var hotspod_id =hotspots[uniqname];
			console.log(hotspod_id);
			$.ajax({
				url: "",
				type: "POST",
				data: {
					getoptions:true,
					hotspod_id:hotspod_id
				},
				dataType: 'json',
				success:function(data) {
					if(!(!data.text_t && !data.text_d)){
						if(data.text_t.length > 0 || data.text_.length > 0){
							$('#text_t_edit').val(data.text_t);
							$('#text_d_edit').text(data.text_d);
							disableButton(['#edit_text', '#edit_Tooltip', '#edit_modal', '#edit_image' ,'#edit_video' ,'#edit_link' ]);
							$('#text_div_edit').show();
							$('#tooltip_div_edit ').hide();
							$('#modal_div_edit ').hide();
							$('#video_div_edit').hide();
							$('#image_div_edit').hide();
							$('#scene_div_edit').hide();
							// console.log(data.text_t.length)
						}
					}

					if(!(!data.tooltip_t && !data.tooltip_d)){
						if(data.tooltip_t.length > 0 || data.tooltip_d.length > 0){
							$('#tooltip_t_edit').val(data.tooltip_t);
							$('#tooltip_d_edit').text(data.tooltip_d);
							disableButton(['#edit_text', '#edit_Tooltip', '#edit_modal', '#edit_image' ,'#edit_video' ,'#edit_link' ]);
							$('#text_div_edit').hide();
							$('#tooltip_div_edit ').show();
							$('#modal_div_edit ').hide();
							$('#video_div_edit').hide();
							$('#image_div_edit').hide();
							$('#scene_div_edit').hide();
							// console.log(data.text_t.length)
						}
					}

					if(!(!data.modal_d && !data.modal_t)){
						if(data.modal_t.length > 0 || data.tooltip_d.length > 0){
							$('#modal_t_edit').val(data.modal_t);
							$('#modal_d_edit').text(data.modal_d);
							disableButton(['#edit_text', '#edit_Tooltip', '#edit_modal', '#edit_image' ,'#edit_video' ,'#edit_link' ]);
							$('#text_div_edit').hide();
							$('#tooltip_div_edit ').hide();
							$('#modal_div_edit ').show();
							$('#video_div_edit').hide();
							$('#image_div_edit').hide();
							$('#scene_div_edit').hide();
							// console.log(data.text_t.length)
						}
					}

					// if(!data.video_url){
					// 	if(data.video_url.length  > 0 ){
					// 		$('#video_input_edit').val(data.video_url);
					// 		disableButton(['#edit_text', '#edit_Tooltip', '#edit_modal', '#edit_image' ,'#edit_video' ,'#edit_link' ]);
					// 		$('#video_div_edit').show();
					// 		// console.log(data.text_t.length)
					// 	}
					// }

					if(!data.video_url){
						if(data.video_url.length  > 0 ){
							$('#video_input_edit').val(data.video_url);
							disableButton(['#edit_text', '#edit_Tooltip', '#edit_modal', '#edit_image' ,'#edit_video' ,'#edit_link' ]);
							// $('#video_div_edit').show();
							$('#text_div_edit').hide();
							$('#tooltip_div_edit ').hide();
							$('#modal_div_edit ').hide();
							$('#video_div_edit').show();
							$('#image_div_edit').hide();
							$('#scene_div_edit').hide();
							// console.log(data.text_t.length)
						}
					}

					if(!(data.image_url)  !== "undefined"){
						if(data.image_url.length  > 0 ){
							$('#image_input_edit').val(data.image_url);
							disableButton(['#edit_text', '#edit_Tooltip', '#edit_modal', '#edit_image' ,'#edit_video' ,'#edit_link' ]);
							$('#text_div_edit').hide();
							$('#text_div_edit').hide();
							$('#tooltip_div_edit ').hide();
							$('#modal_div_edit ').hide();
							$('#video_div_edit').hide();
							$('#image_div_edit').show();
							$('#scene_div_edit').hide();
							// console.log(data.text_t.length)
						}
					}

					if(!data.link_scene){
						if(data.link_scene.length  > 0 ){
							$('scene_d_edit').val(data.link_scene);
							disableButton(['#edit_text', '#edit_Tooltip', '#edit_modal', '#edit_image' ,'#edit_video' ,'#edit_link' ]);

							$('#text_div_edit').hide();
							$('#text_div_edit').hide();
							$('#tooltip_div_edit ').hide();
							$('#modal_div_edit ').hide();
							$('#video_div_edit').hide();
							$('#image_div_edit').hide();
							$('#scene_div_edit').show();
							// console.log(data.text_t.length)
						}
					}

				}
			})
			// $( "#add_hotpost" ).trigger( "click" );

		}

		function list_scene() {
			krpano.call("set(hotspot[" + uniqname + "].ondown, '');");

			show_link.style.display = 'block';
			hotspot_done.style.display = 'none';
		}

		function get_link() {
			var scene = selectbox.value;
			krpano.call("set(hotspot[" + uniqname + "].linkedscene, " + scene + ");");
			hotspot_add_done();
		}

		var removedHotspot = [];

		function addRemovedHotspot(name) {
			removedHotspot.push(name);
		}

		function deleteHotspot() {
		if (confirm("Are you  Sure? ") == true) {
				//krpano.call("set(hotspot[" + i + "].onclick, 'removehotspot(get(name));');");
				krpano.call("removehotspot("+uniqname+");");
				$("[data-popup-close]").trigger("click");
		}else{
				return false;
			}
		}

		function done_remove() {

			enableButton(['add_hotpost', '#remove_hotpost', '#moveHotspot', '#set_defaultView'])
			document.getElementById('done_remove').style.display = 'none';

			add_hotpost.disabled = false;
			var hotspot_count = getHotspotsCount();
			for (i = 0; i < hotspot_count; i++) {
				krpano.call("set(hotspot[" + i + "].onclick, '');");
			}
		}

		function choose_hotSpot_type() {
			$('#hotpost_done').hide();
			document.getElementById('add_hotpost').style.display = 'inline-block';
			$('#choose_hotSpot_type_id').show();

			//this line make hotspot can't move anymore :)
			krpano.call("set(hotspot[" + uniqname + "].ondown, '');");

		}

		function setHotSpotType_Text() {
			$('#choose_hotSpot_type_id').hide();
			krpano.call("set(hotspot[" + uniqname + "].hotspot_type, text);");

			$('#input_text_dialog').show();
			$('#input_text_dialog #text_input_hotspot').val('');
		}

		function setHotSpotType_Nomal() {
			$('#choose_hotSpot_type_id').hide();
			krpano.call("set(hotspot[" + uniqname + "].hotspot_type, normal);");
			$('#show_link').show();
		}

		function hotspot_add_text_from_input() {
			$('#input_text_dialog').hide();
			krpano.call("set(hotspot[" + uniqname + "].hotspot_text, " + $('#text_input_hotspot').val() + ");");
			hotspot_add_done();
		}

		function hotspot_add_done() {
			$('#input_text_dialog').hide();
			$('#show_link').hide();
			add_hotpost.disabled = false;

			enableButton(['#remove_hotpost', '#moveHotspot', '#set_defaultView']);
		}

		var defaultViewList = {};

		function setDefaultView() {
			var scene = krpano.get('xml.scene');

			defaultViewList[scene] = {};
			defaultViewList[scene].hlookat = krpano.get('view.hlookat');
			defaultViewList[scene].vlookat = krpano.get('view.vlookat');
			defaultViewList[scene].fov = krpano.get('view.fov');

			alert('Applied default view hlookat: ' + defaultViewList[scene].hlookat + ' , vlookat: ' + defaultViewList[scene].vlookat + ' , fov: ' + defaultViewList[scene].fov);
		}

		function rotateToDefaultViewOf(scene) {
			//if current scene have edited default view but not save yet, the xml not have changed, so default view still in xml value,
			// we need to rotate to default view.
			if (typeof defaultViewList[scene] != 'undefined') {
				krpano.set('view.hlookat', defaultViewList[scene].hlookat);
				krpano.set('view.vlookat', defaultViewList[scene].vlookat);
				krpano.set('view.fov', defaultViewList[scene].fov);
			}
		}

		function hmv(currentHotspot, currentScene, i) {

// 				if (typeof currentHotspot !== "object") return false;
// 				var hotspotList = superHotspot.hotspotList;
// 				//var sceneName = this.kr.get('xml.scene');
// 				var sceneName = currentScene;
// 				var currentHotspotData = {};
// 				currentHotspotData.ath = currentHotspot.ath;
// 				currentHotspotData.atv = currentHotspot.atv;
// 				currentHotspotData.hotspot_type = currentHotspot.hotspot_type;
// 				currentHotspotData.sceneName    = sceneName;
// 				currentHotspotData.reRender     = 'true';

// 				if ( typeof currentHotspot.linkedscene != 'undefined')
// 					currentHotspotData.linkedscene = currentHotspot.linkedscene;
// 				else if ( typeof currentHotspot.hotspot_text != 'undefined' )
// 					currentHotspotData.hotspot_text = currentHotspot.hotspot_text;
// 				else
// 					console.error('no hotspot data found: ');

// 				console.info (currentHotspotData);

// 				current_vTour_hotspot_counter++;
// 				hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()] = currentHotspotData;

			//if hotspot just live in js var ( not live in xml yet )
			if (currentHotspot.url == 'assets/images/hotspot.png') {
				//hm... do nothing, it's auto re-locate itself
			}
			else // it live in xml, and will auto-reload-by krpano, so we need to
			{
				//1. add it to removed list
				addRemovedHotspot(currentHotspot.name);
				//2. make it render - able
				krpano.call("set(hotspot[" + i + "].xreRender, 'true')");
			}
		}

		function editHotspots() {
			// disableButton(['#add_hotpost', '#remove_hotpost', '#set_defaultView'])

			//create
			// i += 1;
			krpano.call("screentosphere(mouse.x,mouse.y,m_ath,m_atv);");
			krpano.call("set(hotspot[" + uniqname + "].onclick,  js(showPopup()););");
		}

		function moveHotspot() {
			krpano.call("screentosphere(mouse.x,mouse.y,m_ath,m_atv);");
				krpano.call("set(hotspot[" + uniqname + "].ondown, draghotspot(););");
		}

		function showPopup( uniqn ) {
			uniqname = uniqn;
			enableButton(['add_hotpost', '#remove_hotpost', '#set_defaultView'])
			$("#edit-remove-move").show();
			$('#text_div_edit').hide();
			$('#tooltip_div_edit ').hide();
			$('#modal_div_edit ').hide();
			$('#video_div_edit').hide();
			$('#image_div_edit').hide();
			$('#scene_div_edit').hide();

			// $('#edit-remove-move').css('position', 'fixed');
			// var hotspot_count = getHotspotsCount();
			// for (var i = 0; i < hotspot_count; i++) {
			// 	krpano.call("set(hotspot[" + i + "].ondown, '');");
		}

		// function moveHotspotDone() {
		// 	enableButton(['add_hotpost', '#remove_hotpost', '#set_defaultView'])
		// 	console.log("asd");
		// 	// var hotspot_count = getHotspotsCount();
		// 	// for (var i = 0; i < hotspot_count; i++) {
		// 	// 	krpano.call("set(hotspot[" + i + "].ondown, '');");
		// 	// }
		// 	$("#moveHotspot").show();
		// 	$("#moveHotspotDone").hide();
		// }

		/**
		 *
		 * @returns {boolean}
		 */
		function isReady() {
			if (
				add_hotpost.disabled == false
			) {
				return true;
			}
			return false;
		}

		function superHotspotObj(krpano_Obj) {
			var thisAlias = this;

			this.sceneCount = krpano_Obj.get('scene.count');
			this.hotspotList = {};
			this.kr = krpano_Obj;
			this.firstTimesSave = 0;

			this.saveCurrentHotspotFromCurrentScene = function () {
				// if ( thisAlias.firstTimesSave == 0 ){thisAlias.firstTimesSave = 1;}

				sceneName = this.kr.get('xml.scene');
				console.log(sceneName);
				// console.info('saveCurrentHotspotFromCurrentScene: ' + sceneName);
				thisAlias.hotspotList[sceneName] = {};
				var hotspot_count = thisAlias.kr.get('hotspot.count');
				for (var i = 0; i < hotspot_count; i++) {
					// console.log(thisAlias.kr.get('hotspot[' + i + '].url'));
					if (/hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /vtourskin_hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /information\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url'))) {
						// console.log('collecting hotspot: ' + i);
						// console.info(thisAlias.kr.get('hotspot[' + i + ']'));

						thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()] = {
							'ath': thisAlias.kr.get('hotspot[' + i + '].ath'),
							'atv': thisAlias.kr.get('hotspot[' + i + '].atv'),
							'sceneName': thisAlias.kr.get('hotspot[' + i + '].sceneName'),
							'hotspot_type': thisAlias.kr.get('hotspot[' + i + '].hotspot_type'),
							'reRender': 'true'
						}
						if (/vtourskin_hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /information\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url'))) {
							//hotspot which is aready in xml shouldnt re-render by js anymore, if not, doulicate hotspot will apperent.
							// console.log('superHotspot: xreRender: [' + i + '] ' + thisAlias.kr.get('hotspot[' + i + '].xreRender'));

							if (thisAlias.kr.get('hotspot[' + i + '].xreRender') == 'true') {
								thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].reRender == 'true'
							}
							else
								thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].reRender = 'false';
						}

						if (thisAlias.kr.get('hotspot[' + i + '].hotspot_type') == 'normal') {
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].linkedscene = thisAlias.kr.get('hotspot[' + i + '].linkedscene');
						}
						if (thisAlias.kr.get('hotspot[' + i + '].hotspot_type') == 'text') {
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].hotspot_text = thisAlias.kr.get('hotspot[' + i + '].hotspot_text');
						}
						if (thisAlias.kr.get('hotspot[' + i + '].hotspot_type') == 'video') {
							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].hotspot_text = thisAlias.kr.get('hotspot[' + i + '].video');
						}
						current_vTour_hotspot_counter++;
					}
					else {
					}
				}
			};

			/**
			 * Add hotspots into scene
			 */
			this.loadHotspotsToCurrentSceneFromSavedData = function () {
				sceneName = this.kr.get('xml.scene');

				for (var hotspotId in thisAlias.hotspotList[sceneName]) {
					var currentHotspotData = thisAlias.hotspotList[sceneName][hotspotId];

					if (thisAlias.hotspotList[sceneName][hotspotId].reRender == "true") {
						addHotspot(currentHotspotData);
					}
				}

				//this go as sub-job

				for (var i in removedHotspot) {
					if (removedHotspot[i].match(/spot_new_/g) == null)
						krpano.call('removehotspot(' + removedHotspot[i] + ');');
					else
						removedHotspot.splice(i, 1); //remove
				}

				rotateToDefaultViewOf(sceneName);
			};

			this.getData = function () {
				if (true) {
					thisAlias.saveCurrentHotspotFromCurrentScene();
				}
				// if(thisAlias.firstTimesSave == 0){thisAlias.saveCurrentHotspotFromCurrentScene();}
				return thisAlias;
			};

			this.setData = function (data) {
				thisAlias = data;
			}
		}

		var superHotspot = new superHotspotObj(krpano);
	</script>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
	$('.selectpicker').selectpicker();
	// $('.selectpicker').selectpicker();
	// $(".selectpicker").css("marginTop") = "2px";
	// $(".selectpicker").css("marginBottom") = "2px";
	});
</script>
</body>
</html>
