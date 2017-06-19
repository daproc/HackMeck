<?php
#########################################
#Belegungsplan 0.6			#
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
	
require_once ('includes/beleg-config.php');
$db_link = mysqli_connect (
                     HOST, 
                     USER,
                     PASSWORD, 
                     DATABASE
                    );
?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
<link rel="stylesheet" href="css/book.css">
<style>
</style>
</head>
<body>
<?php
$status = 0;
$referer = getenv("HTTP_REFERER");
if (empty($_POST['an']) && empty($_POST['ab']) && !isset($_POST['an']) && !isset($_POST['ab'])){
	echo '<div class="fehler">Buchung nur über Belegungsplan möglich!</div>'; 
	echo "<a href=\"".$referer."\"> Zurück </a>";
}else{

	$fields = array("an", "ab", "anrede", "name", "vorname", "str", "plz", "ort", "tel", "mail", "anzahl_gesamt","anzahl_erw", "anzahl_kind", "tiere", "text", "objekt");
	for ($i = 0; $i < count($fields); $i++){
		if (array_key_exists($fields[$i], $_POST)) {
			${$fields[$i]} = $_POST[$fields[$i]];
		}else{
			${$fields[$i]} = 0;
		}
		
	}
	
//Validierug und Umwandlung der An- und Abreisedaten
	$dan = $_POST['an'];
	$dab = $_POST['ab'];
	if(preg_match("/[\d]{2}\.[\d]{2}\.[\d]{4}/", $dan)) {			//Prüft ob Format dd.mm.yyyy
		$array = explode(".", $dan);
		$date_an = $array[2]."-".$array[1]."-".$array[0];              //Erstellt Format yyyy-mm-dd
	}elseif(preg_match("/[\d]{4}\-[\d]{2}\-[\d]{2}/", $dan)) {		//Prüft ob Format yyyy-mm-dd
		$date_an = $dan;
	}else{
		echo '<div class="fehler">Bitte richtiges Format bei der Anreise angeben z.Bsp. 01.01.2017</div>';
		echo "<a href=\"".$referer."\"> Zurück </a>";
		exit();
	}
	function validateDatean($date_an, $format = 'Y-m-d'){
		$d = DateTime::createFromFormat($format, $date_an);
		return $d && $d->format($format) == $date_an;
	}
	if (validateDatean($date_an)){
		$an = $date_an;
	}else{
		echo '<div class="fehler">Bitte gültiges Anreisedatum eingeben z.Bsp. 01.01.2017</div>';
		echo "<a href=\"".$referer."\"> Zurück </a>";
		exit();
	}
	if(preg_match("/[\d]{2}\.[\d]{2}\.[\d]{4}/", $dab)) {			//Prüft ob Format dd.mm.yyyy
		$array = explode(".", $dab);
		$date_ab = $array[2]."-".$array[1]."-".$array[0];              //Erstellt Format yyyy-mm-dd
	}elseif(preg_match("/[\d]{4}\-[\d]{2}\-[\d]{2}/", $dan)) {		//Prüft ob Format yyyy-mm-dd
		$date_ab = $dab;
	}else{
		echo '<div class="fehler">Bitte richtiges Format bei der Abreise angeben z.Bsp. 01.01.2017</div>';
		echo "<a href=\"".$referer."\"> Zurück </a>";
		exit();
	}
	function validateDateab($date_ab, $format = 'Y-m-d'){
		$d = DateTime::createFromFormat($format, $date_ab);
		return $d && $d->format($format) == $date_ab;
	}
	if (validateDateab($date_ab)){
		$ab = $date_ab;
	}else{
		echo '<div class="fehler">Bitte gültiges Abreisedatum eingeben z.Bsp. 01.01.2017</div>';
		echo "<a href=\"".$referer."\"> Zurück </a>";
		exit();
	}
	
	$new_an = new DateTime($an);
	$new_ab = new DateTime($ab);
	if ($new_an >= $new_ab){
				echo '<div class="fehler">Anreise muss vor der Abreise liegen!</div>';
				echo "<a href=\"".$referer."\"> Zurück </a>";
				exit();
			}
	$new_an->modify('+1 day');
	$new_ab->modify('-1 day');
	$booking = array();
	for ($date = $new_an; $date <= $new_ab; $date->modify('+1 day')) {
			$booking[] = $date->format('Y-m-d');
	}
	$sql = "SELECT datean, dateab FROM times WHERE objekt_id = ".$objekt." AND confirmed = 1 ORDER BY datean" ;
	$db_erg = mysqli_query( $db_link, $sql );
	if ( ! $db_erg )
	{
	  die('Ungültige Abfrage: ' . mysqli_error());
	}
	while ($zeile = mysqli_fetch_array( $db_erg, MYSQLI_ASSOC))
	{
		$datean = new DateTime($zeile['datean']);
		$dateab = new DateTime($zeile['dateab']);
		for ($date_db = clone $datean; $date_db <= $dateab; $date_db->modify('+1 day')) {
			$datum_vergl = $date_db->format('Y-m-d');
			if (in_array($datum_vergl, $booking)){
				echo '<div class="fehler">In diesem Zeitraum gibt es schon eine Belegung</div>';
				echo "<a href=\"".$referer."\"> Zurück </a>";
				exit();
			}
		}
	}
//persönliche Daten des Gastes und beziehen der Gast_ID	
	$ins_guest = "INSERT INTO guests (anrede, name, vorname, str, plz, ort, tel, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = mysqli_prepare ($db_link, $ins_guest);
	mysqli_stmt_bind_param ($stmt, 'ssssisis', $anrede, $name, $vorname, $str, $plz, $ort, $tel, $mail);
	mysqli_stmt_execute($stmt);
	if ( ! $stmt ){
		die('Ungültige Abfrage: ' . mysqli_error());
	}else{
		$guest_id = mysqli_insert_id($db_link);
	}
//Eintragen von An- und Abreise	
	$ins_times = "INSERT INTO times (datean, dateab, user, confirmed, objekt_id) VALUES (?, ?, ?, ?, ?)";
	$stmt = mysqli_prepare ($db_link, $ins_times);
	mysqli_stmt_bind_param ($stmt, 'ssiii', $an, $ab, $guest_id, $status, $objekt );
	mysqli_stmt_execute($stmt);
	if ( ! $stmt ){
		die('Ungültige Abfrage: ' . mysqli_error());
	}else{
		$times_id = mysqli_insert_id($db_link);
	}
//Eintragen der sonstigen Daten
	$ins_booking = "INSERT INTO booking (anzahl_pers, anzahl_erw, anzahl_kind, anzahl_tier, text, guest_id, times_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
	$stmt = mysqli_prepare ($db_link, $ins_booking);
	mysqli_stmt_bind_param ($stmt, 'sssssii', $anzahl_gesamt, $anzahl_erw, $anzahl_kind, $tiere, $text, $guest_id, $times_id);
	mysqli_stmt_execute($stmt);
	if ( ! $stmt ){
		die('Ungültige Abfrage: ' . mysqli_error());
	}else{
		$booking_id = mysqli_insert_id($db_link);
		$booking_nr = $guest_id.'-'.$times_id.'-'.$booking_id;
		echo 'Ihre Buchung wurde gespeichert<br>';
		echo 'Für weitere Anfragen verwenden sie bitte folgende Buchungsnummer: '.$booking_nr;
		
	}
	
//Mailadresse holen	
	$mailadr = "SELECT email FROM users ORDER BY ID DESC LIMIT 1";
	$stmt = mysqli_prepare ($db_link, $mailadr);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	//$db_erg = mysqli_query( $db_link, $obj );
	while ($zeile_c = mysqli_fetch_array( $result, MYSQLI_ASSOC)){
		$emailadr = $zeile_c['email'];
	}		
//Textbaustein holen
	$text = "SELECT buch_text FROM mail_text ORDER BY ID DESC LIMIT 1";
	$stmt = mysqli_prepare($db_link, $text);
	//mysqli_stmt_bind_param($stmt, 's', $obj_id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	while ($zeile_c = mysqli_fetch_array( $result, MYSQLI_ASSOC)){
		$buch_text = $zeile_c['buch_text'];
	}	
