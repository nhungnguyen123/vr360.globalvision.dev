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