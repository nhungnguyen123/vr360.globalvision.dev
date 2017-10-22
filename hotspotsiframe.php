<?php

$hotSpotImgUrl     = base64_encode("/assets/images/hotspot.png");
$hotSpotInfoImgUrl = base64_encode("/assets/images/information.png");
$uId               = $_GET['uId'];
$tourUrl           = '//' . $_SERVER['HTTP_HOST'] . '/_/' . $uId . '/vtour';
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
	<link rel="stylesheet" type="text/css" href="./assets/tour.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/hotspots.min.css">
	<script type="text/javascript" src="./assets/jquery-2.2.4.min.js"></script>
	<script src='<?php echo $tourUrl . '/tour.js'; ?>'></script>

	<!-- Bootstrap -->
	<script type="text/javascript" src="./assets/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.css">

	<link rel="stylesheet" href="./assets/font-awesome/css/font-awesome.css">

</head>
<body>
<div id="button-container">
	<button type="button" id="add_hotpost" class="btn btn-primary" onclick="add_hotspot_to_scene();">Add hotspot</button>
	<button type="button" id="hotpost_done" class="btn btn-primary" onclick="choose_hotSpot_type();">Choose type</button>

	<button type="button" id="remove_hotpost" class="btn btn-danger" onclick="remove_hotspot();">Remove hotspot</button>
	<button type="button" id="done_remove" class="btn btn-danger" onclick="done_remove();">Removed done</button>

	<button type="button" id="set_defaultView" class="btn btn-primary" onclick="setDefaultView();">Set default view</button>

	<button type="button" id="moveHotspot" class="btn btn-warning" onclick="moveHotspot();">Move hotspots</button>
	<button type="button" id="moveHotspotDone" class="btn btn-warning" onclick="moveHotspotDone();">Moved done</button>
</div>
<div id="choose_hotSpot_type_id">
	Choose hotspot type:
	<button type="button" class="btn btn-default" onclick="setHotSpotType_Text()">Text Popup</button>
	<button type="button" class="btn btn-default" onclick="setHotSpotType_Nomal()">Scene linking</button>
</div>

<div id="input_text_dialog">
	<input id='text_input_hotspot' type="text" size="30" placeholder="Input Text for your hotspot here"/>
	<button type="button" class="btn btn-default" onclick="hotspot_add_text_from_input()">Finish</button>
</div>

<div id="show_link">
	Linked scene: <select id="selectbox"></select>
	<button id="done_link" onclick="get_link()">Done</button>
</div>

