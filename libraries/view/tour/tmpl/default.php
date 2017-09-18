<!DOCTYPE html>
<html>
<head>
	<title>VR360 Globalvision - <?php echo $this->tour->name; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<!-- Charset -->
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta http-equiv="x-ua-compatible" content="IE=edge"/>

	<meta name="description" content="<?php echo Vr360Configuration::getConfig('siteDescription'); ?>">
	<meta name="keywords" content="<?php echo Vr360Configuration::getConfig('siteKeyword'); ?>">

	<meta itemprop="image" content="[thumbnail]">

	<!-- Globalvision -->
	<link rel="stylesheet" type="text/css" href="./assets/tour.min.css">
	<script src="assets/js/tour.js"></script>

	<!-- SEO Metadata -->
	<meta name="robots" content="index, follow" />
	<!-- Twitter -->
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="@GlobalVision360" />
	<meta name="twitter:creator" content="@GlobalVision360" />
	<meta name="twitter:description" content="<?php echo Vr360Configuration::getConfig('siteDescription'); ?>">
	<!-- Twitter summary card with large image must be at least 280x150px -->
	<!-- <meta name="twitter:image:src" content="http://www.example.com/image.jpg"> -->

	<!-- Opengraph -->
	<meta property="og:url" content="<?php echo $_SERVER['REQUEST_URI']; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="VR360 Globalvision - <?php echo $this->tour->name; ?>" />
	<meta property="og:description" content="<?php echo Vr360Configuration::getConfig('siteDescription'); ?>"/>
	<!-- <meta property="og:image" content="http://graphics8.nytimes.com/images/2011/12/08/technology/bits-newtwitter/bits-newtwitter-tmagArticle.jpg" /> -->
</head>
<body>

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
	<script>
		embedpano({
			swf: "tour.swf",
			xml: "_/<?php echo $data->tour->dir; ?>/vtour/tour.xml",
			target: "pano",
			html5: "auto",
			mobilescale: 1.0,
			passQueryParameters: true
		});
	</script>
</div>
<script type="text/javascript">
var vr_mode=false;
var krpano=document.getElementById('krpanoSWFObject');
function shareFacebook(){
	var urlfb='https://api.addthis.com/oexchange/0.8/forward/facebook/offer?url='+window.location.href;
	window.open(urlfb);
}
function shareTwitter(){
	var urltt='https://api.addthis.com/oexchange/0.8/forward/twitter/offer?url='+window.location.href;
	window.open(urltt);
}
function shareGooglePlus(){
	var urlgp='https://api.addthis.com/oexchange/0.8/forward/google_plusone_share/offer?url='+window.location.href;
	window.open(urlgp);
}
function toggleEmbedcode(){
	document.getElementById('embedcode').style.display=(document.getElementById('embedcode').style.display=='none'?'block':'none');document.getElementById('embed_code_txt').value='<iframe width="800px" height="400px" src="'+window.location.href+'"></iframe>';}$(document).ready(function(){if(vr_mode)krpano.call('wait(LOAD); webvr.enterVR();');
});
</script>
</body>
</html>
