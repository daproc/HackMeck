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
?>
<!DOCTYPE html>
<html lang="de">
<meta charset="utf-8">
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
//include ('includes/functions.php');
include ('css/insert_css.php');
mysqli_set_charset($db_link, 'utf8');
?>
</style>
</head>
<body>
<?php
echo '<ul>';
$sql = "SELECT times.*, objekt.name FROM times LEFT JOIN objekt ON times.objekt_id = objekt.id ORDER BY objekt_id, datean" ;
$db_erg = mysqli_query( $db_link, $sql );
if ( ! $db_erg )
{
  die('Ungültige Abfrage: ' . mysqli_error());
}
while ($zeile = mysqli_fetch_array( $db_erg, MYSQLI_ASSOC)){
	$datean = $zeile['datean'];
	$dateab = $zeile['dateab'];
	$id = $zeile['id'];
	$obj_name = $zeile['name'];
	echo '<li>'.date('d.m.y', strtotime($datean)).' bis '.date('d.m.y', strtotime($dateab)).' aus Objekt "'.$obj_name.'"  <a href=index.php?in=loe&id='.$id.'&name='.$obj_name.'>löschen</a></li><hr>';
}
echo '</ul>';
if(mysqli_num_rows($db_erg) == 0){
	echo 'Keine Daten in der Datenbanktabelle';
}
?>