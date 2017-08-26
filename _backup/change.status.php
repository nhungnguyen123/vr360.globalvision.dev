<?php
var_dump($_GET);
require_once('db.class.php');
// require_once('auth.class.php');
// require_once('data.ver.class.php');
require_once('configure.php');

//$options = getopt("k:u:t:v:n:");

//if ( $options['k'] != "ThisisSecreatTOKEN_Kkjsdk^&#^jhbdjnJHDASjajsdoKSDJkjwdasJKASJ@HSDjasdbncxvloas" ) die();

$db = new dbObj($_db);
//$db->change_vtour_status($options['u']);
$db->change_vtour_status($_GET['u'], 1);
//echo $_GET['u'];
//$optionsu=$options['u'];
//$to = $options['t'];
//$fullName = $options['n'];
/*
$subject = 'Panorama creation completed';

$headers = "From: GlobalVision Communication<info@globalvision.ch>\r\n";
$headers .= "Reply-To: info@globalvision.ch\r\n";
$headers .= "CC: nhan@globalvision.ch\r\n";
$headers .= "MIME-Version: 1.0\r\n";

$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


$message = '<html><body>'; $optionv = $options['v'];
$message .= <<<EOF
Hello $fullName,<br /><br />

Your panorama is uploaded and its Virtual Tour is ready. Please have a look <a href="http://vr360.globalvision.ch/preview.php?t=$optionsu"><font color="#3333ff"><b>here.</b></font></a> <br /><br />

If you would like to edit your Virtual Tour, please <a href="http://vr360.globalvision.ch/_index.php?t=$optionsu"><font color="#ffad33"><b>login here.</b></font></a><br /><br />

For more assistance, please contact <u><a href="mailto:info@globalvision.ch">info@globalvision.ch</a></u> <br /><br />

Thank you!
EOF;
$message .= '</body></html>';


mail($to, $subject, $message, $headers);
*/
?>
