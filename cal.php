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
<html>
<meta charset="utf-8">
<head>
<style>
<?php
$remote = 24519;
$month = array("Monat", "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");
if(isset($_GET['jahr'])and !empty($_GET['jahr'])) //Prüfen ob Jahr ausgewählt wurde
	{
	$jahr = $_GET['jahr'];  //Jahr uebernehmen
}else {
	$jahr = date("Y");      //Ohne Auswahl aktuelles Jahr übernehmen
}
$objekt = $_GET['objekt'];

include ('includes/functions.php');
include ('css/insert_css.php');
?>
 
</style>
</head>
<body class="cal">

<!--Hier können Sie ihre Seitenelemente eingeben in PHP oder HTML wenn der Belegungsplan unten angezeigt werden soll-->

<div id="cal">
<form action="index.php" method="get" >
	<select name="jahr" size="1">
<?php
for ($year = date("Y"); $year <= (date("Y")+2); $year++){  //Auswahlfeld aktuelles Jahr und die 2 folgenden
    if ($year == $jahr){
		echo '<option selected>'.$year.'</option>';
	}else{
		echo   "<option>".$year."</option>";
	}
}
if ($jahr < date("Y") OR $jahr > $year){
	echo '<option selected>'.$jahr.'</option>';
}
echo '<input type="hidden" name="objekt" value="'.$objekt.'">';
?>
     </select>
	<input type="submit" value="wechseln">
</form>
<?php
//formular();
/*
Hier wird der Kalender als Tabelle dargestellt!
Zuerst werden die 12 Zeilen in einer Schleife erzeugt
und dann werden je nach Monat und Schaltjahr die 
Reihen bzw. die Tage erzeugt!
Dazu wird mit verschachtelten FOR-Schleifen
und IF-Anweisungen gearbeitet!
*/
cal($month, $jahr, $objekt);
?>

</div>

<!--Hier können Sie ihre Seitenelemente eingeben in PHP oder HTML wenn der Belegungsplan oben angezeigt werden soll-->

</body>
</html>


