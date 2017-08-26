<?php
require_once('db.class.php');
require_once('auth.class.php');
require_once('data.ver.class.php');
require_once('configure.php');

$db  = new dbObj($_db);
$re  = $db->get_u_id($_GET['t']);
$url = $re['u_id'];
?>
<html>
<head>
	<style>
		html, body {
			margin: 0px;
			padding: 0px;
		}
	</style>
	<meta charset="utf-8">
	<link rel="shortcut icon" type="image/png" href="http://vr360.globalvision.ch/icon.png">
	<title>VR360 GlobalVision Communication</title>
	<meta name="title" content="360° PANORAMA © GLOBALVISION COMMUNICATION">
	<meta name="description"
	      content="Creation of high-resolution 360° images in immersive and panoramic format. Photographic services related to immersive 360° vision. Our panoramas display on all internet browsers, mobile devices and smartphones, i.e. iOS and Android.">
	<meta name="keywords"
	      content="360°, 360 degrés, photo, photographie, photographe, panographe, prise de vue, reportage, technique, technologie, immersive, immersion, panorama, omnidirectionnel, regarder alentour, environnement, création de visite virtuelle, tour virtuel">
	<meta property="og:image"
	      content="http://vr360.globalvision.ch/_/<?php echo $url ?>/vtour/panos/1.tiles/thumb.jpg"/>
	<meta name="author" content="GlobalVision Communication"/>
	<meta name="owner" content="GlobalVision Communication"/>
</head>
<body>

<script src="../assets/jquery-2.2.4.min.js"></script>
<script src="../assets/krpano/1.19/tour.js"></script>

<div id="embedcode"
     style="z-index: 3; top: 50%; margin-top: 65px; margin-left: 75px; display: none; position: absolute; width: 360px; height: 50px;">
	<div style="background:#fff; padding:10px;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;-webkit-box-shadow: 1px 2px 2px 0px #434343;box-shadow: 1px 2px 2px 0px #434343;opacity:0.80;">
		<p style="margin:0px; padding:0px; margin-bottom:5px; font-family:Arial, Helvetica, sans-serif;font-size:13px; color:#000;text-align: left;text-shadow: 0px 0px #3f3f3f;">
			Copy and paste the following code into your web page to embed the 360° picture</p>
		<textarea id="embed_code_txt"
		          style="width: 334px; text-shadow: rgb(63, 63, 63) 0px 0px; margin: 0px; height: 52px;">iframe width="800px" height="400px" src="http://vr360.globalvision.ch/$_GET['t']"&gt;&lt;/iframe&gt;</textarea>
	</div>
</div>
<div id="pano" style="width:100%;height:100%;">

	<noscript>
		<table style="width:100%;height:100%;">
			<tr style="vertical-align:middle;">
				<td>
					<div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div>
				</td>
			</tr>
		</table>
	</noscript>
	<?php
	$filePath = __DIR__ . '/_/' . $url . '/vtour/tour.xml';
	$handle   = fopen($filePath, "r");
	$xml      = fread($handle, filesize($filePath));
	fclose($handle);

	$handle = fopen($filePath, "w");
	$xml    = str_replace('http://data.globalvision.ch', 'http://vr360.globalvision.ch/assets', $xml);
	fwrite($handle, $xml);
	fclose($handle);
	?>
	<script>
		embedpano({
			swf: "http://vr360.globalvision.ch/assets/krpano/1.19/tour.swf",
			xml: "http://vr360.globalvision.ch/_/<?php echo $url ?>/vtour/tour.xml?" + Math.round(Math.random() * 1000000000).toString(),
			target: "pano",
			html5: "prefer",
			initvars: {design: "flat"},
			passQueryParameters: true
		});
	</script>

	<script type="text/javascript">
		var vr_mode = <?php echo(isset($_GET['v']) ? 'true' : 'false'); ?>;

		var krpano = document.getElementById('krpanoSWFObject');

		function shareFacebook() {
			var urlfb = 'https://api.addthis.com/oexchange/0.8/forward/facebook/offer?url=' + window.location.href;
			window.open(urlfb);
		}

		function shareTwitter() {
			var urltt = 'https://api.addthis.com/oexchange/0.8/forward/twitter/offer?url=' + window.location.href;
			window.open(urltt);
		}

		function shareGooglePlus() {
			var urlgp = 'https://api.addthis.com/oexchange/0.8/forward/google_plusone_share/offer?url=' + window.location.href;
			window.open(urlgp);
		}

		function toggleEmbedcode() {
			document.getElementById('embedcode').style.display = (document.getElementById('embedcode').style.display == 'none' ? 'block' : 'none');
			document.getElementById('embed_code_txt').value = '<iframe width="800px" height="400px" src="' + window.location.href + '"></iframe>';

		}

		//if(vr_mode) krpano.call('wait(LOAD); webvr.enterVR();');


		$(document).ready(function () {
			if (vr_mode) krpano.call('wait(LOAD); webvr.enterVR();');
			//$("#krpanoSWFObject>div:eq(1)>div:eq(2)>div:eq(13)>div:eq(3)>div>div:eq(5)").trigger('click');
		});
	</script>
</div>
</body>

</html>
