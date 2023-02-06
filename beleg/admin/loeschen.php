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
//include ('includes/functions.php');
//include ('css/insert_css.php');
mysqli_set_charset($db_link, 'utf8');
?>
</style>
</head>
<body>
<?php
if(isset($_GET['id'])AND !empty($_GET['id']) AND empty($_GET['ask'])){ //Prüfen ob Datensatz ausgewählt wurde
	$id = $_GET['id'];  //ID übergeben
	$sql = "SELECT times.*, objekt.name FROM times LEFT JOIN objekt ON times.objekt_id = objekt.id WHERE times.id = '".$id."'" ;
	$db_erg = mysqli_query( $db_link, $sql );
	if ( ! $db_erg ){
		die('Ungültige Abfrage: ' . mysqli_error());
	}
	while ($zeile = mysqli_fetch_array( $db_erg, MYSQLI_ASSOC)){
		$datean = $zeile['datean'];
		$dateab = $zeile['dateab'];
		$id = $zeile['id'];
		$guest_id = $zeile['user'];
		$obj_name = $zeile['name'];
		echo 'Datensatz "'.date('d.m.y', strtotime($datean)).' bis '.date('d.m.y', strtotime($dateab)).' aus Objekt: '.$obj_name.'" wirklich löschen?<br>'; 
		echo '<a href=index.php?in=loe&ask=yes&id='.$id.'&user='.$guest_id.'>JA</a> <a href=index.php?in=rem>NEIN</a>';
	}
}elseif(isset($_GET['ask'])and !empty($_GET['ask']) AND isset($_GET['id'])and !empty($_GET['id'])){
	$id = $_GET['id'];
	$guest_id = $_GET['user'];
	$rem_book = "DELETE FROM booking WHERE guest_id = '".$guest_id."'";
	$re_book = mysqli_query($db_link, $rem_book);
	$rem_guest = "DELETE FROM guests WHERE id = '".$guest_id."'";
	$re_guest = mysqli_query($db_link, $rem_guest);
	$loeschen = "DELETE FROM times WHERE id = '".$id."'";
	$loesch = mysqli_query($db_link, $loeschen);
	echo 'Datensatz gelöscht<br>';
	echo '<a href=index.php?in=rem>zurück</a>';	
}elseif(!isset($_GET['id'])AND empty($_GET['id'])) {
	echo 'Keine Daten zum löschen ausgewählt<br>';
	echo '<a href=index.php?in=rem>zurück</a>';
	exit();
}
?>