<div id="pano">
	<noscript>
		<table style="width: 100%; height: 100%;">
			<tr style="vertical-align: middle;">
				<td>
					<div style="text-align: center;">
						ERROR:<br/>
						<br/>Javascript not activated<br/>
						<br/>
					</div>
				</td>
			</tr>
		</table>
	</noscript>
	<script type="text/javascript">
		embedpano({
			swf: '<?php echo $tourUrl . '/tour.swf'; ?>',
			xml: '<?php echo $tourUrl . '/tour.xml?' . time(); ?>',
			target: "pano",
			html5: "prefer",
			passQueryParameters: true
		});

		var krpano = document.getElementById('krpanoSWFObject');

		var add_hotpost = document.getElementById('add_hotpost');
		var hotspot_done = document.getElementById('hotpost_done');
		var selectbox = document.getElementById('selectbox');
		var showlink = document.getElementById('show_link');

		var i = 0;
		var uniqname = '';
		var scene_nums = krpano.get('scene.count');
		var hotspotList = [];
		var current_scene = '';
		var current_vTour_hotspot_counter = 0;
		var current_randome_val = Math.round(Math.random() * 1000000000).toString() + Math.round(Math.random() * 1000000000).toString();

		$.getJSON('http://<?php echo $_SERVER['HTTP_HOST']; ?>/_/<?php echo $_GET['uId']; ?>/data.json?' + Math.random(), function (JSONdata) {
			data = JSONdata;

			$('.b1').show();

			for (var ii in data.panoTitle) {
				// console.info(data.panoList[i].des);
				option = document.createElement('option');
				option.value = ii;
				option.text = data.panoTitle[ii];
				selectbox.add(option);
			}
		});

		function add_hotspot_to_scene(currentHotspotData) {
			document.getElementById('remove_hotpost').disabled = true;
			document.getElementById('moveHotspot').disabled = true;

			document.getElementById('add_hotpost').style.display = 'none';

			i += 1;
			krpano.call("screentosphere(mouse.x,mouse.y,m_ath,m_atv);");

			var scene_num = krpano.get('scene.count');
			current_scene = krpano.get('xml.scene');

			var posX = krpano.get('m_ath');
			var posY = krpano.get('m_atv');

			uniqname = "spot_new_" + i;
			krpano.call("addhotspot(" + uniqname + ");");

			if (typeof currentHotspotData == 'undefined')  // new nomal hotspot added
			{
				currentHotspotData = {};
				currentHotspotData.ath = krpano.get('view.hlookat');
				currentHotspotData.atv = krpano.get('view.vlookat');
				krpano.call("set(hotspot[" + uniqname + "].ondown, draghotspot(););");

				hotspot_done.style.display = 'inline-block';
				add_hotpost.disabled = true;
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

			krpano.call("set(hotspot[" + uniqname + "].ath, " + currentHotspotData.ath + ");");
			krpano.call("set(hotspot[" + uniqname + "].sceneName, " + current_scene + ");");
			krpano.call("set(hotspot[" + uniqname + "].atv, " + currentHotspotData.atv + ");");
			krpano.call("set(hotspot[" + uniqname + "].hotspot_type, " + currentHotspotData.hotspot_type + ");");
			krpano.call("set(hotspot[" + uniqname + "].url, assets/images/hotspot.png);");
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

		function remove_hotspot() {
			document.getElementById('done_remove').style.display = 'inline-block';
			document.getElementById('remove_hotpost').style.display = 'none';
			document.getElementById('moveHotspot').disabled = true;

			add_hotpost.disabled = true;
			var hotspot_count = krpano.get('hotspot.count');
			for (i = 0; i < hotspot_count; i++) {
				//krpano.call("set(hotspot[" + i + "].onclick, 'removehotspot(get(name));');");
				krpano.call("set(hotspot[" + i + "].onclick, 'removehotspot(get(name)); js(addRemovedHotspot(get(name)));');");
			}
		}

		function done_remove() {
			document.getElementById('done_remove').style.display = 'none';
			document.getElementById('remove_hotpost').style.display = 'inline-block';
			document.getElementById('moveHotspot').disabled = false;

			add_hotpost.disabled = false;
			var hotspot_count = krpano.get('hotspot.count');
			for (i = 0; i < hotspot_count; i++) {
				krpano.call("set(hotspot[" + i + "].onclick, '');");
			}
			console.log(hotspot_count);
			console.info(removedHotspot);
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
			document.getElementById('remove_hotpost').disabled = false;
			document.getElementById('moveHotspot').disabled = false;
		}

		var defaultViewList = {};

		function setDefaultView() {
			var scene = krpano.get('xml.scene');

			defaultViewList[scene] = {};
			defaultViewList[scene].hlookat = krpano.get('view.hlookat');
			defaultViewList[scene].vlookat = krpano.get('view.vlookat');
			defaultViewList[scene].fov = krpano.get('view.fov');

			alert ('Applied default view hlookat: ' + defaultViewList[scene].hlookat + ' , vlookat: ' + defaultViewList[scene].vlookat + ' , fov: ' + defaultViewList[scene].fov);
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

		function moveHotspot() {
			document.getElementById('add_hotpost').disabled = true;
			document.getElementById('remove_hotpost').disabled = true;

			var hotspot_count = krpano.get('hotspot.count');
			for (var i = 0; i < hotspot_count; i++) {
				krpano.call("set(hotspot[" + i + "].ondown, 'draghotspot(); js(hmv(get(hotspot[" + i + "]), get(xml.scene), " + i + ") );')");
			}
			$("#moveHotspot").hide();
			$("#moveHotspotDone").show();
		}

		function moveHotspotDone() {
			document.getElementById('add_hotpost').disabled = false;
			document.getElementById('remove_hotpost').disabled = false;

			var hotspot_count = krpano.get('hotspot.count');
			for (var i = 0; i < hotspot_count; i++) {
				krpano.call("set(hotspot[" + i + "].ondown, '');");
			}
			$("#moveHotspot").show();
			$("#moveHotspotDone").hide();
		}

		/**
		 *
		 * @returns {boolean}
		 */
		function isReady() {
			if (
				add_hotpost.disabled == false
				&& document.getElementById('remove_hotpost').disabled == false
				&& document.getElementById('moveHotspot').disabled == false
			) {
				return true;
			}
			return false;
		}

		function superHotspotObj(krpano_Obj) {
			var thisAlias = this;

			this.sceneCount = krpano_Obj.get('scene.count');
			this.kr = krpano_Obj;
			this.hotspotList = {};
			this.firstTimesSave = 0;


			this.saveCurrentHotspotFromCurrentScene = function () {
				// if ( thisAlias.firstTimesSave == 0 ){thisAlias.firstTimesSave = 1;}

				sceneName = this.kr.get('xml.scene');
				console.info('saveCurrentHotspotFromCurrentScene: ' + sceneName);
				thisAlias.hotspotList[sceneName] = {};
				var hotspot_count = thisAlias.kr.get('hotspot.count');
				for (var i = 0; i < hotspot_count; i++) {
					console.log(thisAlias.kr.get('hotspot[' + i + '].url'));
					if (/hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /vtourskin_hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /information\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url'))) {
						console.log('collecting hotspot: ' + i);
						console.info(thisAlias.kr.get('hotspot[' + i + ']'));

						thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()] = {
							'ath': thisAlias.kr.get('hotspot[' + i + '].ath'),
							'atv': thisAlias.kr.get('hotspot[' + i + '].atv'),
							'sceneName': thisAlias.kr.get('hotspot[' + i + '].sceneName'),
							'hotspot_type': thisAlias.kr.get('hotspot[' + i + '].hotspot_type'),
							'reRender': 'true'
						}
						if (/vtourskin_hotspot\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url')) || /information\.png/.test(thisAlias.kr.get('hotspot[' + i + '].url'))) {
							//hotspot which is aready in xml shouldnt re-render by js anymore, if not, doulicate hotspot will apperent.
							console.log('superHotspot: xreRender: [' + i + '] ' + thisAlias.kr.get('hotspot[' + i + '].xreRender'));

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
						current_vTour_hotspot_counter++;
					}
					else {
					}
				}
			}

			this.loadHotspotsToCurrentSceneFromSavedData = function () {
				sceneName = this.kr.get('xml.scene');

				for (var hotspotId in thisAlias.hotspotList[sceneName]) {
					var currentHotspotData = thisAlias.hotspotList[sceneName][hotspotId];
					if (thisAlias.hotspotList[sceneName][hotspotId].reRender == "true") {
						add_hotspot_to_scene(currentHotspotData);
					}
					else {

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
			}

			this.getData = function () {
				if (true) {
					thisAlias.saveCurrentHotspotFromCurrentScene();
				}
				// if(thisAlias.firstTimesSave == 0){thisAlias.saveCurrentHotspotFromCurrentScene();}
				return thisAlias;
			}
			this.setData = function (data) {
				thisAlias = data;
			}
		}

		var superHotspot = new superHotspotObj(krpano);
	</script>


</div>

</body>
</html>
