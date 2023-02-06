<?php
#########################################
#Belegungsplan  			#
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
?>
<html>
<head>
<style>
<?php
$controll = $remote;
if($controll != 24519){
	echo 'Kein Zugriff!';
	exit();
}

$month = array("Monat", "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");
if(isset($_GET['jahr'])and !empty($_GET['jahr'])) //Prüfen ob Jahr ausgewählt wurde
	{
	$jahr = $_GET['jahr'];  //Jahr uebernehmen
}else {
	$jahr = date("Y");      //Ohne Auswahl aktuelles Jahr übernehmen
}
$objekt = $_GET['objekt'];
$obj = $_GET['objekt'];
include ('css/insert_css.php');
?>
</style>
</head>
<body>
<?php
if (!empty($_GET['an']) AND !empty($_GET['ab'])){  // Prüfe ob Felder ausgefüllt sind
	$dan = $_GET['an'];
	$dab = $_GET['ab'];
	if(preg_match("/[\d]{2}\.[\d]{2}\.[\d]{4}/", $dan)) {			//Prüft ob Format dd.mm.yyyy
		$array = explode(".", $dan);
		$date_an = $array[2]."-".$array[1]."-".$array[0];              //Erstellt Format yyyy-mm-dd
	}elseif(preg_match("/[\d]{4}\-[\d]{2}\-[\d]{2}/", $dan)) {		//Prüft ob Format yyyy-mm-dd
		$date_an = $dan;
	}else{
		echo 'Bitte richtiges Format bei der Anreise angeben z.Bsp. 01.01.2017';
		formular($objekt);
		year($jahr, $obj);
		cal($month, $jahr);
		exit();
	}
	function validateDatean($date_an, $format = 'Y-m-d'){
		$d = DateTime::createFromFormat($format, $date_an);
		return $d && $d->format($format) == $date_an;
	}
	if (validateDatean($date_an)){
		$an = $date_an;
	}else{
		echo 'Bitte gültiges Anreisedatum eingeben z.Bsp. 01.01.2017';
		formular($objekt);
		year($jahr, $obj);
		cal($month, $jahr);
		exit();
	}
	if(preg_match("/[\d]{2}\.[\d]{2}\.[\d]{4}/", $dab)) {			//Prüft ob Format dd.mm.yyyy
		$array = explode(".", $dab);
		$date_ab = $array[2]."-".$array[1]."-".$array[0];              //Erstellt Format yyyy-mm-dd
	}elseif(preg_match("/[\d]{4}\-[\d]{2}\-[\d]{2}/", $dan)) {		//Prüft ob Format yyyy-mm-dd
		$date_ab = $dab;
	}else{
		echo 'Bitte richtiges Format bei der Abreise angeben z.Bsp. 01.01.2017';
		formular($objekt);
		year($jahr, $obj);
		cal($month, $jahr);
		exit();
	}
	function validateDateab($date_ab, $format = 'Y-m-d'){
		$d = DateTime::createFromFormat($format, $date_ab);
		return $d && $d->format($format) == $date_ab;
	}
	if (validateDateab($date_ab)){
		$ab = $date_ab;
	}else{
		echo 'Bitte gültiges Abreisedatum eingeben z.Bsp. 01.01.2017';
		formular($objekt);
		year($jahr, $obj);
		cal($month, $jahr);
		exit();
	}
}else{
	$an = date("Y-m-d");
	$ab = date("Y-m-d",strtotime("+3 day"));
	formular($objekt);
	year($jahr, $obj);
	cal($month, $jahr);
	exit ();
}
$new_an = new DateTime($an);
$new_ab = new DateTime($ab);
if ($new_an >= $new_ab){
			echo "Anreise muss vor der Abreise liegen!";
			formular($objekt);
			year($jahr, $obj);
			cal($month, $jahr);
			exit();
		}
$new_an->modify('+1 day');
$new_ab->modify('-1 day');
$booking = array();
for ($date = $new_an; $date <= $new_ab; $date->modify('+1 day')) {
		$booking[] = $date->format('Y-m-d');
}
$sql = "SELECT datean, dateab FROM times WHERE objekt_id = ".$objekt." ORDER BY datean" ;
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
			echo "In diesem Zeitraum gibt es schon eine Belegung";
			formular($objekt);
			year($jahr, $obj);
			cal($month, $jahr);
			exit();
		}
	}
}
$insert = "INSERT INTO times (datean, dateab, confirmed, objekt_id) VALUES ('$an', '$ab', '1', '$objekt')";
$write = mysqli_query($db_link, $insert);
if ( ! $write )
{
  die('Ungültige Abfrage: ' . mysqli_error($db_link));
}else{
	echo 'Ihre Buchung vom '.$an.' bis '.$ab.' wurde korrekt eingetragen<br>';
	echo '<a href="index.php?in=book&objekt='.$objekt.'">Zum Kalender</a>';
}
?>