//Mail an Gast versenden
	// Betreff
	$betreff = 'Ihre Buchung';
	if($anrede == 'Frau'){
		$anr = 'Sehr geehrte Frau '.$name.',';
	}elseif($anrede == 'Herr'){
		$anr = 'Sehr geehrter Herr '.$name.',';
	}else{
		$anr = 'Sehr geehrte/ r Frau/ Herr '.$name.',';
	}
	$nachricht = '
<p>'.$anr.'</p>
<p>'.$buch_text.'</p>';

				
	$header  = 'MIME-Version: 1.0' . "\r\n";
	$header .= 'Content-type: text/html; charset=utf-8' . "\r\n";

	// zusätzliche Header
	$header .= 'From: '.$emailadr . "\r\n";
	$header .= 'Bcc: '.$emailadr . "\r\n";
	mail($mail, $betreff, $nachricht, $header);
	
//url für bearbeitung
	$path = getcwd();
	$path = explode('/', $path);
	$a = count($path);
	$ordner = $path[$a-1];
	$link = $_SERVER['REQUEST_URI'];
	$teile = explode( '/', $link );
	$key = array_search($ordner, $teile);
	//echo $_SERVER['SERVER_NAME'].'/';
	for ($i = 1; $i <= $key; $i++){
		$pfad[] = $teile[$i].'/';
	}
	$uri = $_SERVER['SERVER_NAME'].'/';
	//echo $uri.'<br>';
	$uri .= implode( '/', $pfad).'/admin/index.php?in=vali&bookingnr='.$booking_nr.'&objekt='.$objekt;
	$uri = str_replace("//", "/", $uri);
//Mail an Gastgeber mit Link für bestätigung
	$betreff = 'neue Buchungsanfrage';
	
	$nachricht = '<html>
					<meta charset="utf-8">
					<body>
					<p>Sie haben eine neue Buchungsanfrage, klicken sie auf folgenden Link um die Buchung zu bearbeiten</p>
					<p><a href="http://'.$uri.'">'.$uri.'</a></p>
					</body></html>';
				
	$header  = 'MIME-Version: 1.0' . "\r\n";
	$header .= 'Content-type: text/html; charset=utf-8' . "\r\n";

	
	mail($emailadr, $betreff, $nachricht, $header);
}

?>