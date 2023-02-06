<?php
#########################################
#Belegungsplan 0.7			#
#©2017 Daniel ProBer alias HackMeck	#
#http://hackmeck.bplaced.net		#
#GERMANY				#
#					#
#Mail: daproc@gmx.net			#
#Paypal: daproc@gmx.net			#
#					#
#Zeigt einen Kalender mit 		#
#Belegung für ein Objekt an.		#
#z.B. Ferienwohnung 			#
#########################################

/*	Belegungsplan ist Freie Software: Sie können ihn unter den Bedingungen
    der GNU General Public License, wie von der Free Software Foundation,
    Version 2 der Lizenz weiterverbreiten und/oder modifizieren.

    Belegungsplan wird in der Hoffnung, dass er nützlich sein wird, aber
    OHNE JEDE GEWÄHRLEISTUNG, bereitgestellt; sogar ohne die implizite
    Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
    Siehe die GNU General Public License für weitere Details.

    Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
    Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>
*/
	

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
