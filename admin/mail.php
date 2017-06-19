<?php
#####################################
#Belegungsplan 0.6					#
#©2017 Daniel ProBer alias HackMeck	#
#http://hackmeck.bplaced.net		#
#GERMANY							#
#									#
#Mail: daproc@gmx.net				#
#Paypal: daproc@gmx.net				#
#									#
#Zeigt einen Kalender mit 			#
#Belegung für ein Objekt an.		#
#z.B. Ferienwohnung 				#
#####################################

$controll = $remote;
if($controll != 24519){
	echo 'Kein Zugriff!';
	exit();
}

$mail = $_POST['empfaenger'];
$betreff = $_POST['betreff'];
$nachricht = $_POST['nachricht'];
$emailadr = $_POST['bcc'];		
$header  = 'MIME-Version: 1.0' . "\r\n";
$header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$header .= 'From: '.$emailadr . "\r\n";
$header .= 'Bcc: '.$emailadr . "\r\n";
mail($mail, $betreff, $nachricht, $header);
echo '<p>Die Nachricht wurde erfolgreich versendet</p>';
echo '<p><b>An: </b><br>'.$mail.'</p>';
echo '<p><b>Betreff: </b><br>'.$betreff.'</p>';
echo '<p><b>Nachricht: </b><br>'.$nachricht.'</p>';

?>
