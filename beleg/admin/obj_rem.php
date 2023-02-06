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
//$month = array("Monat", "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");
//include ('includes/functions.php');
//include ('css/insert_css.php');
mysqli_set_charset($db_link, 'utf8');
?>
</style>
</head>
<body>
<?php
if(isset($_GET['objekt'])AND !empty($_GET['objekt']) AND empty($_GET['ask'])){ //Prüfen ob Datensatz ausgewählt wurde
	$id = $_GET['objekt'];  //ID übergeben
	$sql = "SELECT id, name FROM objekt WHERE id = '".$id."'" ;
	$db_erg = mysqli_query( $db_link, $sql );
	if ( ! $db_erg ){
		die('Ungültige Abfrage: ' . mysqli_error());
	}
	while ($zeile = mysqli_fetch_array( $db_erg, MYSQLI_ASSOC)){
		$name = $zeile['name'];
		$id = $zeile['id'];
		echo 'Objekt "'.$name.'" mit der ID '.$id.' und alle Belegungen dazu wirklich löschen?<br>'; 
		echo '<a href=index.php?in=obr&ask=yes&id='.$id.'&objekt='.$id.'>JA</a> | <a href=index.php?in=alt>NEIN</a>';
	}
}elseif(isset($_GET['ask'])and !empty($_GET['ask']) AND isset($_GET['id'])and !empty($_GET['id'])){
	$id = $_GET['id'];
        $loesch_form = "DELETE FROM forms WHERE objektid = '".$id."'";
	$remove_form = mysqli_query($db_link, $loesch_form);
	$loesch_bel = "DELETE FROM times WHERE objekt_id = '".$id."'";
	$remove_bel = mysqli_query($db_link, $loesch_bel);
	echo 'Die Belegungen <br>';
	$loesch_obj = "DELETE FROM objekt WHERE id = '".$id."'";
	$loesch = mysqli_query($db_link, $loesch_obj);
	echo 'und das Objekt gelöscht<br>';
	echo '<a href=index.php?in=alt>zurück</a>';	
}elseif(!isset($_GET['id'])AND empty($_GET['id'])) {
	echo 'Keine Daten zum löschen ausgewählt<br>';
	echo '<a href=index.php?in=alt>zurück</a>';
	exit();
}
?>