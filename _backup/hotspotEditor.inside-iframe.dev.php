<?php
if(isset($_GET['t']) && isset($_GET['p']))
{
	$vt = $_GET['t'];
	$vp = $_GET['p'];
}
else
{
	die('error.!');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta http-equiv="x-ua-compatible" content="IE=edge" />
	<style>
		@-ms-viewport { width:device-width; }
		@media only screen and (min-device-width:800px) { html { overflow:hidden; } }
		html { height:100%; }
		body { height:100%; overflow:hidden; margin:0; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#FFFFFF; background-color:#000000; }
	</style>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

	<script src="http://data.globalvision.ch/krpano/1.19/tour.js"></script>
</head>
<body>

	<button id="add_hotpost"  style="position: absolute; z-index: 9999; top: 10%; left: 24%;" onclick="add_hotspot_to_scene()">ADD Hotspot</button>
	<button id="hotpost_done" style="position: absolute; z-index: 9999; top: 15%; left: 24%; display: none;" onclick="choose_hotSpot_type();">Next step >> </button>

	<button id="remove_hotpost" style="position: absolute; z-index: 9999; top: 10%; left: 40%;" onclick="remove_hotspot()">Remove Hotspot</button>
	<button id="done_remove" style="position: absolute; z-index: 9999; top: 15%; left: 40%; display: none;" onclick="done_remove()">Done</button>

	<button id="set_defaultView" style="position: absolute; z-index: 9999; top: 10%; left: 66%;" onclick="setDefaultView()">Set DefaultView</button>

	<div id="choose_hotSpot_type_id" style="position: absolute; z-index: 9999; top: 40%; left: 10px; display: none; background-color: gray; padding: 10px; opacity: 0.95;">
		Choose hotspot type:
		<button onclick="setHotSpotType_Text()">Text Popup</button>
		<button onclick="setHotSpotType_Nomal()">Normal</button>
	</div>

	<div id="input_text_dialog" style="position: absolute; z-index: 9999; top: 40%; left: 10px; display: none; background-color: gray; padding: 10px; opacity: 0.95;">
		<input id='text_input_hotspot' type="text" size="30" placeholder="Input Text for your hotspot here" />
		<button onclick="hotspot_add_text_from_input()">Done</button>
	</div>

	<div id="show_link" style="position: absolute; z-index: 9999; top: 40%; left: 10px; display: none; background-color: gray; padding: 10px; opacity: 0.95;">
		Linked scene:
		<select id="selectbox" >
		</select>
		<button id="done_link" onclick="get_link()">Done</button>
	</div>

	<div id="pano" style="width:100%;height:100%;">
		<noscript><table style="width:100%;height:100%;"><tr style="vertical-align:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>
		<script type="text/javascript">
			embedpano({swf:"http://data.globalvision.ch/krpano/1.19/tour.swf", xml:"http://vr360.globalvision.ch/_/<?php echo $vt; ?>/vtour/tour.xml?" + Math.round(Math.random()*1000000000).toString(), target:"pano", html5:"prefer", passQueryParameters:true});

			var krpano = document.getElementById('krpanoSWFObject');

			var add_hotpost   = document.getElementById('add_hotpost');
			var hotspot_done  = document.getElementById('hotpost_done');
			var selectbox     = document.getElementById('selectbox');
			var showlink 	  = document.getElementById('show_link');

			var i = 0;
			var uniqname      = '';
			var scene_nums    = krpano.get('scene.count');
			var hotspotList   = [];
			var current_scene = '';
			var current_vTour_hotspot_counter = 0;
			var current_randome_val           = Math.round(Math.random()*1000000000).toString() + Math.round(Math.random()*1000000000).toString();

			$.getJSON('http://vr360.globalvision.ch/_/<?php echo $vt; ?>/data.json?' + Math.random(), function (JSONdata){
				data = JSONdata;
				console.info(data);
				// document.title = data.tourDes;
				$('.b1').show();

				for ( var ii in data.panoList )
				{
					// console.info(data.panoList[i].des);
					option = document.createElement( 'option' );
				    option.value = ii;
					option.text  = data.panoList[ii].des;
				    selectbox.add( option );
				}
			});

			function add_hotspot_to_scene( currentHotspotData )
			{
				document.getElementById('remove_hotpost').disabled = true;
				i += 1;
				krpano.call("screentosphere(mouse.x,mouse.y,m_ath,m_atv);");

				var scene_num = krpano.get('scene.count');
				current_scene = krpano.get('xml.scene');

		        var posX = krpano.get('m_ath');
		        var posY = krpano.get('m_atv');

				uniqname = "spot_new_" + i;
				krpano.call("addhotspot(" + uniqname + ");");

				if(typeof currentHotspotData == 'undefined')  // new nomal hotspot added
				{
					currentHotspotData = {};
					currentHotspotData.ath = krpano.get('view.hlookat');
					currentHotspotData.atv = krpano.get('view.vlookat');
					krpano.call("set(hotspot[" + uniqname + "].ondown, draghotspot(););");

					hotspot_done.style.display = 'block';
					add_hotpost.disabled = true;
				}
				else
				{
					if(krpano.get('hotspot['+i+'].hotspot_type') == 'normal')
					{
						krpano.call("set(hotspot[" + uniqname + "].linkedscene, "+currentHotspotData.linkedscene+");");
					}
					if(krpano.get('hotspot['+i+'].hotspot_type') == 'text')
					{
						krpano.call("set(hotspot[" + uniqname + "].hotspot_text, "+currentHotspotData.hotspot_text+");");
					}
				}

		        krpano.call("set(hotspot[" + uniqname + "].ath, "+currentHotspotData.ath+");");
		        krpano.call("set(hotspot[" + uniqname + "].sceneName, "+current_scene+");");
		        krpano.call("set(hotspot[" + uniqname + "].atv, "+currentHotspotData.atv+");");
		        krpano.call("set(hotspot[" + uniqname + "].hotspot_type, "+currentHotspotData.hotspot_type+");");
		        krpano.call("set(hotspot[" + uniqname + "].url, hotspot.png);");
		        krpano.call("hotspot[" + uniqname + "].loadstyle('hotspot_ani_white');");

				// console.info(currentHotspotData);
			}

			function list_scene()
			{
				// console.log(uniqname);
				// console.log(krpano.get('hotspot[' + uniqname + '].parr'));
				krpano.call("set(hotspot[" + uniqname + "].ondown, '');");

				show_link.style.display = 'block';
				hotspot_done.style.display = 'none';
			}

			function get_link()
			{
				var scene = selectbox.value;
				krpano.call("set(hotspot[" + uniqname + "].linkedscene, "+scene+");");
				hotspot_add_done();
			}

			function remove_hotspot()
			{
				document.getElementById('done_remove').style.display = 'block';
				add_hotpost.disabled = true;
				var hotspot_count = krpano.get('hotspot.count');
				for(i = 0; i < hotspot_count; i++)
				{
					krpano.call("set(hotspot[" + i + "].onclick, 'removehotspot(get(name));');");
				}
			}
			function done_remove()
			{
				document.getElementById('done_remove').style.display = 'none';
				add_hotpost.disabled = false;
				var hotspot_count = krpano.get('hotspot.count');
				for(i = 0; i < hotspot_count; i++)
				{
					krpano.call("set(hotspot[" + i + "].onclick, '');");
				}
				console.log(hotspot_count);
			}
			function choose_hotSpot_type()
			{
				$('#hotpost_done').hide();
				$('#choose_hotSpot_type_id').show();
			}
			function setHotSpotType_Text()
			{
				$('#choose_hotSpot_type_id').hide();
				krpano.call("set(hotspot[" + uniqname + "].hotspot_type, text);");
				// krpano.call("set(hotspot[" + uniqname + "].url, " + "http://data.globalvision.ch/krpano/1.19/skin/information.jpg" + ");");
				$('#input_text_dialog').show();
			}
			function setHotSpotType_Nomal()
			{
				$('#choose_hotSpot_type_id').hide();
				krpano.call("set(hotspot[" + uniqname + "].hotspot_type, normal);");
				$('#show_link').show();
			}
			function hotspot_add_text_from_input()
			{
				$('#input_text_dialog').hide();
				krpano.call("set(hotspot[" + uniqname + "].hotspot_text, "+$('#text_input_hotspot').val()+");");
				hotspot_add_done();
			}
			function hotspot_add_done()
			{
				$('#input_text_dialog').hide();
				$('#show_link').hide();
				add_hotpost.disabled = false;
				document.getElementById('remove_hotpost').disabled = false;
			}

			var defaultViewList = {};
			function setDefaultView( )
			{
		        var scene = krpano.get('xml.scene');

		        defaultViewList[scene]   = {};
		        defaultViewList[scene].hlookat = krpano.get('view.hlookat');
		        defaultViewList[scene].vlookat = krpano.get('view.vlookat');
		        defaultViewList[scene].fov     = krpano.get('view.fov');
			}
			function rotateToDefaultViewOf( scene )
			{
				if( typeof defaultViewList[scene] != 'undefined' )
				{
					krpano.set('view.hlookat', defaultViewList[scene].hlookat);
					krpano.set('view.vlookat', defaultViewList[scene].vlookat);
					krpano.set('view.fov', defaultViewList[scene].fov);	
				}
			}
			function superHotspotObj (krpano_Obj)
			{
				var thisAlias = this;

				this.sceneCount   = krpano_Obj.get('scene.count');;
				this.kr           = krpano_Obj;
				this.hotspotList  = {};
				this.firstTimesSave = 0;


				this.saveCurrentHotspotFromCurrentScene = function (  )
				{
					// if ( thisAlias.firstTimesSave == 0 ){thisAlias.firstTimesSave = 1;}
					// console.info('**************************************************************');

					sceneName = this.kr.get('xml.scene'); console.info('saveCurrentHotspotFromCurrentScene: '+sceneName);
					thisAlias.hotspotList[sceneName] = {};
					var hotspot_count = thisAlias.kr.get('hotspot.count');
					for(var i = 0; i < hotspot_count; i++)
					{
						if( thisAlias.kr.get('hotspot['+i+'].url') == 'hotspot.png' || thisAlias.kr.get('hotspot['+i+']._url') == atob("aHR0cDovL2RhdGEuZ2xvYmFsdmlzaW9uLmNoL2tycGFuby8xLjE5L3NraW4vdnRvdXJza2luX2hvdHNwb3QucG5n") || thisAlias.kr.get('hotspot['+i+']._url') == atob("aHR0cDovL2RhdGEuZ2xvYmFsdmlzaW9uLmNoL2tycGFuby8xLjE5L3NraW4vaW5mb3JtYXRpb24uanBn") )
						{
							//if(typeof thisAlias.hotspotList[sceneName] == 'undefined') thisAlias.hotspotList[sceneName] = {};
							// thisAlias.hotspotList[sceneName] = {};

							thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()] = {
								'ath': thisAlias.kr.get('hotspot[' + i + '].ath'),
								'atv': thisAlias.kr.get('hotspot[' + i + '].atv'),
								'sceneName': thisAlias.kr.get('hotspot[' + i + '].sceneName'),
								// 'linkedscene': thisAlias.kr.get('hotspot[' + i + '].linkedscene'),
								'hotspot_type': thisAlias.kr.get('hotspot[' + i + '].hotspot_type'),
								'reRender' : 'true'
							}
							if(thisAlias.kr.get('hotspot['+i+']._url') == atob("aHR0cDovL2RhdGEuZ2xvYmFsdmlzaW9uLmNoL2tycGFuby8xLjE5L3NraW4vdnRvdXJza2luX2hvdHNwb3QucG5n") || thisAlias.kr.get('hotspot['+i+']._url') == atob("aHR0cDovL2RhdGEuZ2xvYmFsdmlzaW9uLmNoL2tycGFuby8xLjE5L3NraW4vaW5mb3JtYXRpb24uanBn") )
							{
								thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].reRender = 'false' ;
							}

							if(thisAlias.kr.get('hotspot['+i+'].hotspot_type') == 'normal')
							{
								thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].linkedscene = thisAlias.kr.get('hotspot[' + i + '].linkedscene').replace('scene_', '');
							}
							if(thisAlias.kr.get('hotspot['+i+'].hotspot_type') == 'text')
							{
								thisAlias.hotspotList[sceneName][current_randome_val + current_vTour_hotspot_counter.toString()].hotspot_text = thisAlias.kr.get('hotspot[' + i + '].hotspot_text');
							}
							current_vTour_hotspot_counter++;
						}
					}
				}

				this.loadHotspotsToCurrentSceneFromSavedData = function ( )
				{
					console.info('************load**************************************************');

					sceneName = this.kr.get('xml.scene'); console.info( 'loadHotspotsToCurrentSceneFromSavedData: ' + sceneName );
					for ( var hotspotId in thisAlias.hotspotList[sceneName] )
					{
						var currentHotspotData = thisAlias.hotspotList[sceneName][hotspotId];
						if(thisAlias.hotspotList[sceneName][hotspotId].reRender == "true")
						{
							//console.info(currentHotspotData);
							add_hotspot_to_scene( currentHotspotData );
						}
					}

					//this go as sub-job
					rotateToDefaultViewOf( sceneName );
				}

				this.getData = function ()
				{
					if(true){thisAlias.saveCurrentHotspotFromCurrentScene();}
					// if(thisAlias.firstTimesSave == 0){thisAlias.saveCurrentHotspotFromCurrentScene();}
					return thisAlias;
				}
				this.setData = function (data)
				{
					thisAlias = data;
				}
			}

		var superHotspot = new superHotspotObj(krpano);
		</script>


	</div>

</body>
</html